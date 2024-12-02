<?php
    session_start();
    include 'dbconfig.php';

    if(isset($_SESSION["userId"])){
        header("location: index.php");
        exit;
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
    <link rel="icon" href="../assets/img/bus.ico" type="image/ico">
    <title>Bantayan Online Bus Reservation</title>
</head>

<body>
    <div style="width: 100vw; height: 100vh" class="bg-light">
        <div class="h-100 d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <div class="w-100 m-auto" style="max-width: 500px">
                    <h1 class="text-center mb-5">Bantayan Online Bus Reservation</h1>
                    <div class="bg-white rounded shadow p-3">
                        <div class="text-center mb-5">
                            <img class="img-fluid" alt="login" src="../assets/img/newPass.png" style="width: 300px" />
                            <h4>Create New Password</h4>
                        </div>

                        <?php
                        if(empty($_GET["selector"]) || empty($_GET["validator"])){
                            echo '
                            <div class="alert alert-danger" role="alert">
                                <span>Could not validate your request. </span>
                                <a href="reset-password.php">Go to reset password</a>
                            </div>
                            ';
                        }else{
                            $selector = $_GET["selector"];
                        $validator = $_GET["validator"];
                        
                            if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
                                ?>

                        <?php
                                    if(isset($_GET["newPwd"])){
                                        if($_GET["newPwd"] == "mismatchPwd"){
                                            ?>
                        <div class="alert alert-danger" role="alert">
                            Mismatch password.
                        </div>
                        <?php
                                        }else if($_GET["newPwd"] == "invalid"){
                                            ?>
                        <div class="alert alert-danger" role="alert">
                            <span>Invalid! You need to re-submit your reset password request.</span>
                            <a href="reset-password.php">Go to reset password</a>
                        </div>
                        <?php
                                        }
                                    }
                                ?>

                        <form method="POST" action="backend/reset-password.php">
                            <input type="hidden" name="selector" value="<?php echo $selector ?>" />
                            <input type="hidden" name="validator" value="<?php echo $validator ?>" />

                            <div class="form mb-3">
                                <input type="password" class="form__input" id="pwd" name="pwd" placeholder=" "
                                    required />
                                <label for="pwd" class="form__label">Password</label>
                            </div>
                            <div class="form mb-3">
                                <input type="password" class="form__input" id="pwdRepeat" name="pwdRepeat"
                                    placeholder=" " required />
                                <label for="pwdRepeat" class="form__label">Confirm Password</label>
                            </div>
                            <button type="submit" class="btn btn-block btn-primary" name="reset-password-submit">Reset
                                Password</button>

                            <div class="text-center">
                                <a href="login.php" class="btn btn-link">Log in instead.</a>
                            </div>
                        </form>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/jquery.min.js"></script>
    <script src="../assets/popper.min.js"></script>
    <script src="../assets/bootstrap.min.js"></script>
    <script src="../assets/jquery.dataTables.min.js"></script>

    <script>
    $("#login_form").submit(function(event) {
        event.preventDefault();
        var data = $("#login_form").serialize();
        console.log(data)

        $.ajax({
            data: data,
            type: "post",
            url: "backend/user.php",
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    alert("Login successfully!");
                    window.location.replace("index.php")
                } else {
                    alert(dataResult.title);
                }
            },
        });
    });
    </script>

<?php include('includes/scripts.php')?>

</body>

</html>