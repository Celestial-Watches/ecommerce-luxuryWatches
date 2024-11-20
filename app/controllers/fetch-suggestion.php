<?php
require '../config/conn.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$suggestions = [];

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);


    // Fetching product names from the 'name' column
    $stmt = $conn->prepare("
    SELECT name 
    FROM products 
    WHERE brand LIKE ? 
    OR description LIKE ? 
    OR tags LIKE ? 
    OR product_category LIKE ? 
    LIMIT 10
");

    $param = "%" . $search . "%";
    $stmt->bind_param("ssss", $param, $param, $param, $param);

    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($suggestions);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(["error" => "No search term provided."]);
}
