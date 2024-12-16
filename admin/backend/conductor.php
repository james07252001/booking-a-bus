<?php
    include '../dbconfig.php';

    if(count($_POST)>0){
        if($_POST['type']==1){
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Check if a conductor with the same phone number already exists
            $checkQuery = "SELECT * FROM `tblconductor` WHERE `phone` = '$phone'";
            $checkResult = mysqli_query($conn, $checkQuery);
            if (mysqli_num_rows($checkResult) > 0) {
                // If a conductor with the same phone number exists, return an error
                echo json_encode(array("statusCode" => 201, "message" => "Conductor with this phone number already exists."));
            } else {
                // If no duplicate, proceed to insert the new conductor
                $sql = "INSERT INTO `tblconductor`( `name`, `phone`, `address`) VALUES ('$name', '$phone', '$address')";
                if (mysqli_query($conn, $sql)) {
                    echo json_encode(array("statusCode" => 200));
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
            mysqli_close($conn);
        }
    }

    // Update Conductor
    if(count($_POST)>0){
        if($_POST['type']==2){
            $id = $_POST['id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
          
            $sql = "UPDATE `tblconductor` SET `name`='$name', `phone`='$phone', `address`='$address' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode" => 200));
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    // Delete Conductor
    if(count($_POST)>0){
        if($_POST['type']==3){
            $id = $_POST['id'];
            $sql = "DELETE FROM `tblconductor` WHERE id=$id ";
            if (mysqli_query($conn, $sql)) {
                echo $id;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }
?>
