<?php
    if(isset($_SESSION["userId"])){
        if(isset($_SESSION["isVerified"]) && $_SESSION["isVerified"] === false){
            header("location: verify.php");
            exit;
        }
    }else{
        header("location: login.php");
        exit;
    }
?>