<?php
require '../../app/config/conn.php';

$queryLogss = "SELECT * FROM user_activity_logs"; 
$resultLogss = $conn->query($queryLogss);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=activity_logs.csv');
$outputLogs = fopen('php://output', 'w');
fputcsv($outputLogs, ['ID', 'User', 'Activity Type', 'Description', 'IP Address', 'User Agent', 'Timestamp']);
while ($rowLogs = $resultLogss->fetch_assoc()) {
    fputcsv($outputLogs, $rowLogs);
}

fclose($outputLogs);
exit();
?>
