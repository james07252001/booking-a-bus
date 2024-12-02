<?php
require_once '../dbconfig.php';
require_once '../functions/customers.php';

    if(count($_POST)>0){
        if($_POST['type']==1){
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $email=$_POST['email'];
            $contact=$_POST['contact'];
            $address=$_POST['address'];

            if(isEmailExist($conn, $email, null)){
                echo json_encode(array("statusCode"=>500, "title"=>"Email already exist."));
                exit();
            }
            
            $sql = "INSERT INTO `customertbl`( `firstname`, `lastname`, `email`, `contact`, `address`) 
        VALUES ('$firstname', '$lastname', '$email', '$contact', '$address')";
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
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $email=$_POST['email'];
            $contact=$_POST['contact'];
            $address=$_POST['address'];

            if(isEmailExist($conn, $email, $id)){
                echo json_encode(array("statusCode"=>500, "title"=>"Email already exist."));
                exit();
            }
            
            $sql = "UPDATE `customertbl` SET `firstname`='$firstname', `lastname`='$lastname', `email`='$email', `contact`='$contact', `address`='$address' WHERE id=$id";
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
            $sql = "DELETE FROM `customertbl` WHERE id=$id ";
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