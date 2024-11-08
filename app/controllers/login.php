<?php
define('ALLOW_ACCESS', true);
// // Set the session cookie with secure attributes
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
ob_start();

// Regenerate the session ID on every page refresh
session_regenerate_id(true);

$usernamee = isset($_COOKIE['rem_username']) ? $_COOKIE['rem_username'] : '';

// Check for the username cookie before displaying the login form
if (isset($_SESSION['user']) || isset($_COOKIE['SSIDU'])) {
  if (isset($_COOKIE['SSIDU'])) {
    $_SESSION['user'] = $_COOKIE['SSIDU'];
  }
  header("Location: ../../index.php");
  exit();
}

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
  $_SESSION['login_attempts'] = 0;
  $_SESSION['first_attempt_time'] = time();
}

require_once "../config/conn.php";

$errors = [];

// Check for session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
  session_unset(); // Unset session data
  session_destroy(); // Destroy session
}

// Update last activity time
$_SESSION['last_activity'] = time();


if (isset($_POST["login"])) {
  // Increment login attempts only if the login button is clicked
  $_SESSION['login_attempts']++;

  // Check for too many login attempts
  if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['first_attempt_time']) < 300) {
    $errors[] = "Too many login attempts. Please try again later.";
  } else {
    $usernamee = htmlspecialchars(trim($_POST["username"]), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST["password"]);

    // Validate inputs
    if (empty($usernamee)) {
      $errors[] = "Username is required";
    }
    if (empty($password)) {
      $errors[] = "Password is required";
    }

    // Auto-login if cookies are set
    if (isset($_COOKIE['temp'])) {
      $usernamee = htmlspecialchars(trim($_POST["username"]), ENT_QUOTES, 'UTF-8');
      // Check if the user exists in the database
      $sql = "SELECT * FROM users WHERE username = ?";
      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $usernamee);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
          $_SESSION["user"] = $usernamee;
          $_SESSION["LAST_ACTIVITY"] = time();
          $_SESSION["CREATED"] = time();
          $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
          $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['otp_verified'] = true;

          // Redirect to index after auto-login
          header("Location: ../../index.php");
          exit();
        }
        mysqli_stmt_close($stmt);
      }
    }

    if (empty($errors)) {
      $sql = "SELECT * FROM users WHERE username = ?";
      if ($stmt = mysqli_prepare($conn, $sql)) {
          mysqli_stmt_bind_param($stmt, "s", $usernamee);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
  
          // After verifying the password
          if ($user = mysqli_fetch_assoc($result)) {
              // Verify the password
              if (password_verify($password, $user["password"])) {
  
                  // Update the user's status to 'YES' in the database
                  $updateSql = "UPDATE users SET status = 'YES' WHERE username = ?";
                  if ($updateStmt = mysqli_prepare($conn, $updateSql)) {
                      mysqli_stmt_bind_param($updateStmt, "s", $usernamee);
                      mysqli_stmt_execute($updateStmt);
                      mysqli_stmt_close($updateStmt);
                  }
  
                  // Check if user is admin
                  if ($user["role"] === "admin") { // Assuming 'role' column exists
                      $_SESSION["admin"] = true; // Set admin session
                      // Regenerate session ID to prevent session fixation
                      session_regenerate_id(true);
                      // Set session variables for admin
                      $_SESSION["user"] = $usernamee;
                      $_SESSION["user_id"] = $user['id']; // Corrected from $row to $user
                      $_SESSION["LAST_ACTIVITY"] = time();
                      $_SESSION["CREATED"] = time();
                      $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                      $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                      $_SESSION['otp_verified'] = true;
  
                      // Redirect to admin verification page
                      header("Location: ../../index.php");
                      exit(); // Ensure script termination here
                  }
  
                  // Regenerate session ID to prevent session fixation
                  session_regenerate_id(true);
  
                  // Set session variables and initialize session management
                  $_SESSION["user"] = $usernamee;
                  $_SESSION["user_id"] = $user['id']; // Corrected from $row to $user
                  $_SESSION["LAST_ACTIVITY"] = time();
                  $_SESSION["CREATED"] = time();
                  $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                  $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                  $_SESSION['otp_verified'] = true;
  
                  if (isset($_POST['remember'])) {
                      // Set cookie for 1 hour
                      setcookie('temp', '1', time() + 3600, '/', '', true, true);
                  } else {
                      setcookie('temp', '', time() - 3600, '/', '', true, true);
                  }
  
                  // Redirect to the main index page
                  header("Location: ../../index.php");
                  exit(); // Ensure script termination here
              } else {
                  $errors[] = "Incorrect password";
              }
          } else {
              $errors[] = "Username not found";
          }
  
          mysqli_stmt_close($stmt);
      } else {
          $errors[] = "Database query failed";
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
  <script src="../../src/assets/js/navigation.js"></script>

  <!-- ============= CSS =============  -->
  <link rel="stylesheet" href="../../src/assets/css/deskView.css" />
  <link rel="stylesheet" href="../../src/assets/css/google-header.css">

  <!-- ============= FONTS=============  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <style>
    /* Alert Box Styles */
    .alert-box {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      /* Transparent dark background */
      display: none;
      /* Hidden by default */
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    /* Alert Content Styles */
    .alert-content {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
      width: 300px;
      /* Adjust as needed */
      display: flex;
      flex-direction: column;
      align-items: center;
      /* Center the content */
    }

    /* Button Styles */
    #alert-ok-btn {
      background-color: #000;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      margin-top: 15px;
      align-self: center;
      /* Center the button */
    }

    #alert-ok-btn:hover {
      background-color: #444;
    }

    .logg-button {
      display: none;
    }
  </style>

</head>

<body>

 

  <!-- ===================================================== HEADER =====================================================  -->

  <?php include '../../PHP/components/navbar.php';
  ?>

  <!-- ===================================================== HEADER END =====================================================  -->

  <!--    /***************************************************
        * *** LOGIN PAGE START*** *
        ***************************************************/    -->


  <div class="login-container">
    <div class="login-box">
      <div id="custom-alert" class="alert-box">
        <div class="alert-content">
          <p>Please enter your username before proceeding to forgot password.</p>
          <button id="alert-ok-btn">OK</button>
        </div>
      </div>
      <h1>Welcome to Celestial Watches</h1>
      <!-- Display errors here -->
      <?php
      if (!empty($errors)) {
        foreach ($errors as $error) {
          echo "<div class='alert alert-danger'>$error</div>";
        }
      }
      ?>
      <form id="login-form" action="login.php" method="post">
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usernamee, ENT_QUOTES); ?>">
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" class="password-field" name="password">
          <span class="password-toggle-icon" title="Show Passowrd"><i class="fas fa-eye-slash"></i></span>
        </div>
        <div class="input-group remember-me-group">
          <input type="checkbox" id="remember" name="remember" value="1" class="rem" <?php echo (isset($_COOKIE['temp']) && $_COOKIE['temp'] === '1') ? 'checked' : ''; ?>>
          <label for="remember">Remember Me</label>
        </div>
        <button type="submit" class="login-button" value="login" name="login">Login</button>
        <div class="login-footer">
          <a href="forgot_password.php" id="forgot-password-link">Forgot Password?</a>
          <a href="signin.php">Create Account</a>
        </div>
      </form>
    </div>
  </div>

  <?php include '../../PHP/components/footer.php' ?>

  <script>
    document.getElementById('forgot-password-link').addEventListener('click', function(event) {
      var username = document.getElementById('username').value.trim();

      if (username === '') {
        event.preventDefault();

        // Show custom alert box
        var customAlert = document.getElementById('custom-alert');
        customAlert.style.display = 'flex'; // Make it visible

        // Close the alert box when the OK button is clicked
        document.getElementById('alert-ok-btn').addEventListener('click', function() {
          customAlert.style.display = 'none'; // Hide the alert box
        });
      }
    });
  </script>

  <?php
  ob_end_flush();
  // var_dump($_SESSION); FOR LOGG PURPOSE
  ?>



  <!--    /***************************************************
        * *** LOGIN PAGE  END *** *
        ***************************************************/    -->


  <script src="../../src/assets/js/cookie-monitor.js"></script>
  <script src="../../src/assets/js/index.js"></script>
  <script src="../../src/assets/js/currency-language.js"></script>
</body>

</html>