<?php

// Start the session to access session variables.
// Check if the session variable otp_expiry exists:
// If it exists, set otp_expiry_time to the value of the session variable.
// If it does not exist, set otp_expiry_time to 0.
// Get the current timestamp using the time() function.
// Calculate the remaining time by subtracting the current time from otp_expiry_time. Ensure the result is non-negative by using max(0, otp_expiry_time - current_time).
// Output the remaining time as plain text.

session_start();

// Calculate remaining time
$otp_expiry_time = $_SESSION['otp_expiry'] ?? 0;
$remaining_time = max(0, $otp_expiry_time - time());

// Return the remaining time as plain text
echo $remaining_time;
?>