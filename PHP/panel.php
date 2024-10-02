<?php
session_start();

require_once "../conn.php"; 

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || !isset($_SESSION['admin'])) {
    header("Location: ../login.php"); 
    exit();
}

// Check if the admin has already authenticated for this session
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // If already authenticated, redirect to the panel
    header("Location: ../panel.php");
    exit();
}

// Check if the admin has already authenticated for this session
$current_time = time(); // Get the current timestamp
$timeout_duration = 1800; // 30 minutes in seconds

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
  // Check if the session is still valid
  if (isset($_SESSION['last_auth_time']) && ($current_time - $_SESSION['last_auth_time']) < $timeout_duration) {
      // If already authenticated and within timeout duration, redirect to the panel
      header("Location: ../panel.php");
      exit();
  } else {
      // If the session has timed out, require password authentication
      unset($_SESSION['authenticated']); // Remove the authenticated status
  }
}

// Check if the password has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = trim($_POST["password"]);

    // Fetch the admin's password from the admin_login table
    $sql = "SELECT password FROM admin_login WHERE username = ?"; // Adjust the column name if necessary
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']); // Assuming the session variable holds the admin username
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashed_password);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Verify the entered password
        if (password_verify($entered_password, $hashed_password)) {

          
            // Password is correct, set session variable and grant access to the admin panel
            $_SESSION['authenticated'] = true; // Set authenticated to true here
            $_SESSION['last_auth_time'] = $current_time; // Store the current time
            header("Location: ../panel.php");
            exit();
        } else {
            $error_message = "Incorrect password. Access denied.";
        }
    } else {
        $error_message = "Database query failed.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Access</title>
    <script type="text/javascript" src="../js/navigation.js"></script>
    <style>
        * { box-sizing: border-box; }
@import url('https://fonts.googleapis.com/css?family=Rubik:400,500&display=swap');


body {
  font-family: 'Rubik', sans-serif;
  background-color: #000;
}

.container {
  display: flex;
  height: 100vh;
}

.left {
  overflow: hidden;
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  justify-content: center;
  animation-name: left;
  animation-duration: 1s;
  animation-fill-mode: both;
  animation-delay: 1s;
}

.right {
  flex: 1;
  background-color: black;
  transition: 1s;
  background-image: url(/image/pannelBanner.jpg);
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.header > h2 {
  margin: 0;
  color: #4f46a5;
}

.header > h4 {
  margin-top: 10px;
  font-weight: normal;
  font-size: 15px;
  color: white;
}

.form {
  max-width: 80%;
  display: flex;
  flex-direction: column;
}

.form > p {
  text-align: right;
}

.form > p > a {
  color: #000;
  font-size: 14px;
}

.form-field {
  height: 46px;
  padding: 0 16px;
  border: 2px solid #ddd;
  border-radius: 4px;
  font-family: 'Rubik', sans-serif;
  outline: 0;
  transition: .2s;
  margin-top: 20px;
}

.form-field:focus {
  border-color: #0f7ef1;
}



.form > button {
  padding: 12px 10px;
    border: 0;
    background: linear-gradient(to right, #de48b5 0%,#0097ff 100%); 
    border-radius: 3px;
    margin-top: 10px;
    color: #fff;
    letter-spacing: 1px;
    font-family: 'Rubik', sans-serif;
    cursor: pointer;
}


.animation {
  animation-name: move;
  animation-duration: .4s;
  animation-fill-mode: both;
  animation-delay: 2s;
}

.a1 {
  animation-delay: 2s;
}

.a2 {
  animation-delay: 2.1s;
}

.a3 {
  animation-delay: 2.2s;
}

.a4 {
  animation-delay: 2.3s;
}

.a5 {
  animation-delay: 2.4s;
}

.a6 {
  animation-delay: 2.5s;
}

@keyframes move {
  0% {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-40px);
  }

  100% {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
}

@keyframes left {
  0% {
    opacity: 0;
    width: 0;
  }

  100% {
    opacity: 1;
    padding: 20px 40px;
    width: 440px;
  }
}
    </style>
</head>

<body>
    <!-- <div>
    <h1>Admin Panel Access</h1>
    
    <form action="panel.php" method="post">
        <label for="password">Enter Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Submit</button>
    </form>

    <form action="logout.php" method="post" style="margin-top: 20px;">
        <button type="submit" name="logout">Logout</button>
    </form>
    </div> -->

    <?php if (isset($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>
    <form action="panel.php" method="post">
    <div class="container">
        <div class="left">
            <div class="header">
                <h2 class="animation a1">Welcome <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
                <h4 class="animation a2">Please enter your security passoword before moving forward</h4>
            </div>
            <div class="form">
                <input type="password" class="form-field animation a4" placeholder="Password" name="password" required>
                <button class="animation a6">SUBMIT</button>
                <button class="animation a6" id="logoutButton">LOGOUT</button>
            </div>
        </div>
        <div class="right"></div>
    </div>
    </form>
</body>

<script>
        document.getElementById("logoutButton").addEventListener("click", function() {
            // Create a form element
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "../logout.php"; // Specify the logout URL

            // Create a hidden input for logout
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "logout"; // Name of the parameter to be sent
            input.value = "true"; // Value to be sent

            // Append the input to the form
            form.appendChild(input);

            // Append the form to the body (not visible to the user)
            document.body.appendChild(form);

            // Submit the form
            form.submit();
        });
    </script>

</html>