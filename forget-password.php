<?php
    include('includes/layout-header.php');
    
    if(isset($_SESSION["userId"])){
        header("location: account.php");
        exit;
    }

    if(isset($_POST["forget-pass-submit"])){
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);
    
        $root_url = 'http://localhost/onlinebusreservationsystem';
        $url = $root_url."/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);
    
        $expires = date("U") + 1800;
    
        include('controllers/db.php');
        $database = new Database();
        $db = $database->getConnection();
    
        include('controllers/passenger.php');
        $new_passenger = new Passenger($db);
    
        $userEmail = $_POST["email"];
    
        if($new_passenger->isEmailExist($userEmail) == false){
            header("location: forget-password.php?reset=emailNotExist");
            exit();
        }
    
        $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
        $stmt = mysqli_stmt_init($db);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo 'There was an error!';
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "s", $userEmail);
            mysqli_stmt_execute($stmt);
        }
    
        $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, 	pwdResetExpires) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($db);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo 'There was an error!';
            exit();
        }else{
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
            mysqli_stmt_execute($stmt);
        }
    
        mysqli_stmt_close($stmt);
        mysqli_close($db);
    
        $to = $userEmail;
    
        $subject = 'Reset password';
        $message = '<p>We receive a password reset request. Click reset password button below to reset your password.</p>';
        $message .= '<a href="'.$url.'" target="_blank">Reset Password</a>';
    
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
        mail($to, $subject, $message, $headers);
    
        header("location: forget-password.php?reset=success");
    }
?>


<main>
    <div class="container mt-5">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px">
            <div class="bg-primary p-3">
                <h1 class="text-center">Forget Password</h1>
            </div>

            <div class="p-3">
                <?php
                    if(isset($_GET["reset"])){
                        if($_GET["reset"] == "success"){
                            echo '<div class="alert alert-success" role="alert">
            
                            Check your email for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder.
                        </div>';
                        }else if($_GET["reset"] == "emailNotExist"){
                            echo '<div class="alert alert-danger" role="alert">
                            You are not register yet. Please create an account first.
                        </div>';
                        }
                    }
                ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    
                    <button type="submit" class="btn btn-block btn-dark" name="forget-pass-submit">Request
                        reset password</button>

                    <div class="text-center">
                        <a href="login.php">Login instead</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>


<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>