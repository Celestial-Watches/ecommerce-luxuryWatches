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

  unset($_SESSION['user']); // User hasn't verified OTP yet, clear the session variable or reset it

  // Optionally, clear the cookies if you're storing the user info in cookies
  setcookie("SSIDU", "", time() - 3600, "/", true, true); // Expire the cookie
}

require_once "../config/conn.php"; // Include database connection

// Check if user is already logged in
if (isset($_SESSION["user"])) {
  header("Location: ../../index.php");
  exit(); // Stop further execution after redirection
}

// Ensure CSRF token is set
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize errors array
$errors = [];

// Handle form submission
if (isset($_POST["submit"])) {
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
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
    require '../../vendor/autoload.php'; // Include PHPMailer
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
      $mail->Subject = 'Your OTP Code Celestial Watches';
      $mail->addEmbeddedImage(dirname(__FILE__) . '/../../src/assets/image/newsletter.jpg', 'newsletter_image');
      $mail->Body = '
<div style="font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; padding: 20px;">
    <div style="text-align: center;">
        <img src="cid:newsletter_image" alt="Celestial Watches" style="max-width: 100%; height: auto; margin-bottom: 20px;">
    </div>
    <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #000; text-align: center;">Email Verification Code</h2>
        <p style="font-size: 16px; text-align: center;">
            Hello <strong>' . htmlspecialchars($usernamee, ENT_QUOTES, 'UTF-8') . '</strong>,
        </p>
        <p style="font-size: 16px; text-align: center;">
            Thank you for choosing <strong>Celestial Watches</strong>! To complete your email verification, please enter the following code on the identity verification screen:
        </p>
        <p style="font-size: 24px; font-weight: bold; color: #000; text-align: center; margin: 20px 0;">
            Your OTP Code: <strong>' . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . '</strong>
        </p>
        <p style="font-size: 16px; text-align: center; color: #555;">
            This code is valid for 5 minutes. If you didn’t request this, please disregard this email.
        </p>
    </div>
    <div style="margin-top: 30px; text-align: center;">
        <p style="font-size: 14px; color: #888;">Best regards,</p>
        <p style="font-size: 14px; color: #888;"><strong>Celestial Watches Team</strong></p>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <a href="https://www.celestialwatches.com" style="font-size: 14px; color: white; background-color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Visit Celestial Watches
        </a>
    </div>
</div>';
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
  <link rel="stylesheet" href="../../src/assets/css/deskView.css" />
  <link rel="stylesheet" href="../../src/assets/css/google-header.css">


  <!-- ============= JS =============  -->
  <script src="../../src/assets/js/scroll-animation.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
  <script src="../../src/assets/js/navigation.js"></script>

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

<style>
  .logg-button{
    display: none;
  }
</style>
</head>

<body>

  <?php include '../../PHP/components/loader.php';
  ?>
  <!-- ============= HEADER =============  -->
  <?php include '../../PHP/components/navbar.php';
  ?>

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
  <script src="../../src/assets/js/index.js"></script>
  <script src="../../src/assets/js/currency-language.js"></script>

</body>

</html>