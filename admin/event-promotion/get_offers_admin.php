<?php
// /app/models/get_offers.php
header('Content-Type: application/json');
require '../../app/config/conn.php';

$sql = "SELECT * FROM discount_name ORDER BY id DESC";
$result = $conn->query($sql);

if ($result) {
    $offers = [];
    while ($row = $result->fetch_assoc()) {
        $offers[] = $row;
    }
    echo json_encode($offers);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>
