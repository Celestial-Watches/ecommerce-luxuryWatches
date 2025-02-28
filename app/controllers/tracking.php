<?php
require_once '../config/conn.php';
require_once 'GifCreator.php'; 

$tracking_number = $_GET['tracking_number'] ?? '';

// Get transaction and details
$stmt = $conn->prepare("SELECT t.*, u.email FROM transactions t
  JOIN users u ON t.user_id = u.id
  WHERE tracking_number = ?");
$stmt->bind_param("s", $tracking_number);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();

if (!$transaction) {
    die("Transaction not found for tracking number: " . htmlspecialchars($tracking_number));
}

$details = json_decode($transaction['details'], true);

// Customer location details (with defaults)
$customerLocation = $details['customer'] ?? [];
$customerCity    = $customerLocation['city']    ?? 'Rajkot';
$customerState   = $customerLocation['state']   ?? 'Gujarat';
$customerCountry = $customerLocation['country'] ?? 'India';

// Seller location (fixed)
$sellerCity    = 'Delhi';
$sellerCountry = 'India';

// Build addresses (for directions API)
$customerAddress = "{$customerCity}, {$customerState}, {$customerCountry}";
$sellerAddress   = "{$sellerCity}, {$sellerCountry}";

// --- Get Fake Coordinates --- //
function getFakeCoords($city) {
    $locations = [
        'Rajkot' => ['lat' => 22.3039, 'lng' => 70.8022, 'zoom' => 12],
        'Mumbai' => ['lat' => 19.0760, 'lng' => 72.8777, 'zoom' => 11],
        'Delhi'  => ['lat' => 28.7041, 'lng' => 77.1025, 'zoom' => 11]
    ];
    return $locations[$city] ?? ['lat' => 20.5937, 'lng' => 78.9629, 'zoom' => 5];
}

$customerCoords = getFakeCoords($customerCity);
$sellerCoords   = getFakeCoords($sellerCity);

// Compute map center (midpoint between seller and customer)
$centerLat = ($customerCoords['lat'] + $sellerCoords['lat']) / 2;
$centerLng = ($customerCoords['lng'] + $sellerCoords['lng']) / 2;

// --- cURL Directions API integration ---
$queryParams = http_build_query([
    'arrival_time'               => time() + 3600,
    'departure_time'             => time(),
    'alternatives'               => 'true',
    'avoid'                      => 'highways',
    'destination'                => $customerAddress,
    'origin'                     => $sellerAddress,
    'units'                      => 'metric',
    'waypoints'                  => '', 
    'language'                   => 'en',
    'mode'                       => 'driving',
    'region'                     => 'en',
    'traffic_model'              => 'pessimistic',
    'transit_mode'               => 'train|tram|subway',
    'transit_routing_preference' => 'less_walking',
    'key'                        => 'AlzaSy4xmvFtL3iwhqNg9BXk4xDtdddr8wOmGHK', // Replace with your gomaps.pro API key
]);

$directionsURL = 'https://maps.gomaps.pro/maps/api/directions/json?' . $queryParams;

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL            => $directionsURL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);
$directions_response = curl_exec($curl);
if (curl_errno($curl)) {
    die("Directions API error: " . curl_error($curl));
}
curl_close($curl);

$directions_data = json_decode($directions_response, true);

// --- Decode Polyline --- //
function decodePolyline($encoded) {
    $points = [];
    $index = 0;
    $lat = 0;
    $lng = 0;
    $len = strlen($encoded);

    while ($index < $len) {
        $shift = 0;
        $result = 0;
        do {
            $b = ord($encoded[$index++]) - 63;
            $result |= (($b & 0x1f) << $shift);
            $shift += 5;
        } while ($b >= 0x20);
        $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
        $lat += $dlat;

        $shift = 0;
        $result = 0;
        do {
            $b = ord($encoded[$index++]) - 63;
            $result |= (($b & 0x1f) << $shift);
            $shift += 5;
        } while ($b >= 0x20);
        $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
        $lng += $dlng;

        $points[] = ['lat' => $lat * 1e-5, 'lng' => $lng * 1e-5];
    }
    return $points;
}

