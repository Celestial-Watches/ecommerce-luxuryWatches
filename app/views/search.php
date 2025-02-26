<?php
session_start();
define('ALLOW_ACCESS', true);
include '../config/conn.php';
include '../controllers/search-engine.php';

// Get category and sort parameters from the URL
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'new_in';

$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filterKeys = [
    'bracelet_color',
    'case_material',
    'bracelet_material',
    'case_diameter',
    'bezel_material',
    'movement',
    'year',
    'price',
    'power_reserve',
    'glass',
    'clasp_type',
    'water_resistance',
    'dial_numerals',
    'clasp_material'
];

// Initialize selected filters array
$selectedFilters = [];
foreach ($filterKeys as $key) {
    if (isset($_GET[$key]) && $_GET[$key] !== '') {
        $selectedFilters[$key] = $_GET[$key];
    }
}

// Build base WHERE clauses array
$whereClauses = [];
$params = [];
$types = '';

// Apply filters
foreach ($selectedFilters as $key => $value) {
    if (!empty($value)) {
        $values = is_array($value) ? $value : explode(',', $value);
        foreach ($values as $val) {
            $whereClauses[] = "LOWER(TRIM(pd.$key)) = ?";
            $params[] = strtolower(trim($val));
            $types .= 's';
        }
    }
}

// Include category filter if set
if ($category) {
    $whereClauses[] = "product_category = ?";
    $params[] = $category;
    $types .= 's';
}

// Build the final SQL query
$sql = "SELECT * FROM products p ";
if (!empty($selectedFilters)) {
    $sql .= "JOIN product_details pd ON p.id = pd.product_id ";
}
if (!empty($whereClauses)) {
    $sql .= "WHERE " . implode(" AND ", $whereClauses) . " ";
}

// Add ORDER BY clause
switch ($sort) {
    case 'new_in':
        $sql .= "ORDER BY created_at DESC ";
        break;
    case 'price_low_high':
        $sql .= "ORDER BY CASE WHEN price = 'ON REQUEST' THEN 1 ELSE 0 END, 
                  CAST(REPLACE(REPLACE(price, ',', ''), '$', '') AS DECIMAL(10,2)) ASC ";
        break;
    case 'price_high_low':
        $sql .= "ORDER BY CASE WHEN price = 'ON REQUEST' THEN 0 ELSE 1 END, 
                  CAST(REPLACE(REPLACE(price, ',', ''), '$', '') AS DECIMAL(10,2)) DESC ";
        break;
}

// Add pagination
$sql .= "LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

