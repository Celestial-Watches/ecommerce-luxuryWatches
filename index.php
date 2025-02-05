<?php

define('ALLOW_ACCESS', true);

// Define secure session cookie settings:
// lifetime: Session duration (86400 seconds = 24 hours).
// path: Set cookie available across the entire site.
// domain: Use current domain (default).
// secure: false means cookies are not restricted to HTTPS (should be true in production).
// httponly: true to prevent JavaScript access to the cookie.
// samesite: Set to Strict to mitigate CSRF attacks by restricting cross-site requests.

session_set_cookie_params([
    'lifetime' => 86400, // 1 day
    'path' => '/',
    'domain' => '', // Set to your domain
    'secure' => true, // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict' // or 'Lax' based on your needs
]);

// Set the default timezone to 'Asia/Kolkata'.
// Start the PHP session to manage user data across pages.

date_default_timezone_set('Asia/Kolkata');
session_start();


// Call session_regenerate_id() to:
// Create a new session ID for the user.
// Mitigate session fixation attacks.

session_regenerate_id(true);

// If the session contains OTP data but not the user data:
// Unset the session variables using session_unset().
// Destroy the session using session_destroy() to clear user authentication.

if (isset($_SESSION['otp']) && !isset($_SESSION['user'])) {
    session_unset();
    session_destroy();
}

// Verify if:
//     The session has the admin variable set.
//     The admin value is true, indicating admin status.


$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;


// Define $sessionTimeout as 3600 seconds (60 minutes).

$sessionTimeout = 3600;

// Check if the session creation time (CREATED) is set:
//     If not, set $_SESSION['CREATED'] to the current time.
//     If it exists and more than 600 seconds (10 minutes) have passed since creation:
//     Regenerate the session ID using session_regenerate_id().
//     Reset the CREATED timestamp to the current time.

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 600) {

    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// 1. Check if the `user` variable exists in the session, indicating a logged-in user.
// 2. Verify if `LAST_ACTIVITY` exists in the session:
//    - Calculate the session idle time by comparing the current time with `LAST_ACTIVITY`.
//    - If the idle time exceeds `$sessionTimeout` (e.g., 60 minutes):
//      1. Update the user's `status` to `'NO'` in the database to indicate they are logged out.
//      2. Clear the session variables.
//      3. Destroy the session.
//      4. Redirect the user to the login page (`app/controllers/login.php`).
// 3. If the session is still active, update the `LAST_ACTIVITY` timestamp to extend the session duration.

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        $sessionDuration = time() - $_SESSION['LAST_ACTIVITY'];

        if ($sessionDuration > $sessionTimeout) {
            require 'app/config/conn.php';
            // Update user status to 'NO' in the database
            $updateSql = "UPDATE users SET status = 'NO' WHERE username = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();

            // Clear session data and log the user out
            session_unset();
            session_destroy();
            header("Location: app/controllers/login.php");
            exit();
        }
    }

    // Update the last activity time
    $_SESSION['LAST_ACTIVITY'] = time();
}

