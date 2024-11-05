<?php
require '../config/conn.php'; // Ensure this includes the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted email
    $email = $_POST['email'];

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }

    // Check for existing email
    $stmt = $conn->prepare("SELECT COUNT(*) FROM subscribers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already subscribed']);
        exit;
    }

    // Prepare the statement to insert the email into the database
    $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
    $stmt->bind_param("s", $email);

    try {
        // Execute the statement
        $stmt->execute();
        $stmt->close();

        // Create a new PHPMailer instance
        require '../../vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'celestialwatches69@gmail.com'; // SMTP username
        $mail->Password = 'xvmjnggsmsnkavzt'; // SMTP password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Subscribing!';

        // Create the email body using the provided template
        $mail->Body = '
            <html>
            <head>
                <title>Thank You for Subscribing!</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                    h2 { color: #333; }
                    p { font-size: 16px; line-height: 1.5; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Dear Subscriber,</h2>
                    <p>Thank you for subscribing to our newsletter! We are thrilled to have you join our community of watch enthusiasts.</p>
                    <p>As a subscriber, you\'ll be the first to know about our latest collections, exclusive offers, and updates on luxury and limited edition watches. We promise to keep our emails valuable and relevant.</p>
                    <p>If you ever have any questions or feedback, feel free to reach out to us at <a href="mailto:celestialwatches69@gmail.com">Celestial Support Team</a>.</p>
                    <p>Welcome aboard, and happy watching!</p>
                    <p>Best regards,<br>Celestial Watches Team</p>
                </div>
            </body>
            </html>';

        // Alternative plain text body for email clients that don't support HTML
        $mail->AltBody = 'Dear Subscriber, Thank you for subscribing to our newsletter! We are thrilled to have you join our community of watch enthusiasts. As a subscriber, you\'ll be the first to know about our latest collections, exclusive offers, and updates on luxury and limited edition watches. We promise to keep our emails valuable and relevant. If you ever have any questions or feedback, feel free to reach out to us at celestialwatches69@gmail.com. Welcome aboard, and happy watching! Best regards, Celestial Watches Team';

        // Send the email
        $mail->send();
        echo json_encode(['status' => 'success']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
