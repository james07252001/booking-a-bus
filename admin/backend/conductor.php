<?php
    include '../dbconfig.php';

    if(count($_POST)>0){
        if($_POST['type']==1){
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            
            // Check if conductor with the same name or phone exists
            $checkQuery = "SELECT * FROM `tblconductor` WHERE `name` = '$name' OR `phone` = '$phone'";
            $checkResult = mysqli_query($conn, $checkQuery);
            
            if(mysqli_num_rows($checkResult) > 0){
                // If a duplicate is found, return an error
                echo json_encode(array("statusCode"=>201, "message"=>"Conductor with this name or phone already exists."));
            } else {
                // If no duplicate, insert the new conductor
                $sql = "INSERT INTO `tblconductor` (`name`, `phone`, `address`) VALUES ('$name', '$phone', '$address')";
                if (mysqli_query($conn, $sql)) {
                    echo json_encode(array("statusCode"=>200));
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }

            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==2){
            $id = $_POST['id'];
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            
            // Update conductor details
            $sql = "UPDATE `tblconductor` SET `name`='$name', `phone`='$phone', `address`='$address' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==3){
            $id = $_POST['id'];
            // Delete conductor record
            $sql = "DELETE FROM `tblconductor` WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                echo $id; // Return the ID of the deleted conductor
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }
?>
