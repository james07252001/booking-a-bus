<?php 
    include('includes/layout-header.php');
    
    if(!isset($_SESSION["userId"])){
        header("location: login.php");
        exit;
    }else{
        if(isset($_SESSION["isVerified"]) && $_SESSION["isVerified"] === true){
            header("location: account.php");
            exit;
        }
    }

    if(isset($_POST["verify_email"])){
        include('controllers/db.php');
        $database = new Database();
        $conn = $database->getConnection();

        $userEmail = $_SESSION["userEmail"];
        $verification_code = $_POST["code"];

        $sql = "UPDATE tblpassenger SET email_verified_at = NOW() WHERE email = '". $userEmail ."' AND verification_code = '".$verification_code."'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) == 0){
            header("location: verify-email.php?error=wrongCode");
            exit();
        }
        
        $_SESSION["isVerified"] = true;
        header("location: account.php");
        exit();
    }
?>

<main>
    <div class="container mt-5">
        <div class="w-100 m-auto" style="max-width: 600px">
            <div class="shadow-sm bg-white p-3">
                <h3 class="text-uppercase text-center mb-4">Just one more step, <br /> Let's verify your email</h3>
                <p class="text-muted mb-3">
                    We already send a code to <b><?php echo $_SESSION["userEmail"]?></b>,
                    please check your inbox and enter the code in form below to verify your email
                </p>

                <?php
                    if(isset($_GET['error'])){
                        if($_GET['error'] == 'wrongCode'){
                            echo '<div class="alert alert-danger" role="alert">
                            Invalid verification code.
                        </div>';
                        }
                    }
                ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="code">Verification Code</label>
                            <input type="text" class="form-control" id="code" name="code" required />
                        </div>
                        <!-- <div class="text-right">
                                <a href="" class="btn btn-link">Resend Verification Code</a>
                            </div> -->
                    </div>
                    <button type="submit" class="btn btn-block btn-dark" name="verify_email">Continue</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>