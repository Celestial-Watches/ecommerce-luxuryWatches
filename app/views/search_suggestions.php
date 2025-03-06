<?php
// search_suggestions.php
require_once '../config/conn.php';  

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q !== '') {
    // Prepare a statement using a traditional MySQLi approach
    $stmt = $conn->prepare("SELECT name FROM products WHERE name LIKE ? LIMIT 10");
    $like = '%' . $q . '%';
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Output each result as an HTML suggestion item
    while ($row = $result->fetch_assoc()) {
        echo '<div class="suggestion-item">' . htmlspecialchars($row['name']) . '</div>';
    }
    
    $stmt->close();
}
?>
