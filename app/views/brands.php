<?php
session_start();
define('ALLOW_ACCESS', true);
include '../config/conn.php';
include '../controllers/search-engine.php';

$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM products WHERE brand = ?"); // Adjust the table name as needed
$stmt->bind_param("s", $brand);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- ============= JS =============  -->
    <script src="../..//src/assets/js/navigation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="../..//src/assets/css/deskView.css" loading="lazy" />
    <link rel="stylesheet" href="../..//src/libs/swiper/swiper-bundle.min.css" loading="lazy">
    <link rel="stylesheet" href="../..//src/assets/css/google-header.css" loading="lazy">

    <!-- ============= FONTS =============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<style type="text/css" media="all">
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
        }
    }

    @media (max-width: 992px) {

        .product-grid,
        .single-row {
            grid-template-columns: repeat(3, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {

        .product-grid,
        .single-row {
            grid-template-columns: repeat(2, minmax(250px, 1fr));
        }
    }

    @media (max-width: 580px) {

        .product-grid,
        .single-row {
            grid-template-columns: repeat(1, 1fr);
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

    .product-price {
        font-size: 17px !important;
        font-weight: 400 !important;
        line-height: 30px !important;
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

<body>

    <?php include '../../PHP/components/navbar.php'; ?>

    <div class="product__container">

        <?php
        // Check if there are results for the selected brand
        if ($result && $result->num_rows > 0) {
        ?>

            <div style="font-size: 21px;
                    font-weight: 700;
                    line-height: 25px;
                    text-transform: uppercase;
                    margin-top: 17px;
                    margin-bottom: 10px;
                    text-align: left;
                    font-family: 'Poppins';">
                <?php echo htmlspecialchars($brand); ?>
            </div>

            <span style="text-align: left;
                    font-size: 15px;
                    font-weight: 400;
                    line-height: 21px;
                    padding-bottom: 20px;
                    font-family: 'Poppins';">
                Discover our prestigious collections of luxury watches.</span>

        <?php
        }
        ?>

        <div class="product-grid <?php echo ($result && $result->num_rows <= 4) ? 'single-row' : ''; ?>">
            <?php
            // Display products only if the result set has products
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image = $row['image_url'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $numericPrice = null;

                    // Check for "ON REQUEST"
                    if (stripos($price, 'ON REQUEST') !== false) {
                        $numericPrice = null; // Indicate that the price is not available
                    } else {
                        // Check for "FROM" and extract numeric price
                        if (stripos($price, 'FROM') !== false) {
                            preg_match('/FROM\s*([0-9,]+(?:\.[0-9]{1,2})?)/i', $price, $matches);
                            $numericPrice = isset($matches[1]) ? str_replace(',', '', $matches[1]) : 0; // Remove commas
                        } else {
                            preg_match('/[0-9,]+(?:\.[0-9]{1,2})?/', $price, $matches);
                            $numericPrice = isset($matches[0]) ? str_replace(',', '', $matches[0]) : 0; // Remove commas
                        }
                    }

                    $year = $row['year'];
                    $ref_code = $row['ref_code'];
                    $button_name = $row['button_name'];
                    $icon = $row['icon'];
            ?>
                    <div class="product-item">
                        <div class="product__main">
                            <div class="product__image">
                                <div class="product-image">
                                    <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" class="product-image">
                                </div>
                            </div>
                            <hr>
                            <div class="product-content">
                                <div class="product-title">
                                    <h3 class="product-name">
                                        <a href="#" class="name-link"><?php echo $name; ?></a>
                                    </h3>
                                </div>
                                <div class="product-price">
                                    <span class="price featured-price" data-price-in-usd="<?php echo $numericPrice !== null ? $numericPrice : 'N/A'; ?>">
                                        <?php echo $price; ?>
                                    </span>
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
                                    <button type="submit" class="cta-button">
                                        <i style="padding: 2%;" class="<?php echo $icon; ?>"></i>
                                        <?php echo $button_name; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                // If there are no results for the selected brand
                echo '<p style="display:block; text-align: center;">Coming soon</p>';
            }

            // Close the statement and connection
            if (isset($stmt)) {
                $stmt->close();
            }
            $conn->close();
            ?>
        </div>



        <!-- JS files -->
        <script src="../../src/libs/swiper/swiper-bundle.min.js"></script>
        <script src="../../src/assets/js/product-page.js"></script>
</body>

</html>

<?php
// $stmt->close(); // Close statement
// $conn->close(); // Close connection
?>