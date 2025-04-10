<?php
header('Content-Type: application/json');
$paymentMethod = isset($_GET['paymentMethod']) ? trim($_GET['paymentMethod']) : '';

if (empty($paymentMethod)) {
    echo json_encode([]);
    exit;
}

$dsn = "mysql:host=localhost;dbname=celestial_watches;charset=utf8mb4";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT 
            dn.discount_code,
            dn.discount_name,
            dn.discount_percentage,
            dn.description,
            dn.end_date,
            GROUP_CONCAT(apm.method_name SEPARATOR ', ') AS payment_methods
        FROM discount_name dn
        LEFT JOIN applicable_payment_methods apm 
            ON FIND_IN_SET(apm.method_id, dn.applicable_payment_methods)
        WHERE 
            (dn.applicable_payment_methods LIKE '%$paymentMethod%'
            OR :paymentMethod = 'credit_debit_card' AND 
               (dn.applicable_payment_methods LIKE '%credit_card%' 
                OR dn.applicable_payment_methods LIKE '%debit_card%'))
            AND dn.active_status = 1
            AND (dn.start_date IS NULL OR dn.start_date <= CURDATE())
            AND (dn.end_date IS NULL OR dn.end_date >= CURDATE())
        GROUP BY dn.id
    ";

    $stmt = $pdo->prepare($sql);
    
    // Handle credit/debit card combination
    if ($paymentMethod === 'credit_card') {
        $stmt->execute([':paymentMethod' => 'credit_debit_card']);
    } else {
        $stmt->execute([':paymentMethod' => $paymentMethod]);
    }

    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($offers);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch offers: ' . $e->getMessage()]);
}
?>