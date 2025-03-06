<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: selling.php");
  exit();
}

require_once '../config/conn.php';


// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $exchange_watch    = trim($_POST['exchange_watch'] ?? '');
  $original_box      = isset($_POST['original_box']) ? trim($_POST['original_box']) : '';
  $unworn            = isset($_POST['unworn']) ? trim($_POST['unworn']) : '';
  $original_papers   = isset($_POST['original_papers']) ? trim($_POST['original_papers']) : '';
  $purchased_from_ww = isset($_POST['purchased_from_ww']) ? trim($_POST['purchased_from_ww']) : '';
  $watch_age         = trim($_POST['watch_age'] ?? '');
  $expected_price    = trim($_POST['expected_price'] ?? '');
  $request_condition = trim($_POST['request_condition'] ?? '');
  $production_year   = trim($_POST['production_year'] ?? '');
  $additional_info   = trim($_POST['additional_info'] ?? '');
  $buy_watch         = trim($_POST['buy_watch'] ?? '');

  // Prepare an SQL statement to insert the form data into the "requestss" table.
  $sql = "INSERT INTO requestss 
      (user_id, exchange_watch, original_box, unworn, original_papers, purchased_from_ww, watch_age, expected_price, request_condition, production_year, additional_info, buy_watch) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    die("Prepare failed: " . $conn->error);
  }

  $user_id = $_SESSION['user_id'];

  $stmt->bind_param(
    "isssssssssss",
    $user_id,
    $exchange_watch,
    $original_box,
    $unworn,
    $original_papers,
    $purchased_from_ww,
    $watch_age,
    $expected_price,
    $request_condition,
    $production_year,
    $additional_info,
    $buy_watch
  );

  // Execute the statement and check for success
  if ($stmt->execute()) {
    $sql_email = "SELECT email FROM users WHERE id = ?";
    $stmt_email = $conn->prepare($sql_email);
    if (!$stmt_email) {
      die("Prepare failed: " . $conn->error);
    }
    $stmt_email->bind_param("i", $user_id);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();
    if ($result_email && $row_email = $result_email->fetch_assoc()) {
      $email = $row_email['email'];
    } else {
      $email = "celestialwatches69@gmail.com";
    }
    $stmt_email->close();

    require '../../vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'celestialwatches69@gmail.com';
        $mail->Password   = 'xvmjnggsmsnkavzt';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
      $mail->addAddress($email);

      // Content
      $mail->isHTML(true);
      $mail->Subject = 'Confirmation of Your Request';
      $mail->Body    = '
      <html>
      <head>
        <style>
          body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
          .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; padding: 30px; border: 1px solid #e1e1e1; }
          .header { font-size: 24px; font-weight: bold; color: #333333; margin-bottom: 20px; }
          .content { font-size: 16px; line-height: 1.5; color: #555555; }
          .footer { margin-top: 30px; font-size: 12px; color: #aaaaaa; text-align: center; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">Request Confirmation</div>
          <div class="content">
            Dear Customer,<br><br>
            Thank you for submitting your request. Your request has been received and is currently under consideration. We will update you as soon as possible.<br><br>
            Best Regards,<br>
            The Customer Support Team
          </div>
          <div class="footer">
            This is an automated message, please do not reply.
          </div>
        </div>
      </body>
      </html>';
      $mail->AltBody = 'Dear Customer, Thank you for submitting your request. Your request has been received and is currently under consideration. We will update you as soon as possible. Best Regards, The Customer Support Team';

      $mail->send();
      
      header("Location: my_requests.php");
      exit();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    echo "Error: " . $stmt->error;
  }

  // Clean up
  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request method.";
}
?>
