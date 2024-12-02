<?php
    require_once '../dbconfig.php';
    require_once '../functions/users.php';

    // Check if POST data exists
    if(count($_POST) > 0){
        // Handle registration (type = 1)
        if($_POST['type'] == 1){
            $fullname = trim($_POST['fullname']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirmPassword']);
            $status = 1;

            // Validation
            if(!validEmail($email)){
                echo json_encode(array("statusCode" => 500, "title" => "Invalid email."));
            } else if(!pwdMatch($password, $confirmPassword)){
                echo json_encode(array("statusCode" => 500, "title" => "Passwords do not match."));
            } else if(isEmailExist($conn, $email, $username)){
                echo json_encode(array("statusCode" => 500, "title" => "Email or username already exists."));
            } else {
                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                sendVerificationCode($email, $username, $verification_code);
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                // Using prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO tbluser (fullname, username, email, password, status, verification_code, email_verified_at) 
                                        VALUES (?, ?, ?, ?, ?, ?, NULL)");
                $stmt->bind_param("ssssis", $fullname, $username, $email, $hashedPwd, $status, $verification_code);

                if($stmt->execute()){
                    echo json_encode(array("statusCode" => 200, "title" => "Registration successful."));
                } else {
                    echo json_encode(array("statusCode" => 500, "title" => "Error occurred during registration."));
                }

                // Close the statement and connection
                $stmt->close();
                $conn->close();
            }
        }

        // Handle status update (type = 2)
        if($_POST['type'] == 2){
            $id = intval($_POST['id']);
            $status = intval($_POST['status']);

            // Using prepared statement
            $stmt = $conn->prepare("UPDATE tbluser SET status = ? WHERE id = ?");
            $stmt->bind_param("ii", $status, $id);

            if($stmt->execute()){
                echo json_encode(array("statusCode" => 200, "title" => "Status updated successfully."));
            } else {
                echo json_encode(array("statusCode" => 500, "title" => "Error occurred during status update."));
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }

        // Handle login (type = 3)
        if($_POST['type'] == 3){
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Call the secure login function
            loginUser($conn, $email, $password);
        }
    }
?>
