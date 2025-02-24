<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../app/config/conn.php';
    require '../../vendor/autoload.php';

    // Sanitize and validate inputs
    $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $referralCode = filter_input(INPUT_POST, 'referralCode', FILTER_SANITIZE_STRING);
    $background = filter_input(INPUT_POST, 'background', FILTER_SANITIZE_STRING);
    $motivation = filter_input(INPUT_POST, 'motivation', FILTER_SANITIZE_STRING);

    if (!$email) {
        die("Invalid email format");
    }

    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        die("Invalid phone number");
    }

    // Use prepared statements for secure data insertion
    $stmt = $conn->prepare("INSERT INTO membership_applications 
        (full_name, email, phone, referral_code, background, motivation, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");

    if ($stmt) {
        $stmt->bind_param("ssssss", $fullName, $email, $phone, $referralCode, $background, $motivation);

        if ($stmt->execute()) {
            // Send confirmation email
            sendConfirmationEmail($email, $fullName);
            header("Location: ../views/membership.php");
            exit();
        } else {
            die("Error executing query: " . $stmt->error);
        }

        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

function sendConfirmationEmail($toEmail, $name)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'celestialwatches69@gmail.com';
        $mail->Password = 'xvmjnggsmsnkavzt';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Membership Application Received';
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                    .content { padding: 30px; }
                    .signature { margin-top: 30px; color: #666; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <img src='cid:logo_cid' alt='Celestial Watches' width='200'>
                </div>
                <div class='content'>
                    <h2>Dear $name,</h2>
                    <p>Thank you for applying to the Celestial Watches Exclusive Membership Program.</p>
                    <p>Our team is reviewing your application and will respond within 3-5 business days.</p>
                    <p>For any inquiries, please contact our membership team at membership@celestialwatches.com</p>
                    <div class='signature'>
                        Best Regards,<br>
                        The Celestial Watches Team
                    </div>
                </div>
            </body>
            </html>
        ";

        // Attach embedded image
        $mail->addEmbeddedImage('../../celestial-logo.png', 'logo_cid');

        $mail->send();
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
    }
}

$conn->close();
?>
