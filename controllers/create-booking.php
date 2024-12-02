<?php
include('db.php');
$database = new Database();
$conn = $database->getConnection();

if (count($_POST) > 0) {
    if ($_POST['type'] == 1) {
        $schedule_id = $_POST['schedule_id'];
        $passenger_id = $_POST['passenger_id'];
        $passenger_email = $_POST['passenger_email'];
        $seat_num = $_POST['seat_num'];
        $payment_status = "pending";
        $total = $_POST['total'];
        $routeName = $_POST['routeName'];
        $book_reference = $routeName . "_00" . $schedule_id . "00" . $seat_num;

        // Get passenger type
        $passenger_type = $_POST['passenger_type'];

        // Get luggage count
        $luggage_count = isset($_POST['luggage_count']) ? intval($_POST['luggage_count']) : 0;

        // Update SQL query to include luggage_count
        $sql = "INSERT INTO `tblbook` (`schedule_id`, `passenger_id`, `seat_num`, `payment_status`, `total`, `book_reference`, `passenger_type`, `luggage_count`) 
                VALUES ('$schedule_id', '$passenger_id', '$seat_num', '$payment_status', '$total', '$book_reference', '$passenger_type', '$luggage_count')";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode" => 201));
        } else {
            echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
        }
        mysqli_close($conn);
    }
}
?>
