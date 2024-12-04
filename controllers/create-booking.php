<?php
include('db.php');
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($_POST) > 0) {
    if ($_POST['type'] == 1) {
        // Retrieve required POST data
        $schedule_id = mysqli_real_escape_string($conn, $_POST['schedule_id']);
        $passenger_id = mysqli_real_escape_string($conn, $_POST['passenger_id']);
        $passenger_email = mysqli_real_escape_string($conn, $_POST['passenger_email']);
        $seat_num = mysqli_real_escape_string($conn, $_POST['seat_num']);
        $payment_status = "pending";
        $total = mysqli_real_escape_string($conn, $_POST['total']);
        $routeName = mysqli_real_escape_string($conn, $_POST['routeName']);
        $book_reference = $routeName . "_00" . $schedule_id . "00" . $seat_num;

        // Get passenger type
        $passenger_type = mysqli_real_escape_string($conn, $_POST['passenger_type']);

        // Get luggage count and calculate luggage fee
        $luggage_count = isset($_POST['luggage_count']) ? intval($_POST['luggage_count']) : 0;
        $luggage_fee = isset($_POST['luggage_fee']) ? floatval($_POST['luggage_fee']) : 0.0;

        // Get discount
        $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0.0;

        // Handle file upload (if applicable)
        $uploadi_id = null;
        if (isset($_FILES['uploadi_id']) && $_FILES['uploadi_id']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $fileName = uniqid() . "_" . basename($_FILES['uploadi_id']['name']); // Unique filename
            $uploadFile = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }

            if (move_uploaded_file($_FILES['uploadi_id']['tmp_name'], $uploadFile)) {
                $uploadi_id = $uploadFile; // Save the file path
            } else {
                echo json_encode(array("statusCode" => 500, "message" => "Failed to upload ID."));
                exit;
            }
        }

        // Prepare SQL query
        $sql = "INSERT INTO `tblbook` (
                    `schedule_id`, 
                    `passenger_id`, 
                    `seat_num`, 
                    `payment_status`, 
                    `total`, 
                    `book_reference`, 
                    `passenger_type`, 
                    `luggage_count`, 
                    `luggage_fee`, 
                    `discount`, 
                    `uploadi_id`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iissssssdds", 
            $schedule_id, 
            $passenger_id, 
            $seat_num, 
            $payment_status, 
            $total, 
            $book_reference, 
            $passenger_type, 
            $luggage_count, 
            $luggage_fee, 
            $discount, 
            $uploadi_id
        );

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(array("statusCode" => 201, "message" => "Booking successful."));
        } else {
            echo json_encode(array("statusCode" => 500, "message" => "Error: " . $stmt->error));
        }

        $stmt->close();
        $conn->close();
    }
} else {
    echo json_encode(array("statusCode" => 400, "message" => "Invalid request."));
}
?>
