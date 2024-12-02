<?php
function getSeatNum($conn, $schedule_id, $seat_num){
    $sql = "SELECT * FROM seats WHERE schedule_id = ? AND seat_num = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $schedule_id, $seat_num);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        return $row;
    } else{
        return false;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function getSeat($conn, $schedule_id){
    $sql = "SELECT * FROM seats WHERE schedule_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $schedule_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        return $row;
    } else{
        return false;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>