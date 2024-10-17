<?php

// Set the session cookie with secure attributes
session_set_cookie_params([
    'lifetime' => 86400,              // Session expires when the browser is closed
    'path' => '/',                // Available throughout the site
    'domain' => '',               // Leave empty for current domain
    'secure' => false,             // Only send over HTTPS
    'httponly' => true,           // Prevent JavaScript access
    'samesite' => 'Strict'        // Protect against CSRF
]);

date_default_timezone_set('Asia/Kolkata');
session_start();

// Regenerate the session ID on every page refresh
session_regenerate_id(true);


// Check if the user navigated back from verify_otp.php
if (isset($_SESSION['otp']) && !isset($_SESSION['user'])) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
}

// Check if the user is logged in and is an admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

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
            header("Location: app/controllers/login.php"); // Redirect to login page with timeout message
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- ============= JS =============  -->
    <script src="/src/assets/js/navigation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css" />
    <link rel="stylesheet" href="/src/assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">



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
                <img src="/src/assets/image/newsletter.jpg" alt="subscribe newsletter" width="400" height="450">
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

    <?php include 'PHP/components/loader.php';
    ?>

    <!-- ============= HEADER =============  -->

    <?php include 'PHP/components/navbar.php';
    ?>

    <!-- ================================ MAIN ================================  -->

    <?php include 'PHP/components/banner.php';
    ?>

    <?php include 'PHP/components/logo-slider.php';
    ?>

    <?php include 'PHP/components/featured-product.php';
    ?>
    <hr style="border-color: #ffffff;">

    <?php include 'PHP/components/trending-article.php';
    ?>

    

    <!-- ================================ JS ================================  -->
    <script src="/src/assets/js/swiper-bundle.min.js"></script>
    <script src="/src/assets/js/index.js"></script>
    <script src="/src/assets/js/currency-language.js"></script>


</body>

</html>