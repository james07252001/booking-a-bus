<?php
if(isset($_POST["reset-password-submit"])){
    if(empty($_POST["pwd"]) || empty($_POST["pwdRepeat"])){
        header("location: ../create-new-password.php?selector=".$_POST["selector"]."&validator=".$_POST["validator"]."&newPwd=empty");
        exit();
    }
    
    if($_POST["pwd"] !== $_POST["pwdRepeat"]){
        header("location: ../create-new-password.php?selector=". $_POST["selector"] . "&validator=". $_POST["validator"] ."&newPwd=mismatchPwd");
        exit();
    }

    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdRepeat"];

    $currentDate = date("U");

    require_once '../dbconfig.php';

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector = ? AND pwdResetExpires >= ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error.";
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if(!$row = mysqli_fetch_assoc($result)){
            header("location: ../create-new-password.php?selector=".$selector."&validator=".$validator."&newPwd=invalid");
            exit();
        }else{
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

            if($tokenCheck == false){
                header("location: ../create-new-password.php?selector=".$selector."&validator=".$validator."&newPwd=invalid");
                exit();
            }else if($tokenCheck == true){
                $tokenEmail = $row["pwdResetEmail"];

                $sql = "SELECT * FROM tbluser WHERE email = ?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo "There was an error.";
                    exit();
                }else{
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);

                    if(!$row = mysqli_fetch_assoc($result)){
                        header("location: ../create-new-password.php?selector=".$selector."&validator=".$validator."&newPwd=invalid");
                        exit();
                    }else{
                        $sql = "UPDATE tbluser SET password = ? WHERE email = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            echo "There was an error.";
                            exit();
                        }else{
                            $newPwdHash = password_hash($pwd, PASSWORD_DEFAULT);

                            mysqli_stmt_bind_param($stmt, "ss",$newPwdHash, $tokenEmail);
                            mysqli_stmt_execute($stmt);

                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                echo 'There was an error!';
                                exit();
                            }else{
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);

                                header("location: ../login.php?newpwd=passwordUpdated");
                            }
                        }
                    }
                }
            }
        }
    }
}else{
    header("location: ../create-new-password.php");
}