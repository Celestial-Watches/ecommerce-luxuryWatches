<?php
session_start();
define('ALLOW_ACCESS', true);
include '../../PHP/components/navbar.php';
require_once '../config/conn.php';

$productId = (int) $_GET['id'];
$userId = $_SESSION['user_id'] ?? $_SESSION['guest_db_id'] ?? $_SESSION['guest_id'] ?? 0;

$stmt = $conn->prepare("SELECT id, view FROM product_views WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newViewCount = $row['view'] + 1;

    $updateStmt = $conn->prepare("UPDATE product_views SET view = ? WHERE id = ?");
    $updateStmt->bind_param("ii", $newViewCount, $row['id']);
    $updateStmt->execute();
    $updateStmt->close();

    $productStmt = $conn->prepare("UPDATE products SET views = views + 1 WHERE id = ?");
    $productStmt->bind_param("i", $productId);
    $productStmt->execute();
    $productStmt->close();
} else {
    $insertStmt = $conn->prepare("INSERT INTO product_views (user_id, product_id, view, created_at) VALUES (?, ?, 1, NOW())");
    $insertStmt->bind_param("ii", $userId, $productId);
    $insertStmt->execute();
    $insertStmt->close();
}

$stmt->close();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $image = $row['image_url'];
    $name = $row['name'];
    $price = $row['price'];
    $year = $row['year'];
    $ref_code = $row['ref_code'];
    $button_name = $row['button_name'];
    $icon = $row['icon'];
    $brand = $row['brand'];
    $description = $row['description'];

    // Get product details
    $stmt = $conn->prepare("SELECT * FROM product_details WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_details = $stmt->get_result();
    $row_details = $result_details->fetch_assoc();
    // $material = $row_details['material'];
    // $glass = $row_details['glass'];
    // $dial_numerals = $row_details['dial_numerals'];
    // $movement = $row_details['movement'];
    // $water_resistance = $row_details['water_resistance'];
    // $clasp_type = $row_details['clasp_type'];
    // $power_reserve = $row_details['power_reserve'];
    // $bracelet_color = $row_details['bracelet_color'];
    // $gender = $row_details['gender'];
    // $case_diameter = $row_details['case_diameter'];
    // $case_material = $row_details['case_material'];
    // $clasp_material = $row_details['clasp_material'];
    // $bezel_material = $row_details['bezel_material'];
    // $bracelet_material = $row_details['bracelet_material'];
    // $functions = $row_details['functions'];
    $stmt->close();

    $numericPrice = null;

    if (stripos($price, 'ON REQUEST') !== false) {
        $numericPrice = null; 
    } else {
        if (stripos($price, 'FROM') !== false) {
            preg_match('/FROM\s*([0-9,]+(?:\.[0-9]{1,2})?)/i', $price, $matches);
            $numericPrice = isset($matches[1]) ? str_replace(',', '', $matches[1]) : 0; 
        } else {
            preg_match('/[0-9,]+(?:\.[0-9]{1,2})?/', $price, $matches);
            $numericPrice = isset($matches[0]) ? str_replace(',', '', $matches[0]) : 0; 
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:url" content="<?php echo 'http://localhost:3000/app/views/details.php?id=' . $id; ?>">
    <meta property="og:type" content="product">
    <meta property="og:title" content="<?php echo $name; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:image" content="<?php echo $image; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <title>Watch Product Detail</title>
    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- ============= JS =============  -->
    <script src="/src/assets/js/navigation.js" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css" />
    <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">

    <!-- ============= FONTS=============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        .detailsContainer {
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
            height: auto;
            flex-wrap: wrap;
        }

        .image-cont {
            width: 50%;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
        }

        .slider img {
            max-height: 600px;
            object-fit: contain;
            width: 100%;
            margin: 0 auto;
            position: sticky;
            top: 0;
        }

        .lens {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 2px solid #fff;
            border-radius: 50%;
            cursor: none;
            display: none;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            pointer-events: none;
            background-repeat: no-repeat;
            z-index: 3;
        }

        .lens.active {
            display: block;
        }

        .magnifier {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 5px;
            cursor: pointer;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        .magnifier ion-icon {
            font-size: 18px;
        }

        .content {
            width: 50%;
            padding-left: 20px;
            box-sizing: border-box;
        }

        .brand {
            font-family: 'Baskerville';
            font-size: 40px;
            line-height: 46px;
            text-transform: uppercase;
        }

        .product-title {
            font-family: 'Univers LT Std';
            font-size: 21px;
            line-height: 25px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .reference {
            font-family: 'Univers LT Std';
            font-size: 12px;
            color: #808080;
            margin: 12px 0 15px;
        }

        .preferenceCont {
            display: flex;
            align-items: center;
            width: fit-content;
        }

        .payment-options {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid black;
            width: fit-content;
        }

        /* Action button styling */
        .actionBtn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            transition: opacity 0.3s;
        }

        .actionBtn:hover {
            opacity: 0.8;
        }

        .actionBtn ion-icon {
            font-size: 24px;
            color: #333;
        }

        .actionBtn .count {
            background: #000;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
        }

        /* Cart button in contact section */
        .contact-advisor .actionBtn {
            width: 100%;
            justify-content: center;
            margin-top: 10px;
            padding: 6px;
            border: 1px solid #ddd;
            background: #f8f8f8;
        }

        .payment-icons {
            margin: 40px 0;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .payment-icons img {
            height: 30px;
            width: auto;
            max-width: 50px;
            object-fit: contain;
        }

        .offers {
            font-family: 'Univers-LT-Std-65-Bold';
            font-size: 21px;
            margin: 40px 0 20px;
        }

        .offer-box {
            border-bottom: 1px solid #ddd;
            padding: 15px;
            background-color: #fff;
            margin-top: 10px;
            display: flex;
            gap: 30px;
            /* align-items: center; */
            justify-content: space-around;

        }

        .on-request {
            font-size: 20px;
            font-weight: 700;
            color: #000;
            width: max-content;
            margin-top: 20px;
        }

        .offer-cont {
            background-color: white;
            width: max-content;
        }

        .request-button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        .advisor-button {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
        }

        .advisor-button img {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        .footer-reference {
            font-size: 12px;
            display: flex;
            color: #888;
            text-align: right;
            margin-top: 10px;
            padding: 10px;
            justify-content: space-between;
        }

        .details-section {
            width: 50%;
            margin-top: 40px;
            padding: 0 0 0 20px;
            box-sizing: border-box;
        }

        .details-heading {
            font-family: 'Baskerville';
            font-size: 32px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .faq-item {
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .faq-question {
            padding: 15px 0;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
        }

        .faq-answer {
            padding: 0 0 15px 0;
            line-height: 1.6;
            color: #666;
            display: none;
        }

        .faq-item.active .faq-answer {
            display: block;
        }

        .faq-answer b {
            color: black;
        }

        .share-container {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 4;
        }

        .share-button {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .share-button:hover {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
        }

        .social-links {
            position: absolute;
            top: 50px;
            left: 0;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 10px;
            display: none;
            flex-direction: column;
            gap: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .share-container:hover .social-links {
            display: flex;
        }

        .social-link {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            transition: transform 0.2s;
        }

        .social-link:hover {
            transform: scale(1.1);
        }

        .facebook {
            background: #3b5998;
        }

        .twitter {
            background: #1da1f2;
        }

        .whatsapp {
            background: #25d366;
        }

        .link {
            background: #666;
        }



        /* Responsive Adjustments */
        @media (min-width: 1091px) {
            .offer-cont {
                width: 500px;
                max-height: 930px;
            }
        }

        @media (max-width: 1090px) {
            .offer-box {
                display: block;
            }

            .offer-cont {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .detailsContainer {
                flex-direction: column;
                padding: 15px;
            }

            .details-section {
                padding: 20px !important;
            }

            .image-cont,
            .content {
                width: 100%;
                padding-left: 0;
            }

            .brand {
                font-size: 32px;
                line-height: 1.2;
                margin-top: 20px;
            }

            .product-title {
                font-size: 18px;
            }

            .payment-icons img {
                height: 25px;
            }

            .footer-reference {
                text-align: center;
            }

            .details-section {
                width: 100%;
                padding-right: 0;
            }

            .details-heading {
                font-size: 28px;
            }

            .social-links {
                flex-direction: row;
                top: 0;
                left: 50px;
            }
        }

        @media (max-width: 480px) {
            .brand {
                font-size: 28px;
            }

            .product-title {
                font-size: 16px;
            }

            .payment-icons {
                gap: 10px;
            }
        }

        #zoom-image {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            image-rendering: high-quality;
            image-rendering: optimizeQuality;
        }

        .reminder-btn {
            background-color: white;
            color: red;
        }
    </style>
</head>

<body>

    <div class="detailsContainer">
        <div class="image-cont" style="background-color: white; position: relative;">
            <div class="share-container">
                <button class="share-button"><i class="fas fa-share-alt"></i></button>
                <div class="social-links">
                    <a href="#" target="_blank" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" class="social-link twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank" class="social-link whatsapp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-link link"><i class="fas fa-link"></i></a>
                </div>
            </div>
            <div class="slider">
                <img class="watch1 preview-image" src="<?php echo $image; ?>" alt="<?php echo $name; ?>" id="zoom-image" data-preview="<?php echo $image; ?>">
            </div>
            <div class="magnifier" id="magnifier">
                <ion-icon name="search-outline"></ion-icon>
            </div>
            <div class="lens" id="lens"></div>
        </div>

        <div class="content">
            <div class="brand"><?php echo $brand; ?></div>
            <div class="product-title"><?php echo $name; ?></div>
            <div class="reference">REFERENCE: <?php echo $ref_code; ?></div>

            <div class="preferenceCont">
                <div class="payment-options">
                    <span>Payment options</span>
                </div>
                <button class="actionBtn" id="wish-btn-details" title="Add to Wishlist">
                    <ion-icon name="heart-outline"></ion-icon>
                </button>
            </div>
            <div class="payment-icons">
                <span>Payment accepted</span>
                <img src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/visa.png?v=1" alt="Visa">
                <img src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/master.svg" alt="Mastercard">
                <img src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/amex.svg" alt="American Express">
                <img src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/footer/paypal.svg" alt="Paypal">
            </div>

            <div class="offers">OFFERS AVAILABLE</div>
            <div class="offer-cont">
                <div class="offer-box">
                    <div class="on-request">
                        <span class="price featured-price" data-price-in-usd="<?php echo $numericPrice !== null ? $numericPrice : 'N/A'; ?>">
                            <?php echo $price; ?>
                        </span>
                        <p style="font-family: 'Univers-Light-Normal'; text-transform: none; font-style: normal; font-weight: 500; font-size: 15px; line-height: 21px; color: #808080;">
                            Available on request only
                        </p>
                    </div>
                    <div class="contact-advisor" style="padding:10px;">
                        <button class="request-button">SEND REQUEST</button>
                        <div class="advisor-button">
                            <img src="assets/image/contact-mail.png" alt="Contact">
                            Contact a personal advisor
                        </div>
                        <?php if ($isLoggedIn): ?>
                            <button class="actionBtn" id="cart-btn-details" title="Add to Cart">
                                <span>Add to Cart</span><ion-icon name="bag-handle-outline"></ion-icon>
                            </button>
                        <?php else: ?>
                            <p class="request-button reminder-btn" onclick="window.location.href='../controllers/login.php'">Please login to add items to cart</p>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="footer-reference">
                    <div class="fr"><?php echo $year; ?></div>
                    <div class="fr">RÉFÉRENCE: <?php echo $ref_code; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="details-section">
        <h2 class="details-heading">Details about the PRODUCT</h2>

        <div class="faq-item">
            <div class="faq-question">
                Product Details
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p> <b>Brand: </b><?php echo $brand; ?></p>
                <p><b>Model: </b><?php echo $name; ?></p>
                <p><b>Reference: </b><?php //echo $reference; 
                                        ?></p>
                <p><b>Glass: </b><?php //echo $glass; 
                                    ?></p>
                <p><b>Dial Numerals: </b><?php //echo $dial_numerals; 
                                            ?></p>
                <p><b>Movement: </b><?php //echo $movement; 
                                    ?></p>
                <p><b>Water Resistance: </b><?php //echo $water_resistance; 
                                            ?> </p>
                <p><b>Clasp Type: </b><?php //echo $clasp_type; 
                                        ?></p>
                <p><b>Power Reserve: </b><?php //echo $power_reserve; 
                                            ?></p>
                <p><b>Bracelet Color: </b><?php //echo $bracelet_color; 
                                            ?></p>
                <p><b>Gender: </b><?php //echo $gender; 
                                    ?></p>
                <p><b>Case Diameter: </b><?php //echo $case_diameter; 
                                            ?></p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                Material
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p><?php echo $name; ?></p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                Functions
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p><?php echo $year; ?></p>
            </div>
        </div>
    </div>

    <?php include '../../PHP/components/footer.php' ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.faq-question').forEach(item => {
                item.addEventListener('click', () => {
                    const parent = item.parentElement;
                    parent.classList.toggle('active');
                    item.querySelector('span').textContent = parent.classList.contains('active') ? '−' : '+';
                });
            });
        });

        // Magnifier

        document.addEventListener("DOMContentLoaded", function() {
            const magnifierBtn = document.getElementById("magnifier");
            const imageContainer = document.querySelector(".image-cont");
            const zoomLens = document.getElementById("lens");
            const zoomResult = document.getElementById("lens");
            let zoomActive = false;

            magnifierBtn.addEventListener("click", function() {
                zoomActive = !zoomActive;
                if (!zoomActive) {
                    zoomLens.style.display = "none";
                    zoomResult.style.display = "none";
                }
            });

            imageContainer.addEventListener("mousemove", function(e) {
                if (!zoomActive) return;
                const image = e.target;
                const rect = image.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                if (x >= 0 && y >= 0 && x <= rect.width && y <= rect.height) {
                    zoomLens.style.display = "block";
                    zoomResult.style.display = "block";

                    const scale = 2; // Adjust for zoom level
                    zoomLens.style.left = `${x - zoomLens.offsetWidth / 2}px`;
                    zoomLens.style.top = `${y - zoomLens.offsetHeight / 2}px`;

                    zoomResult.style.backgroundImage = `url('${image.src}')`;
                    zoomResult.style.backgroundSize = `${image.width * scale}px ${image.height * scale}px`;
                    zoomResult.style.backgroundPosition = `-${x * scale - zoomResult.offsetWidth / 2}px -${y * scale - zoomResult.offsetHeight / 2}px`;
                }
            });

            imageContainer.addEventListener("mouseleave", function() {
                if (!zoomActive) return;
                zoomLens.style.display = "none";
                zoomResult.style.display = "none";
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get current URL and product title without encoding
            const currentUrl = window.location.href;
            const productTitle = '<?php echo $name; ?>';
            const productId = '<?php echo $id; ?>';

            // Create clean URL for sharing (replace with your production path)
            const cleanUrl = currentUrl.startsWith('http://localhost') ?
                `http://localhost:3000/app/views/details.php?id=${productId}` // Production format
                :
                currentUrl;

            // URL encode components separately
            const encodedUrl = encodeURIComponent(cleanUrl);
            const encodedTitle = encodeURIComponent(productTitle);

            // Social sharing URLs with proper formatting
            // Facebook
            document.querySelector('.facebook').href =
                `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;

            // Twitter (with hashtags and via account)
            document.querySelector('.twitter').href =
                `https://twitter.com/intent/tweet?text=${encodedTitle}&url=${encodedUrl}&hashtags=LuxuryWatches&via=CelestialWatches`;

            // WhatsApp (with proper newline formatting)
            document.querySelector('.whatsapp').href =
                `https://wa.me/?text=${encodedTitle}%0A%0A${encodedUrl}`;

            // Copy link functionality
            document.querySelector('.link').addEventListener('click', function(e) {
                e.preventDefault();
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = cleanUrl;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Link copied to clipboard:\n' + cleanUrl);
            });

            // Mobile touch support
            let isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            if (isMobile) {
                const shareContainer = document.querySelector('.share-container');
                let isOpen = false;

                shareContainer.addEventListener('click', function(e) {
                    if (!isOpen) {
                        this.classList.add('active');
                        this.querySelector('.social-links').style.display = 'flex';
                        isOpen = true;
                    } else {
                        this.classList.remove('active');
                        this.querySelector('.social-links').style.display = 'none';
                        isOpen = false;
                    }
                    e.stopPropagation();
                });

                // Close when clicking outside
                document.addEventListener('click', function() {
                    if (isOpen) {
                        shareContainer.classList.remove('active');
                        shareContainer.querySelector('.social-links').style.display = 'none';
                        isOpen = false;
                    }
                });
            }
        });
    </script>

    <script>
        var isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const product = {
                id: <?= $id ?>,
                image: '<?= $image ?>',
                name: '<?= $name ?>',
                brand: '<?= $brand ?>',
                numericPrice: <?= ($numericPrice !== null) ? $numericPrice : 'null' ?>,
                ref_code: '<?= $ref_code ?>',
                isOnRequest: <?= ($numericPrice === null) ? 'true' : 'false' ?>
            };


            document.getElementById('wish-btn-details').addEventListener('click', () => {
                addToWishlist(product);
                renderDrawerContent(WISHLIST_KEY, wishlistDrawer);
            });

            document.getElementById('cart-btn-details').addEventListener('click', () => {
                addToCart(product);
                renderDrawerContent(CART_KEY, cartDrawer);
            });
        });
    </script>



    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>
</body>

</html>