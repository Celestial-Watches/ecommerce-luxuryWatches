<?php
require '../config/conn.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['status' => 'error', 'message' => 'Something went wrong.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = trim($_POST['email']);

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
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f8f9fa;
            }
            .email-container {
                max-width: 600px;
                margin: auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .email-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .email-header h2 {
                font-size: 30px;
                color: #333;
                margin: 0;
            }
            .email-header p {
                font-size: 16px;
                color: #888;
            }
            .email-body {
                font-size: 16px;
                color: #555;
                line-height: 1.6;
            }
            .cta-button {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                font-size: 16px;
                padding: 12px 25px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
            .cta-button:hover {
                background-color: #45a049;
            }
            .footer {
                text-align: center;
                font-size: 14px;
                color: #888;
                margin-top: 30px;
            }
            .footer a {
                color: #4CAF50;
                text-decoration: none;
            }
            .footer a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h2>Welcome to Celestial Watches!</h2>
                <p>Thank you for subscribing to our newsletter.</p>
            </div>
            <div class="email-body">
                <p>We’re excited to have you on board! By subscribing, you’ll receive updates on the latest arrivals, special offers, and exclusive discounts.</p>
                <p>Stay tuned for exciting updates about our premium timepieces.</p>
                <a href="#" class="cta-button">Explore Our Collection</a>
            </div>
            <div class="footer">
                <p>Follow us on social media for more updates:</p>
                <p>
                    <a href="#">Facebook</a> | <a href="#">Instagram</a> | <a href="#">Twitter</a>
                </p>
                <p>&copy; 2025 Celestial Watches. All rights reserved.</p>
            </div>
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
