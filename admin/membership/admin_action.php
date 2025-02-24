<?php
session_start();
session_regenerate_id(true);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ob_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    // Redirect to login if not authenticated
    header("Location: ../../app/controllers/login.php");
    exit();
}

require '../../vendor/autoload.php';

require_once '../../app/config/conn.php';

define('APP_URL', 'http://localhost:3000');
define('LOGIN_URL', APP_URL . '/app/controllers/login.php');
define('BENEFITS_URL', APP_URL . '/app/views/membership.php');
define('STORE_URL', APP_URL . '/app/views/productLanding.php');

$action = $_GET['action'] ?? '';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id && in_array($action, ['approve', 'reject', 'terminate'])) {
    $stmt = $conn->prepare("SELECT * FROM membership_applications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $application = $stmt->get_result()->fetch_assoc();

    if ($action === 'terminate') {
        $stmtDel = $conn->prepare("DELETE FROM membership_applications WHERE id = ?");
        $stmtDel->bind_param("i", $id);
        $stmtDel->execute();
        $status = 'terminated';
    } else {
        $stmtUp = $conn->prepare("UPDATE membership_applications 
                                  SET status = ?, reviewed_at = NOW() 
                                  WHERE id = ?");
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $stmtUp->bind_param("si", $status, $id);
        $stmtUp->execute();
    }

    sendStatusEmail($application, $status);
    header("Location: view-member.php");
    exit();
}


function sendStatusEmail($application, $status)
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

        // Email content
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($application['email']);
        $mail->isHTML(true);

        // Set email content
        $mail->Subject = ($status === 'approved') ? 'Membership Application Approved' : 'Membership Application Update';

        $emailBody = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Celestial Watches Membership Notification</title>
            <style>
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                
                .header {
                    background: #002f6c;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                
                .header img {
                    max-width: 150px;
                    margin-bottom: 10px;
                }
                
                .content {
                    background: #f8f9fa;
                    padding: 25px;
                    border-radius: 4px;
                }
                
                .section {
                    margin-bottom: 25px;
                }
                
                .cta-button {
                    display: inline-block;
                    background: #007bff;
                    color: white;
                    padding: 12px 25px;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: bold;
                    margin-top: 10px;
                }
                
                .cta-button:hover {
                    background: #0056b3;
                }
                
                .footer {
                    background: #333;
                    color: white;
                    padding: 15px;
                    text-align: center;
                    font-size: 0.9em;
                    margin-top: 25px;
                }
                
                .faq {
                    margin-top: 20px;
                    padding: 15px;
                    background: #e9ecef;
                    border-radius: 4px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <img src="/celestial-logo.png" alt="Celestial Watches Logo">
                    <h1>Membership Notification</h1>
                </div>
                
                <div class="content">
                    <div class="section">
                        <h2>Dear ' . $application['full_name'] . '</h2>
                        ' . ($status === 'approved' ? '
                        <h3>Your Membership Has Been Approved!</h3>
                        <p>Congratulations! We\'re delighted to welcome you as a member of Celestial Watches. Your exclusive benefits will be available immediately.</p>
                        <a href="' . LOGIN_URL . '" class="cta-button">Activate Your Membership</a>
                        ' : '
                        <h3>Application Update</h3>
                        <p>After careful review, we regret to inform you that your application wasn\'t approved at this time. We appreciate your interest in our community.</p>
                        ') . '
                    </div>
                    
                    <div class="section">
                        <h3>Next Steps</h3>
                        ' . ($status === 'approved' ? '
                        <p>You will receive your personalized membership credentials in a separate email within 24 hours. Please review our <a href="' . BENEFITS_URL . '">Membership Benefits</a> for more details.</p>
                        ' : '
                        <p>We encourage you to reapply in the future. Consider exploring our <a href="' . STORE_URL . '">Luxury Collection</a> in the meantime.</p>
                        ') . '
                    </div>
                    <div class="faq">
                        <h3>Frequently Asked Questions</h3>
                        <p><strong>When will I receive my credentials?</strong><br>
                        ' . ($status === 'approved' ? 'Your membership details will be sent within 24 hours.' : 'Credentials are only provided to approved members.') . '</p>
                        
                        <p><strong>Can I reapply?</strong><br>
                        ' . ($status === 'approved' ? 'Yes, you can upgrade your membership at any time.' : 'We accept reapplications after 6 months.') . '</p>
                    </div>
                </div>
                
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' Celestial Watches. All rights reserved.</p>
                    <p>Follow us: 
                        <a href="https://www.instagram.com/celestialwatches" target="_blank">Instagram</a> | 
                        <a href="https://www.facebook.com/celestialwatches" target="_blank">Facebook</a>
                    </p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $emailBody;
        $mail->AltBody = strip_tags($emailBody);

        $mail->send();
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
    }
}
