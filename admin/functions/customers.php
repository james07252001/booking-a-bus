<?php
    function isEmailExist($conn, $email, $id){
        $sql = "SELECT * FROM customertbl WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../index.php?page=customer&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
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
?>