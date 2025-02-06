<?php if (!defined('ALLOW_ACCESS')) {
    header("Location: ../../index.php");
    exit();
}

$_SESSION['cart'] = $_SESSION['cart'] ?? [];
$_SESSION['wishlist'] = $_SESSION['wishlist'] ?? [];
$isLoggedIn = isset($_SESSION['user_id']);
?>

<script>
    var userId = <?php echo $isLoggedIn ? json_encode($_SESSION['user_id']) : 'null'; ?>;
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <script src="../../src/assets/js/scroll-animation.js" async></script>
    <style>
        #suggestions {
            overflow-y: auto;
            max-height: 200px;
        }

        #suggestions::-webkit-scrollbar {
            width: 8px;
            background: transparent;
        }

        #suggestions::-webkit-scrollbar-track {
            background: transparent;
        }

        #suggestions::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        #suggestions::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        #suggestions {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
        }

        #suggestions:hover {
            scrollbar-color: rgba(0, 0, 0, 0.5) transparent;
        }

        .suggestion-item.selected {
            background-color: rgba(0, 0, 0, 0.1);
            color: #000;
        }

        .suggestions-container {
            border: 1px solid #ccc;
            background: white;
            max-height: 300px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            width: 100%;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: none;
        }

        .suggestion-item {
            padding: 10px;
            cursor: pointer;
            transition: background 0.2s;
            text-align: justify;
            font-family: 'Helvetica';
            font-size: 11px;
            /* white-space: nowrap; */
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        @media (max-width: 600px) {
            #suggestions {
                max-width: 320px;
            }
        }

        .no-product {
            color: #000;
            padding: 10px;
            text-align: center;
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 4px;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translate(-50%, -100%);
            }

            to {
                transform: translate(-50%, 0);
            }
        }

        .notification.error {
            background: #c44;
        }

        /* Cart & Wishlist Drawers */
        .cart-drawer {
            width: 420px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: -8px 0 30px rgba(0, 0, 0, 0.15);
            transition: right 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .cart-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
            color: white;
            margin-bottom: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-title {
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cart-title ion-icon {
            font-size: 1.5em;
            color: #fff;
        }

        .cart-close-btn {
            color: #fff;
            transition: transform 0.3s ease;
            padding: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .cart-close-btn:hover {
            transform: rotate(90deg);
            background: rgba(255, 255, 255, 0.2);
        }

        .drawer-item {
            padding: 20px 30px;
            transition: all 0.3s ease;
            background: white;
            margin: 10px 15px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        .drawer-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .drawer-item img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            border: 2px solid #f1f1f1;
            padding: 5px;
            background: white;
        }

        .item-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .item-info p {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 4px;
        }

        .price {
            font-size: 1.1rem;
            color: rgb(0, 0, 0);
            font-weight: 700;
            margin: 10px 0;
        }

        .remove-btn {
            color: rgb(255, 255, 255);
            transition: all 0.3s ease;
            font-size: 1.8rem;
            padding: 0 8px;
        }

        .remove-btn:hover {
            color: rgb(0, 0, 0);
            transform: scale(1.1);
        }

        .quantity-controls {
            margin: 15px 0;
            gap: 12px;
        }

        .qty-btn {
            background: rgb(255, 255, 255) !important;
            color: black !important;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            transition: all 0.3s ease-in;
        }

        .qty-btn:hover {
            background: rgb(0, 0, 0) !important;
            color: white !important;
            transform: scale(1.05);
        }

        .qty-btn:active {
            transform: scale(0.95);
        }

        .checkout-btn {
            background: linear-gradient(135deg, #27ae60 0%, #219a52 100%);
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: calc(100% - 60px);
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .empty-message {
            padding: 50px 20px;
            text-align: center;
            color: #7f8c8d;
        }

        .empty-message ion-icon {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        /* Custom Scrollbar */
        .cart-drawer-content::-webkit-scrollbar {
            width: 6px;
        }

        .cart-drawer-content::-webkit-scrollbar-track {
            background: rgba(241, 241, 241, 0.5);
        }

        .cart-drawer-content::-webkit-scrollbar-thumb {
            background: #3498db;
            border-radius: 4px;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .empty-message ion-icon {
            animation: float 3s ease-in-out infinite;
        }

        .price.on-request {
            color: #e67e22;
            font-style: italic;
            font-weight: 500;
        }

        .drawer-item:not(:last-child) {
            border-bottom: 1px solid #ecf0f1;
            margin-bottom: 15px;
            padding-bottom: 15px;
        }

        @media (max-width: 768px) {
            .cart-drawer {
                width: 90vw;
            }

            .drawer-item {
                margin: 10px;
                padding: 15px;
            }

            .drawer-item img {
                width: 80px;
                height: 80px;
            }

            .checkout-btn {
                margin: 20px 15px;
                width: calc(100% - 30px);
            }
        }

        /* Cart Button */

        .cart-btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .cart-btn:hover {
            background-color: #555;
        }

        .cart-drawer {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100%;
            background-color: #fff;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transition: right 0.3s ease-in-out;
            overflow-y: auto;
        }

        .cart-drawer-content {
            padding: 20px;
        }

        .cart-close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            margin-left: auto;
            display: block;
        }

        .cart-header {
            margin-bottom: 20px;
        }

        .cart-title {
            font-size: 24px;
        }

        .drawer-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            gap: 15px;
        }

        .drawer-item img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .item-info {
            flex: 1;
        }

        .item-info h3 {
            font-size: 16px;
            margin: 0 0 5px;
            font-weight: 500;
        }

        .item-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .price {
            font-weight: bold;
            color: #333 !important;
            margin-top: 5px !important;
            text-align: center;
        }

        .remove-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #999;
            cursor: pointer;
            padding: 0 10px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            justify-content: center;
        }

        .qty-btn {
            background: #f5f5f5;
            border: 1px solid #ddd;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
        }

        .checkout-btn {
            width: 100%;
            background: #333;
            color: white;
            border: none;
            padding: 15px;
            margin-top: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .empty-message {
            text-align: center;
            color: #666;
            padding: 40px 20px;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {

            .cart-drawer {
                width: 100%;
                right: -100%;
            }

            .mobile-bottom-navigation .action-btn {
                padding: 12px;
                font-size: 1.2em;
            }

            .action-btn {
                padding: 8px 16px;
                font-size: 14px;
            }

            .cart-title {
                font-size: 20px;
            }

            .cart-item {
                padding: 8px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .cart-title {
                font-size: 18px;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    <style type="text/css" media="all">
        .languages-box {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-right: 0px !important;
            background-color: #fff;
            left: 0px;
            padding: 0px 10px;
            height: 32px;
            width: fit-content !important;
            min-width: 65px;
            text-transform: uppercase;
            font-weight: 400;
            line-height: 10px;
        }

        .languages-box img.flag {
            width: 20px;
            height: 15px;
            margin-right: 8px;
        }

        .languages-box .selected {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .languages-box .current-lang,
        .languages-box .current-currency {
            font-weight: 500;
            font-size: 12px !important;
        }

        .languages-box .sep {
            margin: 0 5px;
            color: #555;
        }

        .languages-box img.arrow {
            margin-left: 5px;
        }

        .dropdown-box {
            font-family: Arial, sans-serif;
            width: 20rem;
            background-color: #fff !important;
            border: 1px solid #E5E5E5;
            padding: 20px;
            right: 10px;
            z-index: 100;
            position: absolute;
            top: 3rem !important;
            text-transform: initial;
            display: none;
        }

        .dropdown-box .title {
            font-size: 15px;
            font-weight: 700;
            line-height: 21px;
            text-align: center;
            margin-bottom: 14px;

        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;

        }


        .form-label {
            border: 1px solid #BFBFBF;
            border-radius: 0px;
            font-family: 'Univers LT Std', sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 16px;
            color: #000;
            padding: 9px 10px;
            border-bottom: 0px !important;
            text-align: center;
        }

        select.form-control {
            width: 100%;
            padding: 12px 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            background-color: #fff;
            outline: none;
        }

        /* Save Button */
        .btn-submit .btn-default {
            background-color: #000;
            color: #fff;
            font-size: 14px;
            width: 100%;
            padding: 15px;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            text-align: center;
        }

        .btn-submit .btn-default:hover {
            background-color: #333;
        }

        .arrow {
            transition: transform 0.3s ease;
            /* Smooth rotation */
        }

        .arrow.rotate {
            transform: rotate(180deg);
        }

        .price[data-on-request]::after {
            content: "ON REQUEST";
            color: #666;
            font-style: italic;
        }

        .price.on-request {
            color: #c00;
            font-weight: bold;
        }

        /* Image overlay */

        .image-preview-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-in-out;
        }

        .image-preview-container {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }

        .image-preview {
            width: 100%;
            height: auto;
            max-height: 80vh;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.3);
        }

        .close-preview {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            font-size: 24px;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .close-preview:hover {
            background: rgba(255, 255, 255, 0.8);
            color: black;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

</head>

<body>
    <!-- ============= HEADER =============  -->
    <header>

        <div class="header-top">

            <div class="container top-container">

                <ul class="header-social-container">

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-twitter"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-instagram"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-linkedin"></ion-icon>
                        </a>
                    </li>

                </ul>

                <div class="header-alert-news">
                    <p>
                        <b>Free Shipping</b>
                        This Week Order Over - $55
                    </p>
                </div>

                <div class="header-top-actions">


                    <div id="google_translate_element" style="display:none;"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({
                                pageLanguage: 'en', // Default page language
                                includedLanguages: 'en,es,fr,de,it,pt,zh-CN,ja,ru,ar', // Add other languages as needed
                                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                            }, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

                    <li class="lang-curr languages-box">
                        <!-- Flag Image -->
                        <img
                            id="languageFlag"
                            alt="language flag"
                            class="mr-1 flag lazyload"
                            src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/language-flags/en.png">


                        <!-- Language and Currency Selector -->
                        <div class="mr-1 d-flex selected">
                            <span class="current-lang">en</span>
                            <span class="sep">|</span>
                            <span class="current-currency" data-currency="USD">USD</span>

                            <!-- Dropdown Icon -->
                            <img
                                alt="dropdown icon"
                                width="7"
                                height="7"
                                data-src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/navigator/triangle-down.svg"
                                class="arrow ml-1 lazyload"
                                src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/navigator/triangle-down.svg">
                        </div>

                        <div id="langCurrBox" class="dropdown-box">
                            <div class="title text-uppercase">Select your currency and language</div>

                            <!-- Language Selector -->
                            <div class="form-group lang-field">
                                <label class="form-label" for="customLanguageSelect">Language</label>
                                <select id="customLanguageSelect" name="language" class="form-control">
                                    <option value="en" selected>English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                    <option value="it">Italian</option>
                                    <option value="pt">Portuguese</option>
                                    <option value="zh-CN">Chinese (Simplified)</option>
                                    <option value="ja">Japanese</option>
                                    <option value="ru">Russian</option>
                                    <option value="ar">Arabic</option>
                                </select>
                            </div>

                            <!-- Currency Selector -->
                            <div class="form-group currency-field">
                                <label class="form-label" for="currency">Currency</label>
                                <select id="currency" name="currency" class="form-control">
                                    <option value="usd" selected>USD</option>
                                    <option value="eur">EUR</option>
                                    <option value="gbp">GBP</option>
                                    <option value="jpy">JPY</option>
                                    <option value="aud">AUD</option>
                                    <option value="inr">INR</option>
                                    <option value="cny">CNY</option>
                                </select>
                            </div>

                            <!-- Save Button -->
                            <div class="btn-submit w-100">
                                <button class="btn btn-default w-100 text-uppercase set-curr-lang" id="saveSettingsBtn">
                                    Save These Settings
                                </button>
                            </div>
                        </div>
                    </li>


                    <button class="logg-button">
                        <?php if (isset($_SESSION['user']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true || isset($_COOKIE['temp']) || isset($_COOKIE['loggedYes'])): ?>
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <span class="log-button"><a class="styled-login" href="/PHP/components/panel.php">Panel</a></span>
                                <span class="log-button">|</span>
                                <span class="log-button"><a class="styled-login" href="../../app/controllers/logout.php">Logout</a></span>
                            <?php else: ?>
                                <span class="log-button"><a class="styled-login" href="../../app/controllers/logout.php">Logout</a></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <i class="ri-user-line"></i>
                            <span class="log-button"><a class="styled-login" href="../../app/controllers/login.php">Log In</a></span>
                            <span class="log-button">|</span>
                            <span class="log-button"><a class="styled-login" href="../../app/controllers/signin.php">Sign Up</a></span>
                        <?php endif; ?>
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                </div>

            </div>

        </div>

        <div class="header-main">

            <div class="container">

                <div class="logoCont">
                    <a href="../../index.php" class="header-logo">
                        <img src="../../celestial-logo.png" loading="lazy" alt="Celestial logo" width="120" height="36">
                    </a>
                </div>
                <div class="header-search-container">
                    <form action="../../app/views/search.php" method="GET" id="searchForm">
                        <input type="search" name="search" id="searchInput" class="search-field" placeholder="Enter your product name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" autocomplete="off">
                        <button type="submit" class="search-btn">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </form>
                    <div id="suggestions" class="suggestions-container"></div>
                </div>




                <div class="header-user-actions">

                    <button class="action-btn profile-btn" title="Personal Dashboard" onclick="javascript:void(0); window.location.href='http://localhost:3000/app/views/user-dashboard.php';">
                        <ion-icon name="person-outline"></ion-icon>
                    </button>






                    <!-- =========================== -->


                    <button class="action-btn" id="wish-btn">
                        <ion-icon name="heart-outline"></ion-icon>
                        <span class="count wish-count global-wish-counter">0</span>
                    </button>



                    <button class="action-btn" id="cart-btn">
                        <ion-icon name="bag-handle-outline"></ion-icon>
                        <span class="count cart-count global-cart-counter" id="cart-count">0</span>
                    </button>



                </div>

            </div>

        </div>

        <nav class="desktop-navigation-menu">

            <div class="container">

                <ul class="desktop-menu-category-list">

                    <li class="menu-category">
                        <a href="../../index.php" class="menu-title">Home</a>
                    </li>

                    <li class="menu-category">
                        <a href="../../app/views/productLanding.php" class="menu-title">Watches</a>

                        <div class="dropdown-panel">

                            <ul class="dropdown-panel-list">

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Patek%20Philippe">Patek Philippe</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Richard%20Mille">Richard Mille</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Audemars%20Piguet">Audemars Piguet</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Vacheron%20Constantin">Vacheron Constantin</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Jaeger-LeCoultre">Jaeger-LeCoultre</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">
                                        <img src="https://www.theluxuryhut.com/admin/upload/1675842246expensive-and-rare-patek-philippe-watches.jpg" alt="patek watch"
                                            width="250" loading="lazy" height="119">
                                    </a>
                                </li>

                            </ul>

                            <ul class="dropdown-panel-list">


                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=IWC%20Schaffhausen">IWC Schaffhausen</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Breguet">Breguet</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Cartier">Cartier</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Blancpain">Blancpain</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Hublot">Hublot</a>
                                </li>


                                <li class="panel-list-item">
                                    <a href="#">
                                        <img style="height: 203px !important;" src="https://www.watchclub.com/upload/watches/gallery_big/watch-club-rolex-oyster-perpetual-box-and-certificate-ref-124300-year-2022-15012-wb.png6.jpg" alt="rolex box image"
                                            width="250" height="119">
                                    </a>
                                </li>

                            </ul>

                            <ul class="dropdown-panel-list">

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Rolex">Rolex</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=A.%20Lange%20%26%20Söhne">A. Lange & Söhne</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Parmigiani%20Fleurier">Parmigiani Fleurier</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Greubel%20Forsey">Greubel Forsey</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Roger%20Dubuis">Roger Dubuis</a>
                                </li>


                                <li class="panel-list-item">
                                    <a href="#">
                                        <img src="https://www.watchclub.com/upload/watches/originali/watch-club-iwc-portuguese-box-and-papers-ref-iw371417-year-2010-wb.jpgwbwbwbwbwb6.jpg" alt="IWC WATCH BOX" loading="lazy" width="250"
                                            height="119">
                                    </a>
                                </li>

                            </ul>

                            <ul class="dropdown-panel-list">

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=MB%26F">MB&F (Maximilian Büsser & Friends)</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Ulysse%20Nardin">Ulysse Nardin</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Zenith">Zenith</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=F.P.%20Journe">F.P. Journe</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="../../app/views/brands.php?brand=Jacob%20%26%20Co.">Jacob & Co.</a>
                                </li>


                                <li class="panel-list-item">
                                    <a href="#">
                                        <img style="height: 200px;" src="https://i.ytimg.com/vi/KeVNb45AXoQ/maxresdefault.jpg" alt="Jacob and CO" width="250"
                                            height="119">
                                    </a>
                                </li>

                            </ul>



                        </div>
                    </li>

                    <li class="menu-category">
                        <a href="../../app/views/about-us.php" class="menu-title">About us</a>
                    </li>

                    <li class="menu-category">
                        <a href="../../app/views/membership.php" class="menu-title">MEMBERSHIP</a>
                    </li>

                    <li class="menu-category">
                        <a href="../../app/views/blogs.php" class="menu-title">Blog</a>
                    </li>

                    <li class="menu-category">
                        <a href="#" class="menu-title">Hot Offers</a>
                    </li>


                </ul>

            </div>

        </nav>

        <!-- /*-----------------------------------*\
           Drawers for cart and wishlist
          \*-----------------------------------*/
   -->

        <div id="wishlist-drawer" class="cart-drawer">
            <div class="cart-drawer-content">
                <button id="wishlist-close-btn" class="cart-close-btn">✖</button>
                <div class="cart-header">
                    <h2 class="cart-title">Your Wishlist</h2>
                </div>
            </div>
        </div>

        <div id="cart-drawer" class="cart-drawer">
            <div class="cart-drawer-content">
                <button id="cart-close-btn" class="cart-close-btn">✖</button>
                <div class="cart-header">
                    <h2 class="cart-title">Your Cart</h2>
                </div>
            </div>
        </div>

        <!-- /*-----------------------------------*\
            MOBILE NAV
          \*-----------------------------------*/
   -->



        <div class="mobile-bottom-navigation">
            <button class="action-btn has-menu-btn" data-mobile-menu-open-btn>
                <ion-icon name="menu-outline"></ion-icon>
            </button>

            <!-- Cart -->

            <button class="action-btn" id="mobile-cart-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span class="count cart-count global-cart-counter">0</span>
            </button>

            <!-- Homepage -->

            <button class="action-btn" onclick="window.location.href ='http://localhost:3000/index.php';">
                <ion-icon name="home-outline"></ion-icon>
            </button>

            <!-- wishlist -->

            <button class="action-btn" id="mobile-wish-btn">
                <ion-icon name="heart-outline"></ion-icon>
                <span class="count wish-count global-wish-counter">0</span>
            </button>

            <!-- Profile -->

            <button class="action-btn profile-btn" onclick="javascript:void(0); window.location.href='http://localhost:3000/app/views/user-dashboard.php';" data-mobile-menu-open-btn>
                <ion-icon name="person-outline"></ion-icon>
            </button>
        </div>



        <nav class="mobile-navigation-menu  has-scrollbar" data-mobile-menu>

            <div class="menu-top">
                <h2 class="menu-title">Menu</h2>

                <button class="menu-close-btn" data-mobile-menu-close-btn>
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>

            <ul class="mobile-menu-category-list">

                <li class="menu-category">
                    <a href="../../index.php" class="menu-title">Home</a>
                </li>

                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn onclick="window.location.href ='http://localhost:3000/app/views/about-us.php';">
                        <p class="menu-title">About Us</p>
                    </button>
                </li>

                <li class="menu-category">

                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Brands</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Patek%20Philippe" class="submenu-title">Patek Philippe</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Richard%20Mille" class="submenu-title">Richard Mille</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Audemars%20Piguet" class="submenu-title">Audemars Piguet</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Vacheron%20Constantin" class="submenu-title">Vacheron Constantin</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Jaeger-LeCoultre" class="submenu-title">Jaeger-LeCoultre</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">
                                <img src="https://www.theluxuryhut.com/admin/upload/1675842246expensive-and-rare-patek-philippe-watches.jpg" loading="lazy" alt="patek watch"
                                    width="250" height="119">
                            </a>
                        </li>

                        <hr>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=IWC%20Schaffhausen" class="submenu-title">IWC Schaffhausen</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Breguet" class="submenu-title">Breguet</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Cartier" class="submenu-title">Cartier</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Blancpain" class="submenu-title">Blancpain</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Hublot" class="submenu-title">Hublot</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">
                                <img style="height: 203px !important;" src="https://www.watchclub.com/upload/watches/gallery_big/watch-club-rolex-oyster-perpetual-box-and-certificate-ref-124300-year-2022-15012-wb.png6.jpg" alt="rolex box image"
                                    width="250" height="119">
                            </a>
                        </li>

                        <hr>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Rolex" class="submenu-title">Rolex</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=A.%20Lange%20%26%20Söhne" class="submenu-title">A. Lange & Söhne</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Parmigiani%20Fleurier" class="submenu-title">Parmigiani Fleurier</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Greubel%20Forsey" class="submenu-title">Greubel Forsey</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Roger%20Dubuis" class="submenu-title">Roger Dubuis</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">
                                <img src="https://www.watchclub.com/upload/watches/originali/watch-club-iwc-portuguese-box-and-papers-ref-iw371417-year-2010-wb.jpgwbwbwbwbwb6.jpg" alt="IWC WATCH BOX" width="250" loading="lazy"
                                    height="119">
                            </a>
                        </li>

                        <hr>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=MB&F" class="submenu-title">MB&F (Maximilian Büsser & Friends)</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Ulysse%20Nardin" class="submenu-title">Ulysse Nardin</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Zenith" class="submenu-title">Zenith</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=F.P.%20Journe" class="submenu-title">F.P. Journe</a>
                        </li>

                        <li class="submenu-category">
                            <a href="../../app/views/brands.php?brand=Jacob%20%26%20Co." class="submenu-title">Jacob & Co.</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">
                                <img style="height: 200px;" src="https://i.ytimg.com/vi/KeVNb45AXoQ/maxresdefault.jpg" alt="Jacob and CO" width="250"
                                    height="119">
                            </a>
                        </li>

                    </ul>

                </li>

                <li class="menu-category">

                    <button class="accordion-menu" data-accordion-btn onclick="window.location.href ='http://localhost:3000/app/views/membership.php';">
                        <p class="menu-title">Membership</p>
                    </button>

                </li>

                <li class="menu-category" onclick="window.location.href ='';">
                    <a href="../../app/views/blogs.php" class="menu-title">Blog</a>
                </li>

                <li class="menu-category">
                    <a href="#" class="menu-title">Hot Offers</a>
                </li>

                <?php
                // Check if the user is logged in (session or cookie) and OTP is verified
                if ((isset($_SESSION['user']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true) || isset($_COOKIE['temp'])):
                ?>

                    <?php
                    // Check if the user is an admin
                    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true):
                    ?>
                        <!-- Admin-specific options -->
                        <li class="menu-category">
                            <a href="/PHP/components/panel.php" class="menu-title">Panel</a>
                        </li>
                        <li class="menu-category">
                            <a href="../../app/controllers/logout.php" class="menu-title">Logout</a>
                        </li>

                    <?php else: ?>
                        <!-- User-specific option -->
                        <li class="menu-category">
                            <a href="../../app/controllers/logout.php" class="menu-title">Logout</a>
                        </li>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Display login and signup links if the user is not logged in -->
                    <li class="menu-category">
                        <a href="../../app/controllers/login.php" class="menu-title">Log In</a>
                    </li>
                    <li class="menu-category">
                        <a href="../../app/controllers/signin.php" class="menu-title">Sign Up</a>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="menu-bottom">

                <ul class="menu-category-list">
                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title" id="selectedCurrency">Currency</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>
                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('usd')">USD &dollar;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('eur')">EUR &euro;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('inr')">INR &#8377;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('gbp')">GBP &pound;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('jpy')">JPY &#165;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('aud')">AUD &dollar;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectCurrency('cny')">CNY &#165;</a>
                            </li>
                        </ul>
                    </li>
                    <div id="google_translate_element" style="display:none;"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({
                                pageLanguage: 'en',
                                autoDisplay: false // If you want to control language switching manually
                            }, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title" id="selectedLanguage">Language</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>
                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('en')">English</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('es')">Spanish</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('fr')">French</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('de')">German</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('it')">Italian</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('pt')">Portuguese</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('zh-CN')">Chinese (Simplified)</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('ja')">Japanese</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('ru')">Russian</a>
                            </li>
                            <li class="submenu-category">
                                <a href="javascript:void(0)" class="submenu-title" onclick="selectLanguage('ar')">Arabic</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="menu-social-container">

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-twitter"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-instagram"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-linkedin"></ion-icon>
                        </a>
                    </li>

                </ul>

            </div>

        </nav>

    </header>

    <script>
        // Sync storage on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (<?= $isLoggedIn ? 'true' : 'false' ?>) {
                const sessionCart = sessionStorage.getItem(CART_KEY);
                const sessionWishlist = sessionStorage.getItem(WISHLIST_KEY);

                if (sessionCart) {
                    localStorage.setItem(CART_KEY, sessionCart);
                    sessionStorage.removeItem(CART_KEY);
                }

                if (sessionWishlist) {
                    localStorage.setItem(WISHLIST_KEY, sessionWishlist);
                    sessionStorage.removeItem(WISHLIST_KEY);
                }

                // Show "Add to Cart" and "Add to Wishlist" buttons
                toggleAddToButtons(true);
            } else {
                // Hide "Add to Cart" and "Add to Wishlist" buttons
                toggleAddToButtons(false);
            }
        });
    </script>

    <script src="../../src/assets/js/navbar.js"></script>
    <script src="../../src/assets/js/imagePreview.js"></script>

</body>

</html>