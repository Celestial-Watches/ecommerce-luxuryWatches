<?php if (!defined('ALLOW_ACCESS')) {
    header("Location: ../../index.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <script src="../../src/assets/js/scroll-animation.js"></script>
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
            white-space: nowrap;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        @media (max-width: 600px) {
            #suggestions {
                max-width: 180px;
            }
        }

        .no-product {
            color: #000;
            padding: 10px;
            text-align: center;
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

                    <select id="currency" name="currency">
                        <option value="usd">USD &dollar;</option>
                        <option value="eur">EUR &euro;</option>
                        <option value="inr">INR &#8377;</option>
                    </select>

                    <div id="google_translate_element" style="display:none;"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({
                                pageLanguage: 'en'
                            }, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

                    <select id="customLanguageSelect" name="language" onchange="translateLanguage(this.value)">
                        <option value="">Select Language</option>
                        <option value="en">English</option>
                        <option value="es">Spanish</option>
                        <option value="fr">France</option>
                    </select>

                    <button class="logg-button">
                        <?php if (isset($_SESSION['user']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true || isset($_COOKIE['temp']) || isset($_COOKIE['loggedYes'])): ?>
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <span class="log-button"><a class="styled-login" href="/PHP/components/panel.php">Panel</a></span>
                                <span class="log-button">/</span>
                                <span class="log-button"><a class="styled-login" href="../../app/controllers/logout.php">Logout</a></span>
                            <?php else: ?>
                                <span class="log-button"><a class="styled-login" href="../../app/controllers/logout.php">Logout</a></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="log-button"><a class="styled-login" href="../../app/controllers/login.php">Log In</a></span>
                            <span class="log-button">/</span>
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

                <a href="#" class="header-logo">
                    <img src="" alt="Celestial logo" width="120" height="36">
                </a>

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

                    <button class="action-btn profile-btn" title="Personal Dashboard">
                        <ion-icon name="person-outline"></ion-icon>
                    </button>

                    <!-- Dropdown for profile -->



                    <!-- <ul class="profile-category">
              <li class="profile-item"><a href="#" class="profile-link">View Profile</a></li>
              <li class="profile-item"><a href="#" class="profile-link">Orders</a></li>
              <li class="profile-item"><a href="#" class="profile-link">Account Settings</a></li>
              <li class="profile-item"><a href="#" class="profile-link">Subscription Management</a></li>
              <hr>
              <li class="profile-item"><a href="#" class="profile-link">Help/Support</a></li>
              <li class="profile-item"><a href="#" class="profile-link">Logout</a></li>
            </ul> -->




                    <!-- =========================== -->


                    <button class="action-btn">
                        <ion-icon name="heart-outline"></ion-icon>
                        <span class="count">0</span>
                    </button>

                    <button class="action-btn add-to-cart">
                        <ion-icon name="bag-handle-outline"></ion-icon>
                        <span class="count" id="cart-count">0</span>
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
                                            width="250" height="119">
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
                                        <img src="https://www.watchclub.com/upload/watches/originali/watch-club-iwc-portuguese-box-and-papers-ref-iw371417-year-2010-wb.jpgwbwbwbwbwb6.jpg" alt="IWC WATCH BOX" width="250"
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
                        <a href="#" class="menu-title">MEMBERSHIP</a>
                    </li>

                    <li class="menu-category">
                        <a href="#" class="menu-title">Blog</a>
                    </li>

                    <li class="menu-category">
                        <a href="#" class="menu-title">Hot Offers</a>
                    </li>


                </ul>

            </div>

        </nav>



        <!-- /*-----------------------------------*\
            MOBILE NAV
          \*-----------------------------------*/
   -->



        <div class="mobile-bottom-navigation">

            <button class="action-btn has-menu-btn" data-mobile-menu-open-btn>
                <ion-icon name="menu-outline"></ion-icon>
            </button>

            <button class="action-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>

                <span class="count">0</span>
            </button>

            <button class="action-btn" onclick="window.location.href ='http://localhost:3000/index.php';">
                <ion-icon name="home-outline"></ion-icon>
            </button>

            <button class="action-btn">
                <ion-icon name="heart-outline"></ion-icon>

                <span class="count">0</span>
            </button>

            <button class="action-btn profile-btn" data-mobile-menu-open-btn>
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
                                <img src="https://www.theluxuryhut.com/admin/upload/1675842246expensive-and-rare-patek-philippe-watches.jpg" alt="patek watch"
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
                                <img src="https://www.watchclub.com/upload/watches/originali/watch-club-iwc-portuguese-box-and-papers-ref-iw371417-year-2010-wb.jpgwbwbwbwbwb6.jpg" alt="IWC WATCH BOX" width="250"
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

                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Membership</p>
                    </button>

                </li>

                <li class="menu-category">
                    <a href="#" class="menu-title">Blog</a>
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
                                <a href="" class="submenu-title" onclick="selectCurrency('usd')">USD &dollar;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="" class="submenu-title" onclick="selectCurrency('eur')">EUR &euro;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="" class="submenu-title" onclick="selectCurrency('inr')">INR &#8377;</a>
                            </li>
                        </ul>
                    </li>
                    <div id="google_translate_element" style="display:none;"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({
                                pageLanguage: 'en'
                            }, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title" id="selectedLanguage">Language</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>
                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="" class="submenu-title" onclick="selectLanguage('en')">English</a>
                            </li>
                            <li class="submenu-category">
                                <a href="" class="submenu-title" onclick="selectLanguage('es')">Español</a>
                            </li>
                            <li class="submenu-category">
                                <a href="" class="submenu-title" onclick="selectLanguage('fr')">Français</a>
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
        // Wait for the DOM to fully load
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const suggestions = document.getElementById('suggestions');
            const searchForm = document.getElementById('searchForm');
            let selectedIndex = -1; // Track the currently selected suggestion

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value;

                    if (query.length > 1) {
                        fetch('http://localhost:3000/app/controllers/fetch-suggestion.php?search=' + encodeURIComponent(query))
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                suggestions.innerHTML = ''; // Clear previous suggestions
                                suggestions.style.display = 'none'; // Hide by default
                                selectedIndex = -1; // Reset selected index

                                if (data.length > 0) {
                                    suggestions.style.display = 'block'; // Show suggestions
                                    data.forEach((item, index) => {
                                        const suggestionItem = document.createElement('div');
                                        suggestionItem.classList.add('suggestion-item');
                                        suggestionItem.textContent = item.name;

                                        suggestionItem.addEventListener('click', function() {
                                            searchInput.value = item.name;
                                            suggestions.style.display = 'none';
                                            searchForm.submit();
                                        });

                                        suggestionItem.addEventListener('mousedown', function() {
                                            searchInput.value = item.name;
                                            suggestions.style.display = 'none';
                                            searchForm.submit();
                                        });

                                        suggestions.appendChild(suggestionItem);

                                        if (index < data.length - 1) {
                                            const hr = document.createElement('hr');
                                            suggestions.appendChild(hr);
                                        }
                                    });
                                } else {
                                    // Show "No product found" if no suggestions
                                    const noProductItem = document.createElement('div');
                                    noProductItem.classList.add('no-product');
                                    noProductItem.textContent = 'No product found';
                                    suggestions.appendChild(noProductItem);
                                    suggestions.style.display = 'block'; // Show the message
                                }
                            })
                            .catch(error => console.error('Error fetching suggestions:', error));
                    } else {
                        suggestions.style.display = 'none'; // Hide if query is short
                    }
                });

                // Keydown event listener for arrow keys and enter
                searchInput.addEventListener('keydown', function(event) {
                    const suggestionItems = suggestions.querySelectorAll('.suggestion-item');

                    if (event.key === 'ArrowDown') {
                        selectedIndex = (selectedIndex + 1) % suggestionItems.length; // Move down
                        updateSuggestionSelection(suggestionItems);
                        event.preventDefault(); // Prevent default scrolling
                    } else if (event.key === 'ArrowUp') {
                        selectedIndex = (selectedIndex - 1 + suggestionItems.length) % suggestionItems.length; // Move up
                        updateSuggestionSelection(suggestionItems);
                        event.preventDefault(); // Prevent default scrolling
                    } else if (event.key === 'Enter') {
                        if (selectedIndex >= 0 && selectedIndex < suggestionItems.length) {
                            suggestionItems[selectedIndex].click(); // Trigger click on selected item
                        }
                    }
                });

                // Function to update the selected suggestion style
                function updateSuggestionSelection(suggestionItems) {
                    suggestionItems.forEach((item, index) => {
                        if (index === selectedIndex) {
                            item.classList.add('selected'); // Add selected class for styling
                        } else {
                            item.classList.remove('selected'); // Remove selected class
                        }
                    });
                }
            } else {
                console.error('Search input not found. Please check your HTML.');
            }
        });
    </script>




</body>

</html>