$routePoints = [];
if (isset($directions_data['routes'][0]['overview_polyline']['points'])) {
    $encodedPolyline = $directions_data['routes'][0]['overview_polyline']['points'];
    $routePoints = decodePolyline($encodedPolyline);
} else {
    // Fallback: linear interpolation between seller and customer (10 steps)
    $steps = 10;
    for ($i = 0; $i <= $steps; $i++) {
         $lat = $sellerCoords['lat'] + ($customerCoords['lat'] - $sellerCoords['lat']) * ($i / $steps);
         $lng = $sellerCoords['lng'] + ($customerCoords['lng'] - $sellerCoords['lng']) * ($i / $steps);
         $routePoints[] = ['lat' => $lat, 'lng' => $lng];
    }
}

// --- Generate Animated GIF Frames using gomaps Static API and cURL --- //
$frames = [];
$delays = []; // Delay (in 1/100th of a second) for each frame
$apiKey = 'AlzaSy4xmvFtL3iwhqNg9BXk4xDtdddr8wOmGHK'; // Replace with your gomaps.pro API key
$baseURL = 'https://maps.gomaps.pro/maps/api/staticmap';
$zoom = 6;
$size = '600x400';

// Fixed markers for seller and customer
$fixedMarkers = "markers={$sellerCoords['lat']},{$sellerCoords['lng']}|{$customerCoords['lat']},{$customerCoords['lng']}";

// Optionally include the route path if available
$pathParam = isset($encodedPolyline) ? "path=enc:$encodedPolyline" : "";

foreach ($routePoints as $point) {
    // Truck marker with a custom icon (ensure truck_icon.png is accessible on your server)
    $truckMarker = "markers=icon:truck_icon.png|{$point['lat']},{$point['lng']}";

    $queryParams = http_build_query([
         'center' => "{$centerLat},{$centerLng}",
         'zoom'   => $zoom,
         'size'   => $size,
         'key'    => $apiKey
    ]);
    
    // Build the full URL (concatenate extra parameters manually)
    $url = $baseURL . "?" . $queryParams . "&" . $fixedMarkers;
    if (!empty($pathParam)) {
        $url .= "&" . $pathParam;
    }
    $url .= "&" . $truckMarker;
    
    // Fetch the image via cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $imageData = curl_exec($ch);
    curl_close($ch);
    
    if ($imageData) {
        $frames[] = $imageData;
        $delays[] = 10; // 10 = 100ms delay per frame (adjust as needed)
    }
}

if (empty($frames)) {
    die("No frames were generated for the animation.");
}

// --- Create Animated GIF Using a Pure PHP GIF Encoder (GifCreator) --- //
// Instantiate GifCreator, pass frames and delays, and set loop count (0 = infinite loop)
$gc = new GifCreator();
$gc->create($frames, $delays, 0);
$gifBinary = $gc->getGif();

// Output the animated GIF
header('Content-Type: image/gif');
echo $gifBinary;
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" 
      href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" 
        crossorigin=""></script>

    <head>
        <title>Live Tracking</title>
        <style>
        #gomap { 
            height: 600px; 
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .tracking-header {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .directions {
            margin-top: 20px;
            background: #e9ecef;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tracking-header">
            <h1>Tracking #<?= htmlspecialchars($tracking_number) ?></h1>
            <p>Status: <?= ucwords(str_replace('_', ' ', $transaction['order_status'])) ?></p>
            <p>Customer Location: <?= htmlspecialchars($customerAddress) ?></p>
            <p>Seller Location: <?= htmlspecialchars($sellerAddress) ?></p>
        </div>
        <div id="gomap">
            <!-- Static map with markers for seller and customer -->
            <img src="https://maps.gomaps.pro/maps/api/staticmap?center=<?= $centerLat ?>,<?= $centerLng ?>&zoom=6&size=600x400&markers=<?= $sellerCoords['lat'] ?>,<?= $sellerCoords['lng'] ?>|<?= $customerCoords['lat'] ?>,<?= $customerCoords['lng'] ?>&key=AlzaSy4xmvFtL3iwhqNg9BXk4xDtdddr8wOmGHK" 
            alt="Map">
        </div>
    </div>
</body>
</html>

