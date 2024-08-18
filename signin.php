<?php
 date_default_timezone_set('Asia/Kolkata');
session_start();
ob_start();
if (isset($_SESSION["user"])) {
  header("Location: index.php");
  exit(); // Stop further execution after redirection
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



  <!-- ===================================================== HEADER END =====================================================  -->





  <!--    /***************************************************
        **** SIGN IN CSS START****
        ***************************************************/    -->


  <div class="login-container">
    <div class="login-box signin-box">
    <?php
    $errors = array();

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
            array_push($errors, "All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
        }

        if (strlen($phone) !== 10 || !preg_match("/^\d{10}$/", $phone)) {
            array_push($errors, "Phone number must contain exactly 10 digits");
        }

        if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[^\w]/", $password)) {
            array_push($errors, "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character");
        }

        if ($password !== $passwordRepeat) {
            array_push($errors, "Passwords do not match");
        }

        require_once "conn.php";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable error reporting

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                array_push($errors, "Email already exists!");
            }
        } else {
            array_push($errors, "Database preparation failed: " . mysqli_error($conn));
        }

        // If no errors, insert the user into the database
        if (empty($errors)) {
            $status = 'NO'; // Set default status for new users

            $sql = "INSERT INTO users (username, email, phone, password, status) VALUES (?, ?, ?, ?, ?)";
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssss", $usernamee, $email, $phone, $passwordHash, $status);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success'>Account created successfully. You can now log in.</div>";
                    // Redirect to the login page after a short delay
                    echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 2000);</script>";
                    exit();
                } else {
                    array_push($errors, "Something went wrong. Please try again later.");
                }
            } else {
                array_push($errors, "Database preparation failed: " . mysqli_error($conn));
            }
        }
    }
    
    ?>
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
          <input type="text" id="username" name="username">
        </div>
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email">
        </div>
        <div class="input-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" pattern="[0-9]{10}"> <br>
          <?php
          // Display phone number errors here
          if (!empty($errors)) {
            foreach ($errors as $error) {
              if (strpos($error, "Phone number") !== false) {
                echo "<div class='alert alert-danger'>$error</div>";
              }
            }
          }
          ?>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password">
        </div>
        <?php
        // Display password errors here
        if (!empty($errors)) {
          foreach ($errors as $error) {
            if (strpos($error, "Password") !== false) {
              echo "<div class='alert alert-danger'>$error</div>";
            }
          }
        }
        ?>
        <div class="input-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password">
        </div>
        <button type="submit" class="login-button" name="submit">Create Account</button>
        <div class="login-footer">
          <a href="login.php">Already have an account? Login</a>
        </div>
      </form>
    </div>
  </div>



  <!--    /***************************************************
        **** SIGN IN CSS END ****
        ***************************************************/    -->


  <script src="/js/index.js"></script>
</body>

</html>