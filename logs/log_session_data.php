<?php
session_start(); // Start the session

// Define the path to the log file
$log_file = 'session_logs.txt';

// Collect session data
$session_data = print_r($_SESSION, true); // Convert the session array to a readable string format

// Append the session data to the log file with a timestamp
file_put_contents($log_file, date('Y-m-d H:i:s') . " - Session Data:\n" . $session_data . "\n\n", FILE_APPEND);

echo "Session data has been logged.";
?>
