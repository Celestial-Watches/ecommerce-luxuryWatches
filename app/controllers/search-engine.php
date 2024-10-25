<?php

// 1. Initialize variables:
//    - search: Check if there is a search term in the GET request, otherwise set as empty.
//    - limit: Set a constant limit for the number of products per page (30).
//    - page: Check if a page number is in the GET request, default to 1 if not provided.
//    - offset: Calculate the starting point for the SQL query based on page number.

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 30; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// 2. Check if there is a search term:
//     - If search term exists:
//       a. Split the search term into individual words to handle multiple terms.
//       b. Initialize SQL query components:
//          - sql: Start the SQL query with a base SELECT command.
//          - conditions: Create an empty array to hold search conditions.
//          - params: Create an empty array to store parameterized query values.
//       c. Loop through each search term:
//          - Add condition for brand search using LIKE with wildcard search.
//          - Add search term to params with wildcards, allowing partial matches.
//       d. Check if conditions array has any conditions:
//          - If true, add the conditions (for brand search) to sql query using "OR".
//       e. Prepare the SQL statement:
//          - Use conn->prepare() to safely create the SQL statement with placeholders.
//          - Bind parameters with stmt->bind_param() for safe query execution.
//       f. Execute the brand search query:
//          - Run the query using stmt->execute() and get results with get_result().
//       g. If no results are found from the brand search:         
//          - Reset the SQL query and params for a new search focusing on tags. 
//          - Loop through each term again to create search conditions for tags.         
//          - Add the conditions (for tags search) to sql query using "OR".
//          - Prepare the SQL statement and bind parameters for tags. 
//          - Execute the tags query and get results.

if ($search) {

    // Split the search terms into an array of words
    $searchTerms = explode(' ', $search);

    // Prepare the SQL query with placeholders
    $sql = "SELECT * FROM products WHERE ";
    $conditions = [];
    $params = [];

    // Create conditions for brand matches first
    foreach ($searchTerms as $term) {
        // Add conditions for brand
        $conditions[] = "brand LIKE ?";
        $params[] = "%" . $term . "%"; // for brand
    }

    // If there are conditions for brand matches, add them to the query
    if (!empty($conditions)) {
        $sql .= implode(' OR ', $conditions);
    }

    // Prepare the statement for brand search
    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params)); // "s" for each term
    $stmt->bind_param($types, ...$params); // Unpack the parameters

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // If no results found, search by tags if necessary
    if ($result->num_rows == 0) {
        // Reset the SQL query and params array for the tags search
        $sql = "SELECT * FROM products WHERE ";
        $conditions = [];
        $params = []; // Reset params

        // Create conditions for tags
        foreach ($searchTerms as $term) {
            $conditions[] = "tags LIKE ?";
            $params[] = "%" . $term . "%"; // Add search term for tags
        }

        // Build the query for tag search
        $sql .= implode(' OR ', $conditions);

        // Prepare the statement for tag search
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params); // Correctly bind parameters

        // Execute the tag query
        $stmt->execute();
        $result = $stmt->get_result();
    }

} else {
    //     3. If there is no search term:
//    - Select all products using pagination:
//      - Prepare a SQL query to select products with LIMIT and OFFSET for pagination.
//      - Bind limit and offset parameters to the statement.
//      - Execute the query and fetch the results.

    $stmt = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

// 4. Pagination setup:
//    - Get the total count of products by executing a COUNT query.
//    - Calculate totalPages by dividing totalProducts by the limit.
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);
