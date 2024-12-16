<?php
include '../dbconfig.php';

if(count($_POST) > 0){
    if($_POST['type'] == 1){
        $route_from = $_POST['route_from'];
        $route_to = $_POST['route_to'];

        // Check if the route already exists
        $checkQuery = "SELECT * FROM `tblroute` WHERE `route_from` = '$route_from' AND `route_to` = '$route_to'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if(mysqli_num_rows($checkResult) > 0){
            // Route already exists
            echo json_encode(array("statusCode" => 201, "message" => "This route already exists."));
        } else {
            // Insert the new route
            $sql = "INSERT INTO `tblroute`(`route_from`, `route_to`) VALUES ('$route_from', '$route_to')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
}

if(count($_POST) > 0){
    if($_POST['type'] == 2){
        $id = $_POST['id'];
        $route_from = $_POST['route_from'];
        $route_to = $_POST['route_to'];

        // Check if the updated route already exists
        $checkQuery = "SELECT * FROM `tblroute` WHERE `route_from` = '$route_from' AND `route_to` = '$route_to' AND `id` != '$id'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if(mysqli_num_rows($checkResult) > 0){
            // Route already exists
            echo json_encode(array("statusCode" => 201, "message" => "This updated route already exists."));
        } else {
            // Update the route
            $sql = "UPDATE `tblroute` SET `route_from` = '$route_from', `route_to` = '$route_to' WHERE `id` = $id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
}

if(count($_POST) > 0){
    if($_POST['type'] == 3){
        $id = $_POST['id'];
        $sql = "DELETE FROM `tblroute` WHERE `id` = $id";
        if (mysqli_query($conn, $sql)) {
            echo $id;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
?>
