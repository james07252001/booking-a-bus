<?php
    $servername = "localhost";
    $username = "u510162695_bobrs";
    $password = "1Bobrs_password";
    $dbname = "u510162695_bobrs";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>