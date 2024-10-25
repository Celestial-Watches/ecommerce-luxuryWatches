<?php
require_once '../app/config/conn.php';

// Get current page and search term from the AJAX request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$limit = 5; // Number of products per page
$offset = ($page - 1) * $limit; // Calculate offset

// Get total number of products that match the search term
$totalQuery = "SELECT COUNT(*) as total FROM products WHERE name LIKE ?";
$stmtTotal = $conn->prepare($totalQuery);
$likeTerm = "%" . $searchTerm . "%";
$stmtTotal->bind_param('s', $likeTerm);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit); // Calculate total pages

// Fetch products with pagination and search term
$query = "SELECT id, product_category, name, price, description, brand, tags, stock, image_url 
          FROM products 
          WHERE name LIKE ? 
          ORDER BY id 
          LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

// Build the product table HTML
$productTable = '<table class="table table-striped table-hover">
<thead>
    <tr>
        <th>ID</th>
        <th>Product Category</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Brand</th>
        <th>Tags</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>';

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $productTable .= '<tr>
            <td>' . htmlspecialchars($product['id']) . '</td>
            <td>' . htmlspecialchars($product['product_category']) . '</td>
            <td><img src="' . htmlspecialchars($product['image_url']) . '" class="product-image"></td>
            <td>' . htmlspecialchars($product['name']) . '</td>
            <td style="text-wrap: nowrap;">';

        // Check if price is "On Request", otherwise show the price with the dollar sign
        if ($product['price'] === 'ON REQUEST') {
            $productTable .= htmlspecialchars($product['price']);
        } else {
            $productTable .= '$' . htmlspecialchars($product['price']);
        }

        $productTable .= '</td>
        <td style="padding:0.6rem;">' . htmlspecialchars($product['description']) . '</td>
        <td style="text-wrap: nowrap;">' . htmlspecialchars($product['brand']) . '</td>
        <td>' . htmlspecialchars($product['tags']) . '</td>
        <td>' . htmlspecialchars($product['stock']) . '</td>
        <td style="display: flex; gap:10px">
            
<!-- Edit Button -->
<form action="edit-product.php" method="get" style="display:inline;">
<input type="hidden" name="id" value="' . htmlspecialchars($product['id']) . '">
<button type="submit" class="btn btn-warning btn-sm">
    <i class="fas fa-edit"></i> Edit
</button>
</form>

<!-- Delete Button -->
<form action="delete-product.php" method="get" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">
<input type="hidden" name="id" value="' . htmlspecialchars($product['id']) . '">
<button type="submit" class="btn btn-danger btn-sm">
    <i class="fas fa-trash"></i> Delete
</button>
</form>

        </td>
    </tr>';
    }
} else {
    $productTable .= '<tr><td colspan="9" class="text-center">No products found</td></tr>';
}

$productTable .= '</tbody></table>';


// Build the pagination links
$paginationLinks = '';
if ($totalPages > 1) {
    // Previous button
    if ($page > 1) {
        $paginationLinks .= '<li class="page-item">
                    <a href="#" class="page-link" data-page="' . ($page - 1) . '">Previous</a>
                </li>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        $paginationLinks .= '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                    <a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a>
                </li>';
    }

    // Next button
    if ($page < $totalPages) {
        $paginationLinks .= '<li class="page-item">
                    <a href="#" class="page-link" data-page="' . ($page + 1) . '">Next</a>
                </li>';
    }
}



// Return the product table and pagination links as JSON
echo json_encode([
    'products' => $productTable,  // Product HTML table as a string
    'pagination' => $paginationLinks // Pagination links as a string
]);
