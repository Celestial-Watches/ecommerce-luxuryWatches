<?php

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
$category = isset($_GET['category']) ? $_GET['category'] : ''; // Assuming you have category filter
$limit = 30; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$sort = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'new_in';

if ($search !== '') { // Check if the search term is not empty
    // Store the search term in the search history
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert search term into the search_history table
    $stmt = $conn->prepare("INSERT INTO search_history (user_id, search_term, created_at) VALUES (?, ?, NOW())");

    // Bind user ID as NULL for guests
    if ($userId === null) {
        $stmt->bind_param("is", $userId, $search); // "i" allows NULL for user_id
    } else {
        $stmt->bind_param("is", $userId, $search);
    }

    $stmt->execute();

    // Prepare the search term for the query
    $searchTerm = '%' . $search . '%';
    
    // Base SQL query (Handle category filter and search term)
    $sql = "SELECT * FROM products WHERE 1";

    // If a category is set, apply the filter to the query
    if ($category) {
        $sql .= " AND product_category = ?";
    }

    // If a search term is set, apply the search filter to the query
    if ($searchTerm) {
        $sql .= " AND (name LIKE ? OR brand LIKE ?)";
    }

    // Add ORDER BY clause based on the selected sort option
    switch ($sort) {
        case 'new_in':
            $sql .= " ORDER BY created_at DESC"; // Assuming there's a created_at column
            break;
        case 'price_low_high':
            // Sort by price in ascending order after sanitizing
            $sql .= " ORDER BY CAST(REPLACE(REPLACE(price, ',', ''), '$', '') AS DECIMAL(10,2)) ASC";  // Ensure price is numeric
            break;
        case 'price_high_low':
            // Sort by price in descending order after sanitizing
            $sql .= " ORDER BY CAST(REPLACE(REPLACE(price, ',', ''), '$', '') AS DECIMAL(10,2)) DESC"; // Ensure price is numeric
            break;
    }

    // Add LIMIT and OFFSET for pagination
    $sql .= " LIMIT ? OFFSET ?";

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters (category, search term, and pagination)
    if ($category && $searchTerm) {
        // Bind category, search term, and pagination parameters
        $stmt->bind_param("ssssii", $category, $searchTerm, $searchTerm, $limit, $offset);
    } elseif ($category) {
        // Bind category and pagination parameters
        $stmt->bind_param("sii", $category, $limit, $offset);
    } elseif ($searchTerm) {
        // Bind only search term and pagination parameters
        $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
    } else {
        // Bind only pagination parameters
        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Display search results message
    if ($result && $result->num_rows > 0) {
        $message = $result->num_rows . " results found for “" . htmlspecialchars($search) . "”";
    } else {
        $message = 'No products found for "' . htmlspecialchars($search) . '"';
        $result = []; // Clear results to prevent displaying products
    }
} else {
    // If no search term, show all products
    $stmt = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Message for the landing page
    $message = '';
}

// Query to get total products for pagination
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

?>