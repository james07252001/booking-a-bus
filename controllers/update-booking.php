<?php
include('db.php');
$database = new Database();
$conn = $database->getConnection();

if (count($_POST) > 0) {
    if ($_POST['type'] == 2) {
        $id = $_POST['id'];
        $payment_status = $_POST['payment_status'];
        $email = $_POST['email'];

        // Update payment status
        $sql = "UPDATE `tblbook` SET `payment_status`='$payment_status' WHERE id=$id";
        
        if (mysqli_query($conn, $sql)) {
            // Send confirmation email
            $to = $email;
            $subject = 'Booking Confirmed';
            $message = '<p>We are pleased to inform you that your booking request has been confirmed</p>';
            $message .= '<p>Please check your account. Secure screenshots or print your booking receipt and present to the bus counter.</p>';
            
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($to, $subject, $message, $headers);

            echo json_encode(array("statusCode" => 200));
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Update seat number if provided
        if (isset($_POST['seat_num'])) {
            $seat_num = $_POST['seat_num'];
            $sql_seat = "UPDATE `tblbook` SET `seat_num`='$seat_num' WHERE id=$id";
            if (!mysqli_query($conn, $sql_seat)) {
                echo "Error updating seat number: " . mysqli_error($conn);
            }
        }

        mysqli_close($conn);
    }
}
?>
