<?php
    function generateReference($conn, $schedule_id, $seat_num){
        $sql = "SELECT * FROM seats WHERE schedule_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "There was an error.";
            exit();
        }
    
        mysqli_stmt_bind_param($stmt, "s", $schedule_id);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);

        $lastid = mysqli_num_rows($result);
        $next_id = $last_id + 1;
        $newRef = $schedule_id."-".$seat_num."_".sprintf('%06d', $next_id);

        return $newRef;
    
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
 ?>