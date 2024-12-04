<?php
class Book
{
    private $conn;
    private $table_name = "tblbook";

    // Database Connection 
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function checkSeat($schedule_id, $seat_num)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE schedule_id = '$schedule_id' AND seat_num = '$seat_num'";
        $result = $this->conn->query($query);
        $data = $result->fetch_assoc();
        return $data;
    }

    public function create($schedule_id, $passenger_id, $seat_num, $payment_status = 'pending', $total, $passenger_type, $luggage_count) {
        // Updated SQL to include luggage_count
        $sql = "INSERT INTO " . $this->table_name . " (schedule_id, passenger_id, seat_num, payment_status, total, passenger_type, luggage_count) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($this->conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: booked.php?schedule_id=" . $schedule_id . "&error=stmtfailed");
            exit();
        }

        // Updated parameter binding to include luggage_count
        mysqli_stmt_bind_param($stmt, "ssssssi", $schedule_id, $passenger_id, $seat_num, $payment_status, $total, $passenger_type, $luggage_count);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        echo json_encode(array("statusCode" => 201));
        header("location: booked.php?schedule_id=" . $schedule_id . "&success=book");
        exit();
    }

    public function getPassengersBooking($passenger_id)
    {
        $query = "SELECT *, b.id as book_id FROM " . $this->table_name . " b INNER JOIN tblschedule s ON b.schedule_id = s.id INNER JOIN tblroute r ON s.route_id = r.id WHERE passenger_id = '$passenger_id' ORDER BY book_date DESC";
        $result = $this->conn->query($query);
        
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAll()
    {
        $query = "SELECT *, b.id as book_id FROM " . $this->table_name . " b INNER JOIN tblschedule s ON b.schedule_id = s.id INNER JOIN tblroute r ON s.route_id = r.id ORDER BY book_date DESC";
        $result = $this->conn->query($query);
        
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
