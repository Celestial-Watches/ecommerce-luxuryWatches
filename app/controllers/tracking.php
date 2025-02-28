<?php
require_once '../config/conn.php';

$tracking_number = $_GET['tracking_number'] ?? '';

// Get transaction and details
$stmt = $conn->prepare("SELECT t.*, u.email FROM transactions t
  JOIN users u ON t.user_id = u.id
  WHERE tracking_number = ?");
$stmt->bind_param("s", $tracking_number);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();

$details = json_decode($transaction['details'], true);

// Fetch customer location details from details JSON
$customerLocation = $details['customer'] ?? [];
$customerCity    = $customerLocation['city']    ?? 'Rajkot';
$customerState   = $customerLocation['state']   ?? 'Gujarat';
$customerCountry = $customerLocation['country'] ?? 'India';

// Seller location is fixed: Delhi, India
$sellerCity    = 'Delhi';
$sellerState   = 'Delhi';
$sellerCountry = 'India';

// Build addresses for directions API
$customerAddress = "{$customerCity}, {$customerState}, {$customerCountry}";
$sellerAddress   = "{$sellerCity}, {$sellerCountry}";

// --- Get Fake Coordinates --- //
// This function returns preset coordinates for known cities. 
// You can update it to use a geocoding API if needed.
function getFakeCoords($city) {
    $locations = [
        'Rajkot' => ['lat' => 22.3039, 'lng' => 70.8022, 'zoom' => 12],
        'Mumbai' => ['lat' => 19.0760, 'lng' => 72.8777, 'zoom' => 11],
        'Delhi'  => ['lat' => 28.7041, 'lng' => 77.1025, 'zoom' => 11]
    ];
    return $locations[$city] ?? ['lat' => 20.5937, 'lng' => 78.9629, 'zoom' => 5];
}

// Get coordinates for customer and seller
$customerCoords = getFakeCoords($customerCity);
$sellerCoords   = getFakeCoords($sellerCity);

// Compute center point for the static map (using the midpoint)
$centerLat = ($customerCoords['lat'] + $sellerCoords['lat']) / 2;
$centerLng = ($customerCoords['lng'] + $sellerCoords['lng']) / 2;

// --- cURL Directions API integration (server-side) --- //
// Use seller as origin and customer as destination.
$origin      = $sellerAddress;
$destination = $customerAddress;

// Define departure and arrival times (Unix timestamps)
$departure_time = time();
$arrival_time   = time() + 3600; // example: one hour later

// Build the URL with query parameters
$queryParams = http_build_query([
    'arrival_time'               => $arrival_time,
    'departure_time'             => $departure_time,
    'alternatives'               => 'true',
    'avoid'                      => 'highways',
    'destination'                => $destination,
    'origin'                     => $origin,
    'units'                      => 'metric',
    'waypoints'                  => '', 
    'language'                   => 'en',
    'mode'                       => 'driving',
    'region'                     => 'en',
    'traffic_model'              => 'pessimistic',
    'transit_mode'               => 'train|tram|subway',
    'transit_routing_preference' => 'less_walking',
    'key'                        => 'AlzaSy4xmvFtL3iwhqNg9BXk4xDtdddr8wOmGHK',
]);

$url = 'https://maps.gomaps.pro/maps/api/directions/json?' . $queryParams;

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST  => 'GET',
]);

$directions_response = curl_exec($curl);
if (curl_errno($curl)) {
    $directions_error = curl_error($curl);
}
curl_close($curl);

$directions_data = json_decode($directions_response, true);
?>
<!DOCTYPE html>
<html>
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
        <div class="directions">
            <h2>Directions Data</h2>
            <?php if (isset($directions_error)): ?>
                <p>Error fetching directions: <?= htmlspecialchars($directions_error) ?></p>
            <?php else: ?>
                <!-- You can further process this JSON to show steps, distance, etc. -->
                <pre><?= htmlspecialchars(json_encode($directions_data, JSON_PRETTY_PRINT)) ?></pre>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
