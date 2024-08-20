<?php
session_start(); // Ensure this is at the top of the file
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start(); // Start output buffering

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit(); // Stop further execution after redirection
}

$redirectToVerify = false; // Flag for redirection
$errors = []; // Initialize errors array

if (isset($_POST["submit"])) {
    $usernamee = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["confirm_password"];

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Validation
    if (empty($usernamee) || empty($email) || empty($phone) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid";
    }

    if (!preg_match("/^\+\d{1,3}\d{10}$/", $phone)) {
        $errors[] = "Phone number must include country code and be at least 10 digits long (e.g., +919876543210)";
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long, and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    if ($password !== $passwordRepeat) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        require_once "conn.php"; // Include database connection

        // Check for unique username
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $usernamee);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $errors[] = "Username already exists!";
            }
        }

        // Check for unique email
        $sql = "SELECT * FROM users WHERE email = ?";
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $errors[] = "Email already exists!";
            }
        }

        // If no errors, generate OTP and send it
        if (empty($errors)) {
            // Set session variables
            $_SESSION['username'] = $usernamee; // Store username in session
            $_SESSION['email'] = $email; // Store email in session
            $_SESSION['phone'] = $phone; // Store phone in session
            $_SESSION['password'] = $passwordHash; // Store hashed password in session
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT']; // Store user agent
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR']; // Store IP address

            // Debugging: Check if session variables are set
            // var_dump($_SESSION); // Check session data

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp; // Store OTP in session
            $_SESSION['otp_expiry'] = time() + 300; // Set OTP expiry time

            // Send OTP via email
            require 'vendor/autoload.php'; // Ensure PHPMailer is included
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'celestialwatches69@gmail.com';
                $mail->Password   = 'xvmjnggsmsnkavzt';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = "Your OTP code is: <b>$otp</b>";
                $mail->AltBody = "Your OTP code is: $otp";

                // Ensure the email sending was successful before redirecting
                if ($mail->send()) {
                    header("Location: verify_otp.php"); // Redirect to verify OTP page
                    exit(); // Ensure no further code is executed after redirection
                } else {
                    $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
                }
            } catch (Exception $e) {
                $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>

    <!-- ============= IONICONS =============  -->
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

    <!-- ============= CSS =============  -->
    <link rel="stylesheet" href="/css/deskView.css" />

    <!-- ============= FONTS=============  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

</head>

<body>

    <!-- ===================================================== HEADER =====================================================  -->

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
                        <a href="index.php" class="menu-title">Home</a>
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

                <?php if (isset($_SESSION['user'])): ?>
                    <li class="menu-category">
                        <a href="logout.php" class="menu-title">Logout</a>
                    </li>
            <?php else: ?>
                    <li class="menu-category">
                        <a href="login.php" class="menu-title">Log In</a>
                    </li>

                    <li class="menu-category">
                        <a href="signin.php" class="menu-title">Sign Up</a>
                    </li> 
            <?php endif; ?>

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

    <!-- ===================================================== HEADER END =====================================================  -->

    <!--    /***************************************************
        **** SIGN IN CSS START****
        ***************************************************/    -->

    <div class="login-container">
        <div class="login-box signin-box">
            <h1>Welcome to Celestial Watches</h1>
            <?php
            // Display errors
            if (!empty($errors)) {
                echo "<div class='error-container'>";
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                echo "</div>";
            }
            ?>
            <form action="signin.php" method="post">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"  autocomplete="on">
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" autocomplete="on">
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number (with country code)</label>
                    <input type="text" id="phone" name="phone" placeholder="+919876543210" autocomplete="on">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="on">
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" autocomplete="on">
                </div>
                <button type="submit" class="login-button" name="submit">Create Account</button>
                <div class="login-footer">
                    <a href="login.php">Already have an account? Login</a>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        // ============================= NAVIGATION OPEN =============================


    const actionBtns = document.querySelectorAll('.has-menu-btn'); // This is for the side menu for navigation
    const menuCloseBtns = document.querySelectorAll('.menu-close-btn'); // This is for side menu closing button
    const accordionBtns = document.querySelectorAll('[data-accordion-btn]'); // This is for side menu dropdown button

    actionBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
        mobileNavigationMenu.classList.toggle('active');
    });
    });

    menuCloseBtns.forEach(btn => {
        btn.addEventListener('click', () => {
        const mobileNavigationMenu = document.querySelector('.mobile-navigation-menu');
        mobileNavigationMenu.classList.remove('active');
        });
    });

    accordionBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const submenu = btn.nextElementSibling;
        submenu.classList.toggle('active');
        btn.classList.toggle('active'); 
    });
    });

// ============================= NAVIGATION CLOSE =============================

console.log(actionBtns);
console.log(menuCloseBtns);
console.log(accordionBtns);
    </script>
    <!-- ============= JS =============  -->
    <script src="/js/index.js"></script>

</body>
</html>