<?php
require_once '../dbconfig.php';
require_once '../functions/bus.php';

if (count($_POST) > 0) {
    $type = $_POST['type'];

    switch ($type) {
        case 1: // Add new bus
            $bus_num = mysqli_real_escape_string($conn, $_POST['bus_num']);
            $bus_type = mysqli_real_escape_string($conn, $_POST['bus_type']);
            $bus_code = mysqli_real_escape_string($conn, $_POST['bus_code']);

            // Check if bus already exists
            if (isBusExist($conn, $bus_code, null)) {
                echo json_encode(array("statusCode" => 500, "title" => "Bus number already exists."));
                exit();
            }

            $sql = "INSERT INTO `tblbus` (`bus_num`, `bus_type`, `bus_code`) 
                    VALUES ('$bus_num', '$bus_type', '$bus_code')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo json_encode(array("statusCode" => 500, "title" => "Database Error: Unable to add new bus."));
            }
            break;

        case 2: // Update bus
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $bus_num = mysqli_real_escape_string($conn, $_POST['bus_num']);
            $bus_type = mysqli_real_escape_string($conn, $_POST['bus_type']);
            $bus_code = mysqli_real_escape_string($conn, $_POST['bus_code']);

            // Check if bus code already exists for another record
            if (isBusExist($conn, $bus_code, $id)) {
                echo json_encode(array("statusCode" => 500, "title" => "Bus number already exists."));
                exit();
            }

            $sql = "UPDATE `tblbus` 
                    SET `bus_num`='$bus_num', `bus_type`='$bus_type', `bus_code`='$bus_code' 
                    WHERE `id`='$id'";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo json_encode(array("statusCode" => 500, "title" => "Database Error: Unable to update bus."));
            }
            break;

        case 3: // Delete bus
            $id = mysqli_real_escape_string($conn, $_POST['id']);

            $sql = "DELETE FROM `tblbus` WHERE `id`='$id'";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200, "id" => $id));
            } else {
                echo json_encode(array("statusCode" => 500, "title" => "Database Error: Unable to delete bus."));
            }
            break;

        default:
            echo json_encode(array("statusCode" => 400, "title" => "Invalid request type."));
    }
    mysqli_close($conn);
}
?>
