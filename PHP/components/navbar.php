<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches - Exclusivity in Every Tick</title>
    <script src="../../src/assets/js/scroll-animation.js"></script>
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

                    <input type="search" name="search" class="search-field" placeholder="Enter your product name...">

                    <button class="search-btn">
                        <ion-icon name="search-outline"></ion-icon>
                    </button>

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

                    <button class="action-btn">
                        <ion-icon name="bag-handle-outline"></ion-icon>
                        <span class="count">0</span>
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
                        <a href="#" class="menu-title">Watches</a>

                        <div class="dropdown-panel">

                            <ul class="dropdown-panel-list">

                                <li class="menu-title">
                                    <a href="#">Electronics</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Desktop</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Laptop</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Camera</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Tablet</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Headphone</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">
                                        <img src="" alt="headphone collection"
                                            width="250" height="119">
                                    </a>
                                </li>

                            </ul>

                            <ul class="dropdown-panel-list">

                                <li class="menu-title">
                                    <a href="#">Men's</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Formal</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Casual</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Sports</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Jacket</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Sunglasses</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">
                                        <img src="../../src/assets/image/mens-banner.jpg" alt="men's fashion" width="250" height="119">
                                    </a>
                                </li>

                            </ul>

                            <ul class="dropdown-panel-list">

                                <li class="menu-title">
                                    <a href="#">Women's</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Formal</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Casual</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Perfume</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Cosmetics</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Bags</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">
                                        <img src="../../src/assets/image/womens-banner.jpg" alt="women's fashion" width="250"
                                            height="119">
                                    </a>
                                </li>

                            </ul>

                            <ul class="dropdown-panel-list">

                                <li class="menu-title">
                                    <a href="#">Electronics</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Smart Watch</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Smart TV</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Keyboard</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Mouse</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">Microphone</a>
                                </li>

                                <li class="panel-list-item">
                                    <a href="#">
                                        <img src="" alt="mouse collection" width="250"
                                            height="119">
                                    </a>
                                </li>

                            </ul>



                        </div>
                    </li>

                    <li class="menu-category">
                        <a href="#" class="menu-title">Men's</a>

                        <ul class="dropdown-list">

                            <li class="dropdown-item">
                                <a href="#">Shirt</a>
                            </li>

                            <li class="dropdown-item">
                                <a href="#">Shorts & Jeans</a>
                            </li>

                            <li class="dropdown-item">
                                <a href="#">Safety Shoes</a>
                            </li>

                            <li class="dropdown-item">
                                <a href="#">Wallet</a>
                            </li>

                        </ul>
                    </li>

                    <li class="menu-category">
                        <a href="#" class="menu-title">Women's</a>

                        <ul class="dropdown-list">

                            <li class="dropdown-item">
                                <a href="#">Dress & Frock</a>
                            </li>

                            <li class="dropdown-item">
                                <a href="#">Earrings</a>
                            </li>

                            <li class="dropdown-item">
                                <a href="#">Necklace</a>
                            </li>

                            <li class="dropdown-item">
                                <a href="#">Makeup Kit</a>
                            </li>

                        </ul>
                    </li>

                    <li class="menu-category">
                        <a href="#" class="menu-title">About us</a>
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

            <button class="action-btn">
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
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">About Us</p>
                    </button>
                </li>

                <li class="menu-category">

                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Men's</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Formal</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Casual</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Sports</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Jacket</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Sunglasses</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">
                                <img src="../../src/assets/image/mens-banner.jpg" alt="men's fashion" width="250" height="119">
                            </a>
                        </li>

                    </ul>

                </li>

                <li class="menu-category">

                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Women's</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Dress & Frock</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Earrings</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Necklace</a>
                        </li>

                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Makeup Kit</a>
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
</body>
</html>