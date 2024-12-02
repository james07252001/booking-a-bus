<?php
    function emptyInputRegister($name, $email, $username, $pwd, $pwdRepeat){
        $result = false;
        if(empty($name) || empty($email) || empty($pwd) || empty($pwdRepeat)){
            $result = true;
        }
        return $result;
    }
    function validEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    function pwdMatch($pwd, $pwdRepeat){
        return $pwd == $pwdRepeat ? true : false;
    }
    function isEmailExist($conn, $email, $username){
        $sql = "SELECT * FROM tbluser WHERE email = ? OR username = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "There was an error.";
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($resultData)){
            return $row;
        }else{
            return false;
        }

        mysqli_stmt_close($stmt);
    }
    function createUser($conn, $name, $email, $username, $pwd){
        $sql = "INSERT INTO tbluser (name, email, username, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../index.php?page=users&error=stmtfailed");
            exit();
        }

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../index.php?page=users");
        exit();
    }

    function sendVerificationCode($whom, $name, $verification_code){
        $subject = "Verification Code";
        $comment = 'Hello, ' . $name . "\n\n Your verification code is: " . $verification_code;

        mail($whom,$subject,$comment);
    }

    function loginUser($conn, $email, $pwd){
        $isExists = isEmailExist($conn, $email, "");

        if($isExists === false){
            echo json_encode(array("statusCode"=>404, "title"=>"Invalid email or password."));
            exit();
        }
        
        $checkPwd = password_verify($pwd, $isExists['password']);

        if($checkPwd === false){
            echo json_encode(array("statusCode"=>404, "title"=>"Invalid email or password."));
            exit();
        }else if($checkPwd === true){
            // if($isExists['isAdmin'] === 0 || $isExists['status'] === 0){
            //     echo json_encode(array("statusCode"=>401, "title"=>"Unathorized access."));
            //     exit();
            // }
            
            if($isExists['status'] === 0){
                echo json_encode(array("statusCode"=>404, "title"=>"Unathorized access."));
                 exit();
            }
            session_start();
            
            $_SESSION["userId"] = $isExists['id'];
            $_SESSION["userFname"] = $isExists['fullname'];
            $_SESSION["username"] = $isExists['username'];
            $_SESSION["userEmail"] = $isExists['email'];
            // $_SESSION["userIsAdmin"] = $isExists['isAdmin'];
            $_SESSION["userEmailVerifiedAt"] = $isExists['email_verified_at'] == null ? false : true;

            echo json_encode(array("statusCode"=>200));
            exit();
        }
    }
?>