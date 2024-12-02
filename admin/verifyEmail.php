<?php
    session_start();
    include 'dbconfig.php';

    if(!isset($_SESSION["userId"])){
        header("location: login.php");
        exit;
    }else{
        if($_SESSION["userEmailVerifiedAt"] == true){
            header("location: index.php");
        }
    }

    if(isset($_POST["verify_email"])){
        $userEmail = $_SESSION["userEmail"];
        $verification_code = $_POST["code"];

        $sql = "UPDATE tbluser SET email_verified_at = NOW() WHERE email = '". $userEmail ."' AND verification_code = '".$verification_code."'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) == 0){
            header("location: verifyEmail.php?error=wrongCode");
            exit();
        }

        $_SESSION["userEmailVerifiedAt"] = true;
        header("location: index.php");
        exit();
    }
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="icon" href="assets/favicon.ico" type="image/ico">
    <title>Ceres</title>
</head>

<body>
    <div style="width: 100vw; height: 100vh" class="bg-light">
        <div class="h-100 d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <div class="w-100 m-auto" style="max-width: 500px">
                    <h1 class="text-center mb-5">Bantayan Island Online Bus Reservation</h1>
                    <div class="bg-white rounded shadow p-3">
                        <div class="text-center mb-3">
                            <img class="img-fluid" alt="verifyEmail" src="../assets/img/verifyEmail.png"
                                style="width: 300px" />
                        </div>

                        <h3 class="text-uppercase text-center mb-4">Just one more step, <br /> Let's verify your email
                        </h3>
                        <p class="text-muted mb-3">We already send a code to <b><?php echo $_SESSION["userEmail"]?></b>,
                            please check your inbox
                            and enter the code in form below to verify your email</p>

                        <?php
                        if(isset($_GET['error'])){
                            if($_GET['error'] == 'wrongCode'){
                                ?>
                        <div class="alert alert-danger" role="alert">
                            Invalid verification code.
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <form method="POST">
                            <div class="mb-3">
                                <div class="form">
                                    <input type="text" class="form__input" id="code" name="code" placeholder=" "
                                        required />
                                    <label for="code" class="form__label">Verification Code</label>
                                </div>
                                <!-- <div class="text-right">
                                    <a href="" class="btn btn-link">Resend Verification Code</a>
                                </div> -->
                            </div>
                            <button type="submit" class="btn btn-block btn-primary"
                                name="verify_email">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/jquery.dataTables.min.js"></script>
</body>

</html>