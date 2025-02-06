<?php
require '../../app/config/conn.php';

$conn->set_charset("utf8mb4");
$query = "SELECT id, username, email, phone, created_at, status, role FROM users";
$result = $conn->query($query);
if (!$result) {
    die("Query Error: " . $conn->error);
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=users_export.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Username', 'Email', 'Phone', 'Created At', 'Status', 'Role']);
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
$result->free();
$conn->close();
exit();
?>
