<?php
require_once __DIR__ . '/../config/conn.php';


$userId = $_SESSION['user_id'] ?? null;
$cacheFile = __DIR__ . '/../cache/recommended_products_' . ($userId ? $userId : 'guest') . '.json';
$cacheTime = 86400;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    $recommendedProducts = json_decode(file_get_contents($cacheFile), true);
} else {
    $recommendedProducts = [];

    if ($userId) {
        // Fetch products based on recently viewed items
        $viewStmt = $conn->prepare("SELECT DISTINCT product_id FROM product_views WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $viewStmt->bind_param('i', $userId);
        $viewStmt->execute();
        $viewedProducts = $viewStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $viewStmt->close();

        if (!empty($viewedProducts)) {
            $productIds = implode(',', array_column($viewedProducts, 'product_id'));
            $recommendedProducts = array_merge($recommendedProducts, getProductsByIds($productIds, $conn));
        }

        // Fetch additional recommendations based on search history if needed
        if (count($recommendedProducts) < 9) {
            $searchStmt = $conn->prepare("SELECT search_term FROM search_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $searchStmt->bind_param('i', $userId);
            $searchStmt->execute();
            $searchTerms = $searchStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $searchStmt->close();

            $searchQuery = implode("','", array_column($searchTerms, 'search_term'));
            if (!empty($searchQuery)) {
                $recommendedProducts = array_merge($recommendedProducts, getProductsBySearch($searchQuery, $conn));
            }
        }
    }

    // Fetch random products if recommendations are insufficient
    if (count($recommendedProducts) < 9) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE stock > 0 ORDER BY RAND() LIMIT ?");
        $limit = 9 - count($recommendedProducts);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $recommendedProducts[] = $row;
        }
        $stmt->close();
    }

    // Save to cache
    file_put_contents($cacheFile, json_encode($recommendedProducts));
}

// Function to get products by search terms
function getProductsBySearch($searchTerms, $conn)
{
    $searchTermsArray = explode("','", $searchTerms);
    if (empty($searchTermsArray)) {
        return []; // Return an empty array if no search terms are provided
    }

    $placeholders = implode(',', array_fill(0, count($searchTermsArray), '?'));
    $query = "SELECT * FROM products WHERE name IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($searchTermsArray)), ...$searchTermsArray);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: []; // Return empty array on failure
}

function getProductsByIds($productIds, $conn)
{
    if (empty($productIds)) {
        return []; // Return an empty array if no product IDs are provided
    }

    $query = "SELECT * FROM products WHERE id IN ($productIds)";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: []; // Return empty array on failure
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Products</title>
    <script src="../../src/assets/js/scroll-animation.js"></script>
    <style>
        .featured-main {
            background-color: #f9f9f9;
        }

        .featured-products {
            max-width: 1200px;
            margin: auto;
            text-align: center;
            padding: 20px;
            width: 100%;
        }

        .featured-title {
            margin: 20px 0;
            font-family: 'Oranienbaum', serif;
            font-weight: 700;
            font-size: 18px;
            line-height: 34px;
            color: #000000;
            font-weight: 500;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .slider {
            display: flex;
            overflow: hidden;
            position: relative;
            margin: 20px auto;
        }

        .slider-container {
            position: relative;
            overflow: hidden;
        }

        .product-grid {
            display: flex;
            transition: transform 0.3s ease-in-out;
            justify-content: flex-start;
            width: 100%;
            will-change: transform;
        }

        .card {
            width: 30%;
            background-color: #e4dfdf;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 0 5px;
            flex: 0 0 32.32%;
            transition: transform 0.3s ease-in-out, opacity 0.5s ease-in-out;
            text-align: center;
            background-image: linear-gradient(300deg,
                    rgba(255, 255, 255, 0) 30%,
                    rgba(255, 255, 255, 0.8),
                    rgba(255, 255, 255, 0) 70%);
        }

        .card.reduce-opacity {
            opacity: 0.5;
        }

        .card.scale-down {
            transform: scale(0.95);
        }

        .card .featured-card {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card .featured-text,
        .card .featured-price {
            text-align: left;
            font-size: 12px !important;
            text-transform: uppercase !important;
            font-family: "Univers LT Std", sans-serif !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card .featured-price {
            font-size: 17px !important;
            font-weight: 400 !important;
            line-height: 25px;
            color: #000;
            margin-top: 8px;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .buy-button {
            width: 100%;
            background-color: #fff !important;
            border: 1px solid #E5E5E5;
            color: #000 !important;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            font-family: 'Univers LT Std', sans-serif;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.3s ease;
            height: 44px;
            margin-top: 20px !important;
        }

        .buy-button:hover {
            background-color: #F3F4F4 !important;
            border-color: #F3F4F4 !important;
            color: #000 !important;
        }

        hr {
            width: 100%;
            margin-bottom: 2px;
        }

        .slider-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1;
            border-radius: 5px;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        /* Responsive styling */
        @media (max-width: 1024px) {
            .card {
                width: 28%;
            }
        }

        @media (max-width: 768px) {
            .card {
                width: 45%;
                flex: 0 0 50%;
            }
        }

        @media (max-width: 480px) {

            /* For mobile, show 1 card */
            .card {
                width: 90%;
                flex: 0 0 100%;
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <?php if (!empty($recommendedProducts)): ?>
        <main class="featured-main animate-on-scroll">
            <section class="featured-products animate-on-scroll">
                <h2 class="featured-title animate-on-scroll">Luxury Timepieces: Handpicked Just for You</h2>
                <div class="slider">
                    <button class="slider-button prev animate-on-scroll" aria-label="Previous Slide" onclick="moveSlide(-1)">&#10094;</button>
                    <div class="slider-container animate-on-scroll">
                        <div class="product-grid">
                            <?php foreach ($recommendedProducts as $product): ?>
                                <div class="card">
                                    <img loading="lazy" class="featured-card" src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <hr>
                                    <h3 class="featured-text"><?= htmlspecialchars($product['name']) ?></h3>

                                    <?php
                                    $rawPrice = $product['price'];

                                    // Initialize display price variable for this specific product
                                    $displayPrice = '';
                                    $onRequestPrice = ''; // New variable for ON REQUEST price

                                    // Check if the price is "ON REQUEST"
                                    if (stripos($rawPrice, 'ON REQUEST') !== false) {
                                        // Set display price for "ON REQUEST"
                                        $onRequestPrice = 'ON REQUEST'; 
                                    } else {                                        
                                        // Extract the currency symbol
                                        $currencySymbol = preg_replace('/[0-9.,\s]+/', '', $rawPrice); // Extract currency symbol
                                        $currencySymbol = trim($currencySymbol); // Trim any extra whitespace
                                        // Extract numeric value and ensure proper formatting
                                        $priceValue = (float)preg_replace('/[^0-9.]/', '', $rawPrice); // Extract numeric value

                                        // Check for "FROM" in the raw price
                                        if (stripos($rawPrice, 'FROM') !== false) {
                                            // Format price for FROM case
                                            $displayPrice = "FROM $currencySymbol" . number_format($priceValue, 2);
                                        } else {
                                            // Format price for normal case
                                            $displayPrice = "$currencySymbol" . number_format($priceValue, 2);
                                        }
                                    }
                                    ?>

                                    <?php if (!empty($onRequestPrice)): ?>
                                        <p class="featured-price"><?= $onRequestPrice ?></p> 
                                    <?php else: ?>
                                        <p class="featured-price" data-price-in-usd="<?= htmlspecialchars($priceValue) ?>"><?= $displayPrice ?></p> 
                                    <?php endif; ?>
                                    <button class="buy-button">View Details</button>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="slider-button next animate-on-scroll" aria-label="Next Slide" onclick="moveSlide(1)">&#10095;</button>
                </div>
            </section>
        </main>
        <hr style="border-color: #ffffff;">
    <?php endif; ?>



    <script>
        // Function to initialize sliders
        function initializeSlider(sliderContainer) {
            let currentIndex = 0;
            const cards = sliderContainer.querySelectorAll('.card');
            let slidesToShow = 3; // Default for large screens

            // Function to handle mouse enter
            function handleMouseEnter() {
                cards.forEach((card) => {
                    if (card !== this) {
                        card.classList.add('scale-down'); // Add scale-down class to other cards
                        card.classList.add('reduce-opacity'); // Add reduce-opacity class to other cards
                    }
                });
            }

            // Function to handle mouse leave
            function handleMouseLeave() {
                cards.forEach((card) => {
                    card.classList.remove('scale-down'); // Remove scale-down class
                    card.classList.remove('reduce-opacity'); // Remove reduce-opacity class
                });
            }

            // Attach event listeners to each card
            cards.forEach((card) => {
                card.addEventListener('mouseenter', handleMouseEnter);
                card.addEventListener('mouseleave', handleMouseLeave);
            });

            // Adjust number of slides based on window width
            function updateSlidesToShow() {
                if (window.innerWidth <= 480) {
                    slidesToShow = 1; // For mobile
                } else if (window.innerWidth < 768) {
                    slidesToShow = 2; // For tablets
                } else if (window.innerWidth <= 1024) {
                    slidesToShow = 3; // For smaller laptops
                } else {
                    slidesToShow = 3; // For larger screens
                }
                showSlides();
            }

            // Move the slides
            function moveSlide(step) {
                currentIndex += step;

                // Wrapping logic
                if (currentIndex < 0) {
                    currentIndex = cards.length - slidesToShow; // Jump from the first slide to the last set of slides
                } else if (currentIndex > cards.length - slidesToShow) {
                    currentIndex = 0; // Jump from the last set of slides to the first slide
                }

                showSlides();
            }

            // Display the slides by translating the grid
            function showSlides() {
                const grid = sliderContainer.querySelector('.product-grid');
                const cardWidth = cards[0].offsetWidth; // Get the width of the first card
                const totalWidth = (cardWidth + 9.5) * currentIndex; // 9px gap between cards
                grid.style.transform = `translateX(-${totalWidth}px)`; // Translate the grid
            }

            // Expose moveSlide function to the global scope for button clicks
            sliderContainer.querySelector('.prev').onclick = () => moveSlide(-1);
            sliderContainer.querySelector('.next').onclick = () => moveSlide(1);

            // Initial setup
            updateSlidesToShow();
            window.addEventListener('resize', updateSlidesToShow);
        }

        // Initialize sliders
        document.querySelectorAll('.slider').forEach(initializeSlider);
    </script>

    <script src="/src/libs/swiper/swiper-bundle.min.js"></script>
    <script src="/src/assets/js/index.js"></script>
    <script src="/src/assets/js/currency-language.js"></script>
    <script src="/src/assets/js/cookie-monitor.js"></script>

</body>

</html>