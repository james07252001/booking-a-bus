<?php
// Include database configuration
include_once '../config/database.php';

// Check if the POST request contains 'bus_num'
if (isset($_POST['bus_num'])) {
    $bus_num = mysqli_real_escape_string($conn, $_POST['bus_num']);

    // Query to check if the bus number exists in the database
    $query = "SELECT COUNT(*) AS count FROM tblbus WHERE bus_num = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $bus_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Prepare the response
    $response = [
        'exists' => ($row['count'] > 0) ? true : false
    ];

    // Return the JSON response
    echo json_encode($response);
} else {
    // If no bus_num is provided, return an error response
    echo json_encode(['exists' => false, 'error' => 'No bus number provided.']);
}

// Close the connection
$conn->close();
