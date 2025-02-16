<?php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';

if (
    !isset($_SESSION['user']) ||
    !isset($_SESSION['admin']) ||
    !isset($_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true ||
    $_SESSION['admin'] !== true
) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

require_once '../../app/config/conn.php';

$categories = [];
$catResult = $conn->query("SELECT product_category FROM products");
if ($catResult && $catResult->num_rows > 0) {
    while ($row = $catResult->fetch_assoc()) {
        $words = explode(',', $row['product_category']);
        foreach ($words as $word) {
            $word = trim($word);
            if (!empty($word)) {
                $categories[] = $word;
            }
        }
    }
}
$categories = array_unique($categories);
sort($categories);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$limit = 10;
$offset = ($page - 1) * $limit;

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

if (!empty($selectedCategory)) {
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE product_category LIKE CONCAT('%', ?, '%')");
    $countStmt->bind_param("s", $selectedCategory);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $countRow = $countResult->fetch_assoc();
    $total = $countRow['total'];
    $countStmt->close();
} else {
    $countQuery = $conn->query("SELECT COUNT(*) as total FROM products");
    $countRow = $countQuery->fetch_assoc();
    $total = $countRow['total'];
}
$totalPages = ceil($total / $limit);

if (!empty($selectedCategory)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category LIKE CONCAT('%', ?, '%') LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $selectedCategory, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Watch Categories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <style>
        .container {
            color: #000;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter {
            text-align: left;
            margin-bottom: 20px;
        }
        .filter form {
            display: inline-block;
        }
        .filter select {
            padding: 8px 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #000;
            width: 150px;
        }
        .custom-table {
            display: grid;
            gap: 10px;
        }
        .custom-table-header,
        .custom-table-row {
            display: grid;
            grid-template-columns: 120px repeat(7, 1fr);
            align-items: center;
            padding: 12px;
        }
        .custom-table-header {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .custom-table-row {
            border-bottom: 1px solid #ddd;
        }
        .custom-table-row:hover {
            background-color: #f9f9f9;
        }
        .custom-table-row > div {
            padding: 4px 8px;
        }
        .custom-table-row img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
            display: block;
            margin: 0 auto;
        }
        /* Responsive adjustments */
        @media screen and (max-width: 600px) {
            .custom-table-header,
            .custom-table-row {
                grid-template-columns: 1fr;
            }
            .custom-table-header {
                display: none;
            }
            .custom-table-row {
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 10px;
                margin-bottom: 10px;
            }
            .custom-table-row > div {
                margin-bottom: 5px;
            }
            .custom-table-row > div:last-child {
                margin-bottom: 0;
            }
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 12px;
            margin: 0 4px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #000;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #000;
            color: #fff;
        }
        .pagination a:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Watch Categories</h1>
        <div class="filter">
            <form method="GET" action="">
                <select name="category" id="category" onchange="this.form.submit();">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php if ($selectedCategory == $cat) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="custom-table">
            <!-- Header row with an extra "Action" column -->
            <div class="custom-table-header">
                <div>Image</div>
                <div>Name</div>
                <div>Price</div>
                <div>Year</div>
                <div>Reference</div>
                <div>Brand</div>
                <div>Category</div>
                <div>Action</div>
            </div>

            <!-- Data rows with Edit button -->
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($watch = $result->fetch_assoc()): ?>
                    <div class="custom-table-row">
                        <div>
                            <?php if (!empty($watch['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($watch['image_url']); ?>" alt="<?php echo htmlspecialchars($watch['name']); ?>">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                        <div><?php echo htmlspecialchars($watch['name']); ?></div>
                        <div><?php echo htmlspecialchars($watch['price']); ?></div>
                        <div><?php echo htmlspecialchars($watch['year']); ?></div>
                        <div><?php echo htmlspecialchars($watch['ref_code']); ?></div>
                        <div><?php echo htmlspecialchars($watch['brand']); ?></div>
                        <div><?php echo htmlspecialchars($watch['product_category']); ?></div>
                        <div>
                            <a href="edit-product.php?id=<?php echo $watch['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="custom-table-row">
                    <div style="text-align: center; grid-column: span 8;">No watches found.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination Controls -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?><?php echo (!empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : ''); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="<?php echo ($i == $page ? 'active' : ''); ?>" href="?page=<?php echo $i; ?><?php echo (!empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : ''); ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?><?php echo (!empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : ''); ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <script type="text/javascript" src="../../src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="../../src/assets/js/navigation.js" async></script>
    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>
