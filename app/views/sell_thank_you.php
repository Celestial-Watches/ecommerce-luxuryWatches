<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: selling.php");
    exit();
}

define('ALLOW_ACCESS', true);

require_once '../config/conn.php';

// Current user
$user_id = $_SESSION['user_id'];

$sell_request_id = isset($_GET['sell_request_id']) ? intval($_GET['sell_request_id']) : 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Celestial Watches - My Stock Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ICONS & FONTS -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- JS -->
    <script src="/src/assets/js/navigation.js" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="/src/assets/css/deskView.css">
    <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">
    <link rel="stylesheet" href="assets/css/stock-request.css">

    <style>
        .my-account-container {
            display: flex;
            min-height: calc(100% - 60px);
            padding: 20px;
            gap: 20px;
        }
    </style>
</head>

<body>

    <?php include '../../PHP/components/navbar.php'; ?>
    <div class="my-account-container">
        <!-- LEFT SIDEBAR -->
        <aside class="sidebar">
            <h2>My Account</h2>
            <ul>
                <li><a href="my_requests.php" class="active">My Stock Requests</a></li>
                <li><a href="user-dashboard.php">Personal Details</a></li>
                <li><a href="user-dashboard.php">Settings</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="thankyou-container">
                <!-- Optional Tracker -->
                <div class="tracker-steps">
                    <div class="tracker-step completed">
                        <span class="step-title">Select Your Watch</span>
                    </div>
                    <div class="tracker-step completed">
                        <span class="step-title">Informations</span>
                    </div>
                    <div class="tracker-step completed">
                        <span class="step-title">Watch Inspection</span>
                    </div>
                </div>
                <div class="trophy-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="79" height="65" fill="currentColor">
                        <path d="M13.0049 16.9409V19.0027H18.0049V21.0027H6.00488V19.0027H11.0049V16.9409C7.05857 16.4488 4.00488 13.0824 4.00488 9.00275V3.00275H20.0049V9.00275C20.0049 13.0824 16.9512 16.4488 13.0049 16.9409ZM6.00488 5.00275V9.00275C6.00488 12.3165 8.69117 15.0027 12.0049 15.0027C15.3186 15.0027 18.0049 12.3165 18.0049 9.00275V5.00275H6.00488ZM1.00488 5.00275H3.00488V9.00275H1.00488V5.00275ZM21.0049 5.00275H23.0049V9.00275H21.0049V5.00275Z"></path>
                    </svg>
                </div>
                <!-- Confirmation Message -->
                <div class="confirmation-box">
                    <h2>Congratulations!</h2>
                    <p>Your sell request <strong>#<?php echo htmlspecialchars($sell_request_id); ?></strong> has been submitted.</p>
                    <p>
                        Check your email shortly for a confirmation and further instructions. For the latest watch news, please follow us on <a style="display: inline;" href="https://www.instagram.com/celestial._.watches/?utm_source=ig_web_button_share_sheet" target="_blank" rel="noopener">Celestial Watches</a>
                    </p>
                    <button class="continue-btn" onclick="window.location.href='/index.php'">
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>

        <!-- Right SIDEBAR -->
        <aside class="sidebar" style="width: 350px; padding:20px; text-align:center;">
            <h2>Need Help? Contact Us</h2>
            <div class="help-text" style="text-align:start; padding: 10px 20px;">
                <p class="help-text-para">
                    Placing an order, tracking your purchase, or arranging an exchange or return?
                </p>
            </div>
            <!-- Contact Section -->
            <div class="contacts" style="margin-top: 20px; text-align: left;">
                <div class="contact-item" style="margin-bottom: 10px;">
                    <a style="font-weight: 200;" target="_blank" rel="nofollow" href="https://api.whatsapp.com/send?phone=9909573480&amp;text=Hi">
                        <img alt="whatsapp" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/whatsapp.png" style="vertical-align: middle; width:20px; height:20px; margin-right:8px;">
                        +91 9909573480
                    </a>
                </div>
                <div class="contact-item" style="margin-bottom: 10px; white-space:nowrap;">
                    <a style="font-weight: 200;" href="mailto:celestialwatches69@gmail.com">
                        <img alt="mail" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/mail.png" style="vertical-align: middle; width:20px; height:20px; margin-right:8px;">
                        celestialwatches69@gmail.com
                    </a>
                </div>
                <div class="contact-item">
                    <a style="font-weight: 200;" href="tel:+919909573480">
                        <img alt="phone" src="https://www.watchesworld.com/wp-content/themes/ww2/assets/images/sell-exchange/phone.png" style="vertical-align: middle; width:20px; height:20px; margin-right:8px;">
                        +91 9909573480
                    </a>
                </div>
            </div>
        </aside>

    </div>
    <?php include '../../PHP/components/footer.php'; ?>
    <!-- Additional JS Files -->
    <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
    <script src="/src/assets/js/index.js" async></script>
    <script src="/src/assets/js/currency-language.js" async></script>
    <script src="/src/assets/js/cookie-monitor.js" async></script>
    <script src="/src/assets/js/imagePreview.js" async></script>
</body>

</html>