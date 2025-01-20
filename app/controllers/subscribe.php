<?php
require '../config/conn.php'; 
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['status' => 'error', 'message' => 'Something went wrong.']; // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the posted email
        $email = trim($_POST['email']);

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address.');
        }

        // Check for existing email
        $stmt = $conn->prepare("SELECT COUNT(*) FROM subscribers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            throw new Exception('Email is already subscribed.');
        }

        // Insert the email into the database
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // Send email
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'celestialwatches69@gmail.com'; 
        $mail->Password = 'xvmjnggsmsnkavzt'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Subscribing!';
        $mail->Body = '
            <html>
            <head>
                <title>Thank You for Subscribing!</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                    h2 { color: #333; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Welcome to Celestial Watches!</h2>
                    <p>Thank you for subscribing to our newsletter.</p>
                </div>
            </body>
            </html>';
        $mail->AltBody = 'Thank you for subscribing to our newsletter!';

        $mail->send();

        $response = ['status' => 'success', 'message' => 'Thank you for subscribing!'];
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>