// echo '<pre>';
// print_r($_SESSION);  // Debugging: See what session data is set
// echo '</pre>';
// throw new Exception('This is a test exception!');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Celestial Watches | Exclusivity in Every Tick</title>



    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js" integrity="sha512-a+SUDuwNzXDvz4XrIcXHuCf089/iJAoN4lmrXJg18XnduKK6YlDHNRalv4yd1N40OKI80tFidF+rqTFKGPoWFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



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
        /* Cookie Card */
        .cookie-card {
            max-width: 320px;
            padding: 1rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 20px 20px 30px rgba(0, 0, 0, .05);
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            /* Hidden by default */
            z-index: 9999;
            transition: all 0.3s ease-out;
        }

        /* Title */
        .title {
            font-weight: 600;
            color: rgb(31 41 55);
            font-size: 1rem;
        }

        /* Description */
        .description {
            margin-top: 1rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: rgb(75 85 99);
        }

        .description a {
            color: rgb(59 130 246);
            text-decoration: none;
        }

        .description a:hover {
            text-decoration: underline;
        }

        /* Actions */
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            gap: 1rem;
        }

        .pref {
            font-size: 0.75rem;
            line-height: 1rem;
            color: rgb(31 41 55);
            text-decoration: underline;
            border: none;
            background-color: transparent;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .pref:hover {
            color: rgb(156 163 175);
        }

        .pref:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        /* Accept Button */
        .accept {
            font-size: 0.75rem;
            line-height: 1rem;
            background-color: rgb(17 24 39);
            font-weight: 500;
            border-radius: 0.5rem;
            color: #fff;
            padding: 0.625rem 1rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .accept:hover {
            background-color: rgb(55 65 81);
        }

        .accept:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        /* Cookie Settings Modal */
        .cookie-settings-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            /* Hidden by default */
            z-index: 10000;
            transition: opacity 0.3s ease-in-out;
        }

        .cookie-settings-modal .cookie-settings-content {
            background-color: white;
            padding: 30px;
            margin: 100px auto;
            width: 300px;
            text-align: left;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .cookie-settings-modal .cookie-settings-content h2 {
            margin-bottom: 15px;
            font-size: 1.25rem;
            color: rgb(31 41 55);
        }

        .cookie-settings-modal .cookie-settings-content label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.875rem;
            color: rgb(75 85 99);
        }

        .cookie-settings-modal .cookie-settings-content .btn {
            background-color: rgb(17 24 39);
            color: white;
            border: none;
            padding: 0.625rem 1rem;
            cursor: pointer;
            border-radius: 8px;
            margin-top: 15px;
            transition: background-color 0.15s ease;
        }

        .cookie-settings-modal .cookie-settings-content .btn:hover {
            background-color: rgb(55 65 81);
        }

        /* Small Screen Adjustments */
        @media (max-width: 480px) {
            .cookie-card {
                width: 90%;
            }

            .cookie-settings-modal .cookie-settings-content {
                width: 80%;
            }
        }
    </style>
</head>

<body>

    <div class="cookie-card">
        <span class="title">🍪 Cookie Notice</span>
        <p class="description">We use cookies to ensure that we give you the best experience on our website. <a href="#">Read cookies policies</a>.</p>
        <div class="actions">
            <button class="pref" id="manage-preferences">
                Manage your preferences
            </button>
            <button class="accept" id="accept-cookies">
                Accept
            </button>
        </div>
    </div>

    <!-- Cookie Preferences Modal -->
    <div id="cookie-settings-modal" class="cookie-settings-modal">
        <div class="cookie-settings-content">
            <h2>Cookie Preferences</h2>
            <label>
                <input type="checkbox" id="cookie-analytics" /> Enable analytics cookies
            </label>
            <label>
                <input type="checkbox" id="cookie-marketing" /> Enable marketing cookies
            </label>
            <button id="save-cookies" class="btn">Save Settings</button>
            <button id="close-settings" class="btn">Close</button>
        </div>
    </div>

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
                <img src="/src/assets/image/newsletter.jpg" loading="lazy" alt="subscribe newsletter" width="450" height="450">
            </div>

            <div class="newsletter">

                <form>

                    <div class="newsletter-header">

                        <h3 class="newsletter-title">Subscribe Newsletter.</h3>

                        <p class="newsletter-desc">
                            Subscribe the <b>Celestial Watches </b> to get latest products and discount update.
                        </p>

                    </div>

                    <input type="email" id="subscribe-email" name="email" class="email-field" placeholder="Email Address" required>

                    <button type="submit" class="btn-newsletter">Subscribe</button>

                    <div id="feedback-message"></div>

                </form>

            </div>

        </div>

    </div>



    <!-- ================================================================ -->



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


    <?php include 'app/controllers/fetch_random_products.php';
    ?>


    <?php include 'PHP/components/trending-article.php';
    ?>

    <?php include 'PHP/components/benefit.php' ?>

    <?php include 'PHP/components/footer.php' ?>
    <!-- ================================ JS ================================  -->
    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <!-- <script src="/src/assets/js/cookie-monitor.js" async></script> -->


</body>

</html>