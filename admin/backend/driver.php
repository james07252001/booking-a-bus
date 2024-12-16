<?php
include '../dbconfig.php';

if(count($_POST) > 0){
    // Handle Add New Driver (Type 1)
    if($_POST['type'] == 1){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        
        // Check if a driver with the same name or phone already exists
        $checkQuery = "SELECT * FROM `tbldriver` WHERE `name` = '$name' OR `phone` = '$phone'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if(mysqli_num_rows($checkResult) > 0){
            // If a duplicate is found, return error
            echo json_encode(array("statusCode" => 201, "message" => "Driver with this name or phone number already exists."));
        } else {
            // Insert the new driver
            $sql = "INSERT INTO `tbldriver`(`name`, `phone`, `address`) VALUES ('$name', '$phone', '$address')";
            if(mysqli_query($conn, $sql)){
                echo json_encode(array("statusCode" => 200, "message" => "Driver added successfully!"));
            } else {
                echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
            }
        }
        mysqli_close($conn);
    }

    // Handle Update Driver (Type 2)
    if($_POST['type'] == 2){
        $id = $_POST['id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        
        // Update the driver details
        $sql = "UPDATE `tbldriver` SET `name`='$name', `phone`='$phone', `address`='$address' WHERE id=$id";
        if(mysqli_query($conn, $sql)){
            echo json_encode(array("statusCode" => 200, "message" => "Driver updated successfully!"));
        } else {
            echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
        }
        mysqli_close($conn);
    }

    // Handle Delete Driver (Type 3)
    if($_POST['type'] == 3){
        $id = $_POST['id'];
        // Delete the driver
        $sql = "DELETE FROM `tbldriver` WHERE id=$id";
        if(mysqli_query($conn, $sql)){
            echo $id;  // Return the ID of the deleted driver
        } else {
            echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
        }
        mysqli_close($conn);
    }
}
?>
