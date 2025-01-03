<?php
require_once '../dbconfig.php';
require_once '../functions/bus.php';

    if(count($_POST)>0){
        if($_POST['type']==1){
            $bus_num=$_POST['bus_num'];
            $bus_type=$_POST['bus_type'];
            $bus_code=$_POST['bus_code'];


            if(isBusExist($conn, $bus_code, null)){
                echo json_encode(array("statusCode"=>500, "title"=>"Bus number already exist."));
                exit();
            }
            
            $sql = "INSERT INTO `tblbus`( `bus_num`, `bus_type`, `bus_code`) 
            VALUES ('$bus_num', '$bus_type', '$bus_code' )";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==2){
            $id=$_POST['id'];
            $bus_num=$_POST['bus_num'];
            $bus_type=$_POST['bus_type'];
            $bus_code=$_POST['bus_code'];

            // if(isBusExist($conn, $bus_num, $bus_type,  $rate_km, $id)){
            //     echo json_encode(array("statusCode"=>500, "title"=>"Bus number already exist."));
            //     exit();
            // }
            
            $sql = "UPDATE `tblbus` SET `bus_num`='$bus_num', `bus_type`='$bus_type', `bus_code`='$bus_code' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==3){
            $id=$_POST['id'];
            $sql = "DELETE FROM `tblbus` WHERE id=$id ";
            if (mysqli_query($conn, $sql)) {
                echo $id;
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }
?>