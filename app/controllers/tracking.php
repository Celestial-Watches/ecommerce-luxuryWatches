<?php
require_once '../config/conn.php';

$tracking_number = $_GET['tracking_number'] ?? '';

// Get transaction and details
$stmt = $conn->prepare("SELECT t.*, u.email FROM transactionss t
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

// Fetch customer location details from details JSON
$customerLocation = $details['customer'] ?? [];
$customerCity    = $customerLocation['city']    ?? 'Rajkot';
$customerState   = $customerLocation['state']   ?? 'Gujarat';
$customerCountry = $customerLocation['country'] ?? 'India';

// Seller location is fixed: Delhi, India
$sellerCity    = 'Delhi';
$sellerState   = 'Delhi';
$sellerCountry = 'India';

// Build address strings (for display)
$customerAddress = "{$customerCity}, {$customerState}, {$customerCountry}";
$sellerAddress   = "{$sellerCity}, {$sellerCountry}";

// --- Get Dynamic Coordinates using Nominatim API --- //
function getDynamicCoords($city, $state, $country)
{
    $address = urlencode("$city, $state, $country");
    $url = "https://nominatim.openstreetmap.org/search?q=$address&format=json&limit=1";
    // Nominatim requires a valid User-Agent header.
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: PremiumTrackingExperience/1.0 (your-email@example.com)\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data[0])) {
            return [
                'lat'  => floatval($data[0]['lat']),
                'lng'  => floatval($data[0]['lon']),
                'zoom' => 11 // default zoom level
            ];
        }
    }
    return null;
}

$customerCoords = getDynamicCoords($customerCity, $customerState, $customerCountry);
if (!$customerCoords) {
    $customerCoords = ['lat' => 22.3039, 'lng' => 70.8022, 'zoom' => 12];
}
$sellerCoords = getDynamicCoords($sellerCity, $sellerState, $sellerCountry);
if (!$sellerCoords) {
    $sellerCoords = ['lat' => 28.7041, 'lng' => 77.1025, 'zoom' => 11];
}

// Compute center point for map (simple midpoint)
$centerLat = ($customerCoords['lat'] + $sellerCoords['lat']) / 2;
$centerLng = ($customerCoords['lng'] + $sellerCoords['lng']) / 2;

// --- Get Route from OpenRouteService --- //
$ors_api_key = '5b3ce3597851110001cf6248a1a108a0613c4fee944d5c9868c7580b';  
$orsUrl = 'https://api.openrouteservice.org/v2/directions/driving-car?';
$postData = json_encode([
    "coordinates" => [
        [$sellerCoords['lng'], $sellerCoords['lat']],   // ORS expects [lng, lat]
        [$customerCoords['lng'], $customerCoords['lat']]
    ]
]);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $orsUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $ors_api_key,
    'Content-Type: application/json'
]);
$response = curl_exec($curl);
if (curl_errno($curl)) {
    $ors_error = curl_error($curl);
    // Optionally log the error or provide fallback
}
curl_close($curl);
$ors_response = json_decode($response, true);

// Get total distance (in km) from ORS summary if available.
if (isset($ors_response['features'][0]['properties']['summary']['distance'])) {
    $total_distance = $ors_response['features'][0]['properties']['summary']['distance'] / 1000;
} else {
    // Fallback: straight-line haversine distance
    if (!function_exists('haversineDistance')) {
        function haversineDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371)
        {
            $latFrom = deg2rad($lat1);
            $lonFrom = deg2rad($lon1);
            $latTo   = deg2rad($lat2);
            $lonTo   = deg2rad($lon2);
            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;
            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return $angle * $earthRadius;
        }
    }
    $total_distance = haversineDistance(
        $sellerCoords['lat'],
        $sellerCoords['lng'],
        $customerCoords['lat'],
        $customerCoords['lng']
    );
}

// --- Determine Progress Based on Order Status ---
$statusProgress = [
    'pending'          => 0,
    'confirmed'        => 15,
    'shipped'          => 35,
    'in_transit'       => 65,
    'out_for_delivery' => 85,
    'delivered'        => 100
];
$currentStatus = strtolower($transaction['order_status']);
$progress = $statusProgress[$currentStatus] ?? 0;
$covered_distance = $total_distance * ($progress / 100);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Premium Tracking Experience</title>
    <!-- Google Fonts and Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS for dynamic map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        :root {
            --primary: rgb(255, 255, 255);
            --secondary: #7A6A58;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63);
            color: #fff;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .tracking-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .tracking-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .status-container {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 12px;
        }

        .status-item {
            text-align: center;
            flex: 1;
        }

        .status-item i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .progress-container {
            position: relative;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            margin: 30px 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), #FFA500);
            border-radius: 4px;
            transition: width 0.5s ease;
            width: <?= $progress ?>%;
        }

        /* Dynamic Map Container */
        #gomap {
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            margin: 30px 0;
        }

        .distance-info {
            display: flex;
            justify-content: space-around;
            background: rgba(255, 255, 255, 0.08);
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .distance-item {
            text-align: center;
        }

        .distance-item h3 {
            color: var(--primary);
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .tracking-header h1 {
                font-size: 2rem;
            }

            .status-container {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="tracking-header">
            <h1>Tracking #<?= htmlspecialchars($tracking_number) ?></h1>
            <div class="status-container">
                <div class="status-item">
                    <i class="fas fa-warehouse"></i>
                    <p>Seller Location<br><small><?= htmlspecialchars($sellerAddress) ?></small></p>
                </div>
                <div class="status-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Customer Location<br><small><?= htmlspecialchars($customerAddress) ?></small></p>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
            <div class="distance-info">
                <div class="distance-item">
                    <h3>Total Distance</h3>
                    <p><?= round($total_distance) ?> km</p>
                </div>
                <div class="distance-item">
                    <h3>Covered Distance</h3>
                    <p><?= round($covered_distance) ?> km</p>
                </div>
                <div class="distance-item">
                    <h3>Remaining</h3>
                    <p><?= round($total_distance - $covered_distance) ?> km</p>
                </div>
            </div>
        </div>
        <!-- Dynamic Map Container -->
        <div id="gomap"></div>
        <div class="status-container">
            <div class="status-item">
                <i class="fas fa-box-open"></i>
                <h3>Order Status</h3>
                <p><?= ucwords(str_replace('_', ' ', $transaction['order_status'])) ?></p>
            </div>
            <div class="status-item">
                <i class="fas fa-calendar-alt"></i>
                <h3>Estimated Delivery</h3>
                <p><?= date('M j, Y', strtotime('+' . round($total_distance / 200) . ' days')) ?></p>
            </div>
            <div class="status-item">
                <i class="fas fa-phone-alt"></i>
                <h3>Support</h3>
                <p>+1 (800) 123-4567</p>
            </div>
        </div>
    </div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('gomap').setView([<?= $centerLat ?>, <?= $centerLng ?>], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add seller and customer markers
        L.marker([<?= $sellerCoords['lat'] ?>, <?= $sellerCoords['lng'] ?>])
            .addTo(map).bindPopup('Seller Location');
        L.marker([<?= $customerCoords['lat'] ?>, <?= $customerCoords['lng'] ?>])
            .addTo(map).bindPopup('Customer Location');

        <?php if (isset($ors_response['features'][0]['geometry'])): ?>
            // Add ORS route as GeoJSON layer
            var routeGeoJson = <?= json_encode($ors_response['features'][0]['geometry']) ?>;
            var routeLayer = L.geoJSON(routeGeoJson, {
                style: {
                    color: 'blue',
                    weight: 4,
                    opacity: 0.7
                }
            }).addTo(map);
            map.fitBounds(routeLayer.getBounds());

            // Extract route coordinates (in [lng, lat] order)
            var routeCoords = routeGeoJson.coordinates;

            // Haversine distance (in km) between two lat/lng points
            function haversineDistance(lat1, lon1, lat2, lon2) {
                var R = 6371; // Radius of the Earth in km
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // Interpolate a point along the route given a fraction (0 to 1)
            function interpolatePoint(coords, fraction) {
                var totalDistance = 0;
                var distances = [];
                for (var i = 0; i < coords.length - 1; i++) {
                    var d = haversineDistance(coords[i][1], coords[i][0], coords[i + 1][1], coords[i + 1][0]);
                    distances.push(d);
                    totalDistance += d;
                }
                var target = fraction * totalDistance;
                var cumulative = 0;
                for (var i = 0; i < distances.length; i++) {
                    if (cumulative + distances[i] >= target) {
                        var segFraction = (target - cumulative) / distances[i];
                        var lng1 = coords[i][0],
                            lat1 = coords[i][1];
                        var lng2 = coords[i + 1][0],
                            lat2 = coords[i + 1][1];
                        var interpLat = lat1 + (lat2 - lat1) * segFraction;
                        var interpLng = lng1 + (lng2 - lng1) * segFraction;
                        return [interpLat, interpLng]; // Return as [lat, lng]
                    }
                    cumulative += distances[i];
                }
                // Fallback: return last coordinate
                var last = coords[coords.length - 1];
                return [last[1], last[0]];
            }

            // Animate the truck marker along the route based on progress
            function animateTruckMarker(coords, targetFraction, duration) {
                var startTime = null;
                // Create truck marker at the start of the route (fraction 0)
                var truckMarker = L.marker(interpolatePoint(coords, 0), {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/870/870130.png', // Replace with your truck icon URL
                        iconSize: [32, 32]
                    })
                }).addTo(map).bindPopup('Truck');

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var elapsed = timestamp - startTime;
                    var progressAnim = Math.min(elapsed / duration, 1);
                    var currentFraction = progressAnim * targetFraction;
                    var position = interpolatePoint(coords, currentFraction);
                    truckMarker.setLatLng(position);
                    if (progressAnim < 1) {
                        requestAnimationFrame(step);
                    }
                }
                requestAnimationFrame(step);
            }
            // Animate truck marker to the progress value (progress from PHP: <?= $progress / 100 ?>)
            animateTruckMarker(routeCoords, <?= $progress / 100 ?>, 2000);
        <?php else: ?>
            console.log('No route data available from ORS.');
        <?php endif; ?>
    </script>
</body>

</html>