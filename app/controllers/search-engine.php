<?php

// Fetch distinct brands from the products table
$brandResult = $conn->query("SELECT DISTINCT brand FROM products");
$brands = [];

if ($brandResult) {
    while ($row = $brandResult->fetch_assoc()) {
        $brands[] = $row['brand'];
    }
}

// Function to generate abbreviation for each brand
function generateAbbreviation($brand)
{
    return implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), explode(' ', $brand)));
}

// Create a map of brand abbreviations
$abbreviationMap = array_map('generateAbbreviation', $brands);

// Sanitize search term to handle special characters like periods
function sanitizeSearchTerm($term)
{
    return str_replace('.', '%', $term); 
}

// Helper function to build WHERE clause dynamically
function buildWhereClause($searchTerm, $category) {
    $whereClause = "WHERE 1";

    if ($category) {
        $whereClause .= " AND product_category = ?";
    }

    if ($searchTerm) {
        $whereClause .= " AND (name LIKE ? OR brand LIKE ? OR year LIKE ? OR description LIKE ? OR tags LIKE ?)";
    }

    return $whereClause;
}

// Function to get SQL ORDER BY clause based on sort option
function getSortOrder($sort) {
    switch ($sort) {
        case 'new_in':
            return "ORDER BY created_at DESC";
        case 'price_low_high':
            return "ORDER BY CASE WHEN price = 'ON REQUEST' THEN 1 ELSE 0 END, 
                          CAST(REPLACE(REPLACE(price, ',', ''), '$', '') AS DECIMAL(10,2)) ASC";
        case 'price_high_low':
            return "ORDER BY CASE WHEN price = 'ON REQUEST' THEN 0 ELSE 1 END, 
                          CAST(REPLACE(REPLACE(price, ',', ''), '$', '') AS DECIMAL(10,2)) DESC";
        default:
            return "ORDER BY created_at DESC"; 
    }
}

// Search logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$limit = 20; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$sort = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'new_in';

if ($search !== '') {
    // Store the search term in the search history table
    if (!empty($search)) {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $stmt = $conn->prepare("INSERT INTO search_history (user_id, search_term, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $userId, $search);
        $stmt->execute();
    }

    $searchTerm = '%' . sanitizeSearchTerm($search) . '%';
    $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ? OR brand LIKE ? OR year LIKE ? OR tags LIKE ?";
    $sql .= " " . getSortOrder($sort);
    $sql .= " LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    $params = [];
    $types = 'sssss'; 

    // Add search term bindings
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;

    // Add category if present
    if ($category) {
        $params[] = $category;
        $types .= 's'; 
    }

    // Add pagination parameters
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii'; 

    // Bind parameters to the prepared statement
    $stmt->bind_param($types, ...$params);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Message indicating the number of results found
    $message = ($result && $result->num_rows > 0) ? 
        $result->num_rows . " results found for “" . htmlspecialchars($search) . "”" : 
        'No products found for "' . htmlspecialchars($search) . '"';
} else {
    // If no search term is provided, fetch all products
    $stmt = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = '';
}

// Query to get the total number of products for pagination purposes
$stmt = $conn->prepare("SELECT COUNT(1) AS total FROM products");
$stmt->execute();
$totalResult = $stmt->get_result();
$totalProducts = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

?>
