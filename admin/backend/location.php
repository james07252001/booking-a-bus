<?php
include '../dbconfig.php';

if (count($_POST) > 0) {
    if ($_POST['type'] == 1) { // Add new location
        $location_name = trim($_POST['location_name']);
        
        // Check for duplicates
        $checkQuery = "SELECT * FROM tbllocation WHERE location_name = '$location_name'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            echo json_encode(array("statusCode" => 201, "message" => "Location already exists."));
        } else {
            $sql = "INSERT INTO `tbllocation` (`location_name`) VALUES ('$location_name')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
            }
        }
    }

    if ($_POST['type'] == 2) { // Update location
        $id = $_POST['id'];
        $location_name = trim($_POST['location_name']);
        
        // Check for duplicates (excluding current record)
        $checkQuery = "SELECT * FROM tbllocation WHERE location_name = '$location_name' AND id != $id";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            echo json_encode(array("statusCode" => 201, "message" => "Location name already exists."));
        } else {
            $sql = "UPDATE `tbllocation` SET `location_name` = '$location_name' WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
            }
        }
    }

    if ($_POST['type'] == 3) { // Delete location
        $id = $_POST['id'];
        $sql = "DELETE FROM `tbllocation` WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            echo $id;
        } else {
            echo json_encode(array("statusCode" => 500, "message" => "Error: " . mysqli_error($conn)));
        }
    }

    mysqli_close($conn);
}
?>
