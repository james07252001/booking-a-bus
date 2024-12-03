<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/styles.css" />
    
    <title>Bantayan Online Bus Reservation</title>


  </head>
<?php

function pwdMatch($pwd, $pwdRepeat){
    return $pwd == $pwdRepeat ? true : false;
}

function isEmailExist($conn, $email){
    $sql = "SELECT * FROM passengers WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../sign-up.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function sendVerificationCode($whom, $name, $verification_code){
    $subject = "Verification Code";
    $comment = 'Hello, ' . $name . "\n\n Your verification code is: " . $verification_code;

    mail($whom,$subject,$comment);
}

function createPassenger($conn, $fname, $lname, $email, $contactNo, $address, $pwd, $verification_code){
    $sql = "INSERT INTO passengers (fname, lname, email, contactNo, address, password, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../sign-up.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssss", $fname, $lname, $email, $contactNo, $address, $hashedPwd, $verification_code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../sign-in.php?signUp=passengerCreated");
    exit();
}

function loginPassenger($conn, $email, $pwd){
    $isExists = isEmailExist($conn, $email);

    if($isExists === false){
        header("location: ../sign-in.php?signin=fail");
        exit();
    }
    
    $checkPwd = password_verify($pwd, $isExists['password']);

    if($checkPwd === false){
        header("location: ../sign-in.php?signin=fail");
        exit();
    }else if($checkPwd === true){
        session_start();
        
        $_SESSION["userId"] = $isExists['id'];
        $_SESSION["userFname"] = $isExists['fname'];
        $_SESSION["userLname"] = $isExists['lname'];
        $_SESSION["userEmail"] = $isExists['email'];
        $_SESSION["userEmailVerifiedAt"] = $isExists['email_verified_at'] == null ? false : true;

        header("location: ../account.php");
        exit();
    }
}