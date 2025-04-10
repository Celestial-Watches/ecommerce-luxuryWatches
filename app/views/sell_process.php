<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: selling.php");
    exit();
}


include '../config/conn.php';

// Create the sell_process table if it does not exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS sell_process (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exchange_watch VARCHAR(255),
    sell_method VARCHAR(50), 
    original_box VARCHAR(10),
    unworn VARCHAR(10),
    original_papers VARCHAR(10),
    purchased_from_cw VARCHAR(10),
    watch_age VARCHAR(100),
    expected_price DECIMAL(10,2),
    request_condition VARCHAR(50),
    production_year VARCHAR(10),
    additional_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createTableSQL);

// Fetch and sanitize form data
$exchange_watch    = isset($_POST['exchange_watch']) ? trim($_POST['exchange_watch']) : '';
$sell_method       = isset($_POST['sell_method']) ? trim($_POST['sell_method']) : '';
$original_box      = isset($_POST['original_box']) ? trim($_POST['original_box']) : '';
$unworn            = isset($_POST['unworn']) ? trim($_POST['unworn']) : '';
$original_papers   = isset($_POST['original_papers']) ? trim($_POST['original_papers']) : '';
$purchased_from_cw = isset($_POST['purchased_from_cw']) ? trim($_POST['purchased_from_cw']) : '';
$watch_age         = isset($_POST['watch_age']) ? trim($_POST['watch_age']) : '';
$expected_price    = isset($_POST['expected_price']) ? trim($_POST['expected_price']) : '0';
$request_condition = isset($_POST['request_condition']) ? trim($_POST['request_condition']) : '';
$production_year   = isset($_POST['production_year']) ? trim($_POST['production_year']) : '';
$additional_info   = isset($_POST['additional_info']) ? trim($_POST['additional_info']) : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Prepare and bind insert query
$stmt = $conn->prepare("
    INSERT INTO sell_process 
    (exchange_watch, sell_method, original_box, unworn, original_papers, purchased_from_cw, watch_age, expected_price, request_condition, production_year, additional_info, user_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "sssssssssssi",
    $exchange_watch,
    $sell_method,
    $original_box,
    $unworn,
    $original_papers,
    $purchased_from_cw,
    $watch_age,
    $expected_price,
    $request_condition,
    $production_year,
    $additional_info,
    $user_id
);
$stmt->execute();

// Get the auto-generated ID
$sell_request_id = $stmt->insert_id;

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

        $userName  = isset($_SESSION['user']) ? $_SESSION['user'] : 'Customer';

        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmation of Your Request';
        $mail->Body    = "
        Dear {$userName},<br><br>
        Thank you for your interest!<br>
        We will now evaluate your watch based on the pictures and details provided. We will revert shortly to let you know whether we can offer to purchase your watch from you and arrange the shipping.<br>
        Valuations are based on the current market prices, driven by supply and demand.<br>
        If you have not provided pictures of the watch, box, and papers (if available), please send them along as soon as possible to help us respond swiftly.<br><br>
        Best regards,<br>
        Celestial Watches.
    ";

        $mail->send();
        header("Location: sell_thank_you.php?sell_request_id=" . urlencode($sell_request_id));
        exit();
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
