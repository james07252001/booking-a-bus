<?php
header('Content-Type: application/json');

// Database connection
$host = '127.0.0.1';
$username = 'u510162695_bobrs';
$password = '1Bobrs_password';
$dbname = 'u510162695_bobrs';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['statusCode' => 500, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Get bus number from POST request
$bus_num = isset($_POST['bus_num']) ? $conn->real_escape_string($_POST['bus_num']) : '';

if (empty($bus_num)) {
    echo json_encode(['statusCode' => 400, 'message' => 'Bus number is required']);
    exit();
}

// Check if bus number exists in the database
$sql = "SELECT id FROM tblbus WHERE bus_num = '$bus_num' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}

// Close connection
$conn->close();
?>
