<?php
 date_default_timezone_set('Asia/Kolkata');
session_start();

// Set session cookie parameters (if not set in login.php)
if (session_status() === PHP_SESSION_NONE) {
  session_set_cookie_params([
      'lifetime' => 86400, // 1 day
      'path' => '/',
      'domain' => '',
      'secure' => false,
      'httponly' => true,
      'samesite' => 'Lax'
  ]);
}

// Set a session timeout period in seconds (e.g., 1800 seconds = 30 minutes)
$sessionTimeout = 1800;

// Regenerate session ID periodically to prevent session fixation/hijacking
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 600) {
    // Regenerate session ID every 10 minutes
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Session expiration handling
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        $sessionDuration = time() - $_SESSION['LAST_ACTIVITY'];
        if ($sessionDuration > $sessionTimeout) {
            // Session expired: unset and destroy session
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=true"); // Redirect to login page with timeout message
            exit();
        }
    }

    // Update last activity time stamp to extend the session
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Celestial Watches - Exclusivity in Every Tick</title>

    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="/css/deskView.css"/>

    <!-- ============= FONTS=============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">


  </head>
  <body>

    
    <!-- ============= Newsletter=============  -->

    <div class="overlay" data-overlay></div>

  <!-- ============= MODAL =============  -->

  <div class="modal" data-modal>

    <div class="modal-close-overlay" data-modal-overlay></div>

    <div class="modal-content">

      <button class="modal-close-btn" data-modal-close>
        <ion-icon name="close-outline"></ion-icon>
      </button>

      <div class="newsletter-img">
        <img src="/image/newsletter.jpg" alt="subscribe newsletter" width="400" height="450">
      </div>

      <div class="newsletter">

        <form action="#">

          <div class="newsletter-header">

            <h3 class="newsletter-title">Subscribe Newsletter.</h3>

            <p class="newsletter-desc">
              Subscribe the <b>Celestial Watches </b> to get latest products and discount update.
            </p>

          </div>

          <input type="email" name="email" class="email-field" placeholder="Email Address" required>

          <button type="submit" class="btn-newsletter">Subscribe</button>

        </form>

      </div>

    </div>

  </div>



  <!-- ================================================================ -->



    <!-- ============= HEADER =============  -->
    <header>

      <div class="header-top">
  
        <div class="container">
  
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
  
            <select name="currency">
  
              <option value="usd">USD &dollar;</option>
              <option value="eur">EUR &euro;</option>
  
            </select>
  
            <select name="language">
  
              <option value="en-US">English</option>
              <option value="es-ES">Espa&ntilde;ol</option>
              <option value="fr">Fran&ccedil;ais</option>
  
            </select>

            <?php if (isset($_SESSION['user'])): ?>
                <span><a href="logout.php">Logout</a></span>
            <?php else: ?>
                <span><a href="login.php">Log In</a></span>
                <span>/</span>
                <span><a href="signin.php">Sign Up</a></span>
            <?php endif; ?>
  
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
  
            <button class="action-btn profile-btn">
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
              <a href="#" class="menu-title">Home</a>
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
                      <img src="/image/electronics-banner-2.jpg" alt="headphone collection" width="250"
                        height="119">
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
                      <img src="/image/mens-banner.jpg" alt="men's fashion" width="250" height="119">
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
                      <img src="/image/womens-banner.jpg" alt="women's fashion" width="250" height="119">
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
                      <img src="/image/electronics-banner-2.jpg" alt="mouse collection" width="250" height="119">
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
            <a href="#" class="menu-title">Home</a>
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
                  <img src="/image/mens-banner.jpg" alt="men's fashion" width="250" height="119">
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
              <p class="menu-title">MEMBERSHIP</p>
            </button>
  
          </li>
  
          <li class="menu-category">
            <a href="#" class="menu-title">Blog</a>
          </li>
  
          <li class="menu-category">
            <a href="#" class="menu-title">Hot Offers</a>
          </li>

          <li class="menu-category">
            <a href="login.php" class="menu-title">Log In</a>
          </li>

          <li class="menu-category">
            <a href="signin.php" class="menu-title">Sign Up</a>
          </li> 
  
        </ul>
  
        <div class="menu-bottom">
  
          <ul class="menu-category-list">
  
            <li class="menu-category">
  
              <button class="accordion-menu" data-accordion-btn>
                <p class="menu-title">Language</p>
  
                <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
              </button>
  
              <ul class="submenu-category-list" data-accordion>
  
                <li class="submenu-category">
                  <a href="#" class="submenu-title">English</a>
                </li>
  
                <li class="submenu-category">
                  <a href="#" class="submenu-title">Espa&ntilde;ol</a>
                </li>
  
                <li class="submenu-category">
                  <a href="#" class="submenu-title">Fren&ccedil;h</a>
                </li>
  
              </ul>
  
            </li>
  
            <li class="menu-category">
              <button class="accordion-menu" data-accordion-btn>
                <p class="menu-title">Currency</p>
                <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
              </button>
  
              <ul class="submenu-category-list" data-accordion>
                <li class="submenu-category">
                  <a href="#" class="submenu-title">USD &dollar;</a>
                </li>
  
                <li class="submenu-category">
                  <a href="#" class="submenu-title">EUR &euro;</a>
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

    

    <!-- ============= MAIN =============  -->
    <main class="main"></main>

    <!-- ============= HOME =============  -->
    <section class="home"></section>

    <!-- ============= CATEGORIES =============  -->
    <section class="categories"></section>

    <!-- ============= PRODUCTS =============  -->
    <section class="products"></section>

    <!-- ============= DEALS =============  -->
    <section class="deals"></section>

    <!-- ============= JS =============  -->
    <script src="/js/index.js"></script>
  </body>
</html>
