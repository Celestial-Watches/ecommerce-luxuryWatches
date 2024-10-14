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

// Set secure session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Ensure your site is served over HTTPS
ini_set('session.cookie_samesite', 'Strict'); // Additional session security


// Start session and output buffering
session_start();
ob_start();

// Regenerate the session ID on every page refresh
session_regenerate_id(true);


if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
  // User hasn't verified OTP yet, clear the session variable or reset it
  unset($_SESSION['user']);
  // Optionally, clear the cookies if you're storing the user info in cookies
  setcookie("SSIDU", "", time() - 3600, "/", true,true); // Expire the cookie
}

// Redirect logged-in users to index.php
if (isset($_SESSION['user'])) {
  header("Location: index.php");
  exit();
}

require_once "conn.php"; // Include database connection

// Ensure CSRF token is set
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if user is already logged in
if (isset($_SESSION["user"])) {
  header("Location: index.php");
  exit(); // Stop further execution after redirection
}

// Initialize errors array
$errors = [];

// Handle form submission
if (isset($_POST["submit"])) {
  if (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
  }

  // Sanitize user inputs
  $usernamee = htmlspecialchars(trim($_POST["username"]), ENT_QUOTES, 'UTF-8');
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $phone = htmlspecialchars(trim($_POST["phone"]), ENT_QUOTES, 'UTF-8');
  $password = $_POST["password"];
  $passwordRepeat = $_POST["confirm_password"];

  // Password validation
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }
  if ($password !== $passwordRepeat) {
    $errors[] = "Passwords do not match.";
  }

  // Validate email and phone
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email is not valid.";
  }
  if (!preg_match("/^\+\d{1,3}\d{10}$/", $phone)) {
    $errors[] = "Phone number must include country code and be at least 10 digits long (e.g., +919876543210).";
  }
  if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
    $errors[] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
  }

  if (empty($usernamee) || empty($email) || empty($phone) || empty($password) || empty($passwordRepeat)) {
    $errors[] = "All fields are required.";
  }

  // Check if username or email already exists
  if (empty($errors)) {
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $usernamee, $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) > 0) {
        $errors[] = "Username or email already exists.";
      }
      mysqli_stmt_close($stmt);
    }
  }

  // If no errors, proceed with OTP generation and sending
  if (empty($errors)) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $_SESSION['username'] = $usernamee;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['password'] = $passwordHash;
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

    // Send OTP via email
    require 'vendor/autoload.php'; // Include PHPMailer
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
      $mail->addEmbeddedImage(dirname(__FILE__) . '/image/newsletter.jpg', 'newsletter_image');
      $mail->Body    = '<img src="cid:newsletter_image">'
        . '<br>'
        . '<h2>Email Verification Code</h2>'
        . '<p>Hello ' . htmlspecialchars($usernamee, ENT_QUOTES, 'UTF-8') . ', Enter this code on the identity verification screen:</p>'
        . '<br>'
        . "Your OTP code is: <b>$otp</b>"
        . '<br>'
        . '<p>This code will expire shortly. If you can&#8217;t find the verification code, try signing up again.</p>';
      $mail->AltBody = "Your OTP code is: $otp";


      if ($mail->send()) {
        // Optionally hash the username for added security
        $hashedUsername = hash('sha256', $usernamee); // Hash the username
        // Set a cookie for the hashed username (e.g., for 1 week)
        setcookie("SSIDU", $hashedUsername, time() + (86400 * 7), "/", "", false, true); // Secure and HttpOnly flags enabled
        $_SESSION['email'] = $email; // Store email in the session
        header("Location: verify_otp.php"); // Redirect to OTP verification page
        exit();
      } else {
        $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
      }
    } catch (Exception $e) {
      $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
    }
  }

  // Implement session timeout
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset(); // Unset session data
    session_destroy(); // Destroy session
  }
  $_SESSION['last_activity'] = time(); // Update last activity time

  // Regenerate session ID upon successful login/registration
  session_regenerate_id(true);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- ============= CSS =============  -->
  <link rel="stylesheet" href="/css/deskView.css" />

  <script src="/js/navigation.js"></script>

  <!-- ============= FONTS=============  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>

</head>

<body>

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
          <a href="index.php" class="menu-title">Home</a>
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
            <p class="menu-title">Membership</p>

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

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" autocomplete="on">
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
          <input type="password" id="password" class="password-field" name="password" autocomplete="on">
          <span class="password-toggle-icon" title="Show Password">
            <i class="fas fa-eye-slash" data-toggle="password"></i>
          </span>
        </div>
        <div class="input-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" class="password-field" name="confirm_password" autocomplete="on">
          <span class="password-toggle-icon" title="Show Password">
            <i class="fas fa-eye-slash" data-toggle="confirm_password"></i>
          </span>
        </div>
        <button type="submit" class="login-button" name="submit">Create Account</button>
        <div class="login-footer">
          <a href="login.php">Already have an account? Login</a>
        </div>
        <div class="conditons">
          <p>By clicking on create account you agree to our <a href="">Terms & Conditions</a> and that you have read out <a href="">Privacy & Policy</a></p>
        </div>
      </form>
    </div>
  </div>
  <?php
  // End output buffering and flush the output
  ob_end_flush();
  ?>



  <!-- ============= JS =============  -->
  <script src="/js/index.js"></script>

</body>

</html>