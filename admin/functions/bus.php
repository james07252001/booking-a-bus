<?php
    function isBusExist($conn, $bus_num, $id){
        $sql = "SELECT * FROM tblbus WHERE bus_num = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../index.php?page=bus&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $bus_num);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            if(isset($id)){
                if($row["id"] == $id){
                    return false;
                }
            }
            return $row;
        }else{
            return false;
        }

        mysqli_stmt_close($stmt);
    }

    function getBusById($conn, $id){
        $sql = "SELECT * FROM tblbus WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = trim($id);
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
        
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    return $row;
                } else{
                    return false;
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        mysqli_close($conn);
    }
?>