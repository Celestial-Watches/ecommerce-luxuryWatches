<?php
    $servername = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbname = "celestial_watches";
    
    // Create connection
    $conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);
    
    // Check connection
    if (!$conn) {
      die("Connection failed: ". mysqli_error($conn));
    }
    echo "Connected successfully";
?>