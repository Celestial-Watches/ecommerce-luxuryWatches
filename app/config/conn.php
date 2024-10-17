<?php
date_default_timezone_set('Asia/Kolkata');
    $servername = "localhost";
    $username = "root";
    $password = ""; // Your database password
    $dbname = "celestial_watches";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }     
    $conn->query("SET time_zone = '+05:30';");  
?>