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
$limit = 30; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

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


