<?php

// Assuming you already have your database connection in $conn

// Fetch all brands from the database
$brandResult = $conn->query("SELECT DISTINCT brand FROM products");
$brands = [];

if ($brandResult) {
    while ($row = $brandResult->fetch_assoc()) {
        $brands[] = $row['brand'];
    }
}

// Function to generate abbreviation
function generateAbbreviation($brand) {
    $words = explode(' ', $brand);
    $abbreviation = '';
    foreach ($words as $word) {
        $abbreviation .= strtoupper(substr($word, 0, 1)); // Take the first letter of each word
    }
    return $abbreviation;
}

// Create a mapping of abbreviations to brand names dynamically
$abbreviationMap = [];
foreach ($brands as $brand) {
    $abbreviation = generateAbbreviation($brand);
    $abbreviationMap[$abbreviation] = $brand;
}

// Search logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 30; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if ($search) {
    $searchTerms = explode(' ', $search);
    $sql = "SELECT * FROM products WHERE ";
    $conditions = [];
    $params = [];

    foreach ($searchTerms as $term) {
        $termUpper = strtoupper($term); // Convert to upper case for matching
        
        // Check if the term matches an abbreviation
        if (array_key_exists($termUpper, $abbreviationMap)) {
            $conditions[] = "brand = ?";
            $params[] = $abbreviationMap[$termUpper]; // Use the full brand name
        } else {
            // Fallback to searching for brands matching the term
            $conditions[] = "brand LIKE ?";
            $params[] = "%" . $term . "%"; // for partial match
        }
    }

    if (!empty($conditions)) {
        $sql .= implode(' OR ', $conditions);
    }

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params)); // "s" for each term
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Query to get total products for pagination
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);
?>
