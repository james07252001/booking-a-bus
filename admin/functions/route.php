<?php
function getRouteById($conn, $id){
    $sql = "SELECT * FROM routes WHERE id = ?";
    
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