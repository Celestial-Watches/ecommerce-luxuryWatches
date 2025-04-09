<?php
header('Content-Type: application/json');
$paymentMethod = isset($_GET['paymentMethod']) ? trim($_GET['paymentMethod']) : '';

// If no payment method is provided, return an empty result.
if (empty($paymentMethod)) {
    echo json_encode([]);
    exit;
}

$dsn = "mysql:host=localhost;dbname=celestial_watches;charset=utf8mb4";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    // Throw exceptions on errors.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // For credit_card, we want to check if either 'credit_card' or 'debit_card'
    if ($paymentMethod === 'credit_card') {
        $sql = "
            SELECT discount_name, discount_percentage 
            FROM discount_name 
            WHERE 
              (FIND_IN_SET('credit_card', applicable_payment_methods) 
               OR FIND_IN_SET('debit_card', applicable_payment_methods))
              AND active_status = 1
            //   AND (start_date IS NULL OR start_date <= CURDATE())
            //   AND (end_date IS NULL OR end_date >= CURDATE())
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        // For other methods use the provided parameter.
        $sql = "
            SELECT discount_name, discount_percentage 
            FROM discount_name 
            WHERE 
              FIND_IN_SET(:paymentMethod, applicable_payment_methods)
              AND active_status = 1
            //   AND (start_date IS NULL OR start_date <= CURDATE())
            //   AND (end_date IS NULL OR end_date >= CURDATE())
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['paymentMethod' => $paymentMethod]);
    }

    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($offers);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch offers: ' . $e->getMessage()]);
}
?>