$stmt->execute();
$sortresult = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- ============= JS =============  -->
    <script src="../../src/assets/js/navigation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script src="assets/js/filterModel.js"></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="../../src/assets/css/deskView.css" loading="lazy" />
    <link rel="stylesheet" href="../../src/libs/swiper/swiper-bundle.min.css" loading="lazy">
    <link rel="stylesheet" href="../../src/assets/css/google-header.css" loading="lazy">


    <!-- ============= FONTS =============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <title>Search Results</title>

    <style type="text/css" media="all">
        /* Container for the result text and filter button */
        .filter-head-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }

        /* Results and Filter button container */
        .top-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Base styling for results count */
        .results-count {
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 21px;
            color: #212121;
            margin-right: 20px;
        }

        .filter-container {
            display: flex;
            gap: 15px;
        }

        .filter-item {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            position: relative;
            cursor: pointer;
            width: auto;
            /* Default width */
        }

        .filter-item::after {
            content: "▼";
            font-size: 10px;
            color: #aaa;
            margin-left: 5px;
        }


        /* Base styling for filter button */
        .filter-button {
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 21px;
            text-transform: uppercase;
            background: #212121;
            border: 2px solid #212121;
            border-radius: 4px;
            width: 135px;
            padding: 13px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            position: relative;
        }

        /* Icon for filter button, positioned inline on the left */
        .filter-button::before {
            content: "";
            background: url(https://www.watchesworld.com/wp-content/themes/ww2/assets/images/shop/filter-btn-white.svg) no-repeat center;
            background-size: contain;
            width: 16px;
            height: 10px;
            display: inline-block;
            margin-right: 8px;
        }

        /* Sort form styling */
        .sort-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Sort label styling */
        .sort-label {
            font-size: 12px;
            font-weight: 600;
            color: #000;
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
            text-wrap: nowrap;
        }

        /* Sort dropdown styling */
        .sort-dropdown {
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
            background-color: #fff;
            color: #333;
            border: none;
            position: relative;
            min-width: 150px;
        }

        .sort-dropdown::after {
            content: "▼";
            position: absolute;
            right: 10px;
            pointer-events: none;
            font-size: 12px;
            color: #333;
        }

        /* Responsive adjustments */

        /* For screen sizes 1280px or less */
        @media (max-width: 1280px) {

            /* Hide the filter container */
            .filter-container {
                display: none;
            }

            /* Remove background and border from filter button */
            .filter-button {
                background: none;
                border: none;
                color: #212121;
                width: auto;
                padding: 0;
            }

            .filter-button::before {
                background: url(https://www.watchesworld.com/wp-content/themes/ww2/assets/images/shop/filter-btn-black.svg) no-repeat center;
            }

            /* Adjust layout */
            .filter-head-container {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
                gap: 10px;
                flex-wrap: wrap;
            }

            /* Align filter button to the left */
            .filter-button {
                order: -1;
            }

            /* Position results-count below filter button with sort-form aligned to the right */
            .top-container {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-direction: row;
                gap: 20px;
            }

            /* Make results-count appear below filter-button */
            .results-count {
                margin-top: 10px;
                order: 1;
            }
        }

        /* For screen sizes 375px or less */
        @media (max-width: 375px) {

            /* Hide sort-label text */
            .sort-label {
                display: none;
            }

            /* Maintain single-line layout */
            .top-container {
                flex-wrap: nowrap;
            }
        }

        .product__container {
            padding: 40px;
            background-color: #FCF8F5 !important;
        }

        .product-grid,
        .single-row {
            display: grid !important;
            grid-template-columns: repeat(5, minmax(250px, 1fr));
            justify-content: center !important;
            gap: 10px !important;
        }

        .product-item {
            background-color: white !important;
            padding: 10px !important;
            height: 100%;
        }

        /* Media Queries */
        @media (max-width: 1360px) {

            .product-grid,
            .single-row {
                grid-template-columns: repeat(4, minmax(250px, 1fr));
                /* 4 items per row */
            }
        }

        @media (max-width: 992px) {

            .product-grid,
            .single-row {
                grid-template-columns: repeat(3, minmax(250px, 1fr));
                /* 3 items per row */
            }
        }

        @media (max-width: 768px) {

            .product-grid,
            .single-row {
                grid-template-columns: repeat(2, minmax(250px, 1fr));
                /* 2 items per row */
            }
        }

        @media (max-width: 580px) {

            .product-grid,
            .single-row {
                grid-template-columns: repeat(1, 1fr);
                /* 1 item per row */
            }
        }


        .single-row .product-item {
            margin: 5px;
            flex: 0 0 auto;
        }

        .product-content {
            margin-top: 20px;
            padding: 5px;
        }

        .product__image {
            text-align: center !important;
            padding-block: 20px !important;
        }

        .product-image {
            width: 100% !important;
            height: 260px !important;
            object-fit: cover;
        }

        .cta-button:hover {
            background-color: #F3F4F4 !important;

        }

        .product-code {
            display: flex !important;
            justify-content: space-between !important;
            padding: 10px !important;
            line-height: 45px !important;
        }

        .product-title {
            display: block !important;
            text-align: left !important;
            width: 100% !important;
        }

        .product-name {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
        }

        .product-date {
            font-size: 12px !important;
            text-transform: uppercase !important;
            color: #BFBFBF !important;
            text-align: left !important;
        }

        .product-ref-code {
            font-size: 12px !important;
            text-transform: uppercase !important;
            color: #BFBFBF !important;
            text-align: right !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .name-link {
            text-decoration: none !important;
            font-size: 11px;
            text-transform: uppercase !important;
            font-family: "Univers LT Std", sans-serif !important;
            height: auto !important;
            max-width: 100% !important;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            color: #000 !important;
            font-weight: 400;
        }

        .product-price,
        .price {
            font-size: 17px !important;
            font-weight: 500 !important;
            font-family: 'Univers LT Std', sans-serif !important;
            color: #000 !important;
            margin-top: 8px !important;
            text-transform: uppercase !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .product-button {
            position: relative !important;
            display: flex !important;
            justify-content: center !important;
            background-color: transparent !important;
            width: 100%;
            text-align: center !important;
        }

        .cta-button {
            font-size: 14px !important;
            height: 44px !important;
            background-color: transparent !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 400 !important;
            width: 95% !important;
            padding: 5px !important;
            cursor: pointer !important;
            transition: all .4s !important;
            border-style: double !important;
            border: 1px solid #BFBFBF !important;
            display: flex;
        }

        .button-link {
            color: black;
        }

        @media (max-width: 580px) {

            .product-item,
            .product-name,
            .product-title {
                text-align: center;
                place-items: center;
            }

            .name-link {
                white-space: normal;
                text-overflow: clip;
                overflow: visible;
            }
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a,
        .pagination strong {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #f0f0f0;
        }

        .pagination strong {
            font-weight: bold;
        }

        .search-results {
            text-align: center;
            margin-bottom: 2%;
            font-size: 24px;
            font-weight: 500;
        }
    </style>

</head>

<body>
    <?php include '../../PHP/components/navbar.php'; ?>

    <div class="product__container">
        <div class="search-results">
            <?php if ($search && $result): ?>
                <?php if ($result->num_rows > 0): ?>
                    <p><?php echo $result->num_rows; ?> results found for “<?php echo htmlspecialchars($search); ?>”</p>
                <?php else: ?>
                    <p>No products found for “<?php echo htmlspecialchars($search); ?>”</p>
                <?php endif; ?>
            <?php elseif (!$search): ?>
                <p>No products found for ""</p>
            <?php endif; ?>
        </div>

        <?php if ($search): ?>
            <div class="filter-head-container">
                <div class="top-container">
                <div class="results-count"><?php echo $result->num_rows; ?> results</div>
                    <button class="filter-button" onclick="openFilterModal()">FILTER</button>
                </div>
                <div class="filter-container">
                    <div class="filter-item" onclick="openFilterModal('gender')">Gender</div>
                    <div class="filter-item" onclick="openFilterModal('bracelet_color')">Bracelet Color</div>
                    <div class="filter-item" onclick="openFilterModal('case_material')">Case Material</div>
                    <div class="filter-item" onclick="openFilterModal('bracelet_material')">Bracelet Material</div>
                    <div class="filter-item" onclick="openFilterModal('case_diameter')">Case Diameter</div>
                    <div class="filter-item" onclick="openFilterModal('bezel_material')">Bezel Material</div>
                    <div class="filter-item" onclick="openFilterModal('movement')">Movement</div>
                </div>

                <!-- Sort form that preserves current filters -->
                <form method="GET" action="" class="sort-form">
                    <?php
                    // Output hidden fields for all current GET parameters except 'sort_by'
                    foreach ($_GET as $key => $value) {
                        if ($key == 'sort_by') {
                            continue;
                        }
                        if (is_array($value)) {
                            foreach ($value as $val) {
                                echo '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($val) . '">';
                            }
                        } else {
                            echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                        }
                    }
                    ?>
                    <label for="sort" class="sort-label">Sort by</label>
                    <select name="sort_by" id="sort" class="sort-dropdown" onchange="this.form.submit()">
                        <option value="new_in" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'new_in') ? 'selected' : ''; ?>>New In</option>
                        <option value="price_low_high" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_low_high') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high_low" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_high_low') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </form>
            </div>


            <div class="product-grid <?php echo ($result && $result->num_rows <= 4) ? 'single-row' : ''; ?>">
                <?php


                // Check if there are results
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $image = $row['image_url'];
                        $name = $row['name'];
                        $price = $row['price'];
                        $numericPrice = str_replace([','], '', $price);
                        $year = $row['year'];
                        $ref_code = $row['ref_code'];
                        $button_name = $row['button_name'];
                        $icon = $row['icon'];
                ?>
                        <div class="product-item">
                            <div class="product__main">
                                <div class="product__image">
                                    <div class="product-image">
                                        <img src="<?php echo $image; ?>" loading="lazy" alt="<?php echo $name; ?>" class="product-image">
                                    </div>
                                </div>
                                <hr>
                                <div class="product-content">
                                    <div class="product-title">
                                        <h3 class="product-name">
                                            <a href="details.php?id=<?php echo $row['id']; ?>" class="name-link"><?php echo $name; ?></a>
                                        </h3>
                                    </div>
                                    <div class="product-price">
                                        <span class="price featured-price" data-price-in-usd="<?php echo $numericPrice; ?>"><?php echo $price; ?></span>
                                    </div>
                                    <div class="product-code">
                                        <div class="product-date">
                                            <p class="product-year"><?php echo $year; ?></p>
                                        </div>
                                        <div class="product-ref-code">
                                            <p class="product-ref">Ref Code: <?php echo $ref_code; ?></p>
                                        </div>
                                    </div>
                                    <div class="product-button">
                                        <button class="cta-button actionBtn" id="cart-btn-details">
                                            <i style="padding: 2%;" class="<?php echo $icon; ?>"></i>
                                            <a class="button-link" href="details.php?id=<?php echo $row['id']; ?>"><?php echo $button_name; ?></a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>No products found based on the search term or sorting criteria.</p>';
                }


                ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">« Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../../PHP/components/footer.php' ?>

    <!-- Include your scripts -->
    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>
    <script src="/src/assets/js/imagePreview.js"></script>
    <?php
    include '../models/filterModule.php';
    echo renderFilterModal($conn);
    ?>
</body>

</html>

<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>