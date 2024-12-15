<?php 
    include('includes/layout-header.php');
    
    if(isset($_SESSION["userId"])){
        header("location: account.php");
        exit;
    }

    include('controllers/db.php');
    include('controllers/passenger.php');

    $database = new Database();
    $db = $database->getConnection();

    if(isset($_POST["sign-in-submit"])){
        // Verify reCAPTCHA
        $recaptcha_secret = "6LfXi5wqAAAAADx-yGAWdeuB5VcJwNu-KGXHHetM"; // Replace with your actual secret key
        $response = $_POST['g-recaptcha-response'];
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
        $captcha_success = json_decode($verify);

        if($captcha_success->success == false) {
            // reCAPTCHA verification failed
            header("Location: login.php?signin=captcha");
            exit;
        }

        $new_passenger = new Passenger($db);

        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $new_passenger->login($email, $password);
    }
?>

<main>
    <div class="container mt-5">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px">
            <div class="bg-primary p-3">
                <h1 class="text-center">Login</h1>
            </div>

            <div class="p-3">
                <?php
                    if(isset($_GET["signUp"])){
                        if($_GET["signUp"] == "passengerCreated"){
                            echo '<div class="alert alert-success" role="alert">
                            Account created successfully.
                          </div>';
                        }
                    }else if(isset($_GET["newpwd"])){
                        if($_GET["newpwd"] == "passwordUpdated"){
                            ?>
                                <div class="alert alert-success" role="alert">
                                    Password updated successfully.
                                </div>
                            <?php
                        }
                    }else if(isset($_GET["signin"])){
                        if($_GET["signin"] == "fail"){
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    Invalid email or password.
                                </div>
                            <?php
                        } else if($_GET["signin"] == "captcha"){
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    reCAPTCHA verification failed. Please try again.
                                </div>
                            <?php
                        }
                    }
                ?>

                <form method="POST" action="" id="login-form">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required />
                    </div>
                    <div class="text-center mt-2">
                        <a href="forget-password.php" class="forgot-password">Forgot password?</a>
                    </div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <button type="submit" class="btn btn-block btn-dark" name="sign-in-submit">Login</button>

                    <div class="text-center">
                        <span>Not register yet? </span>
                        <a href="register.php">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    body {
    background-image: url('assets/img/plaza.jpg'); /* Replace with the actual path to your image */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh; /* Ensure it covers the full viewport height */
    margin: 0;
    font-family: Arial, sans-serif; /* Optional: Set a default font family */
}
.text-center .forgot-password {
    display: inline-block; /* Ensure it's treated as an inline element for alignment */
    font-size: 0.9rem; /* Adjust size if needed */
    color: #007bff; /* Default Bootstrap link color */
    text-decoration: none;
    margin-top: 10px; /* Optional: Add space above */
}

.text-center .forgot-password:hover {
    text-decoration: underline; /* Add underline on hover for better UX */
}

</style>

<?php include('includes/scripts.php')?>

<!-- Add reCAPTCHA v3 script -->
<script src="https://www.google.com/recaptcha/api.js?render=6LfXi5wqAAAAACCfme12iSCd2LbbXqeECqswcs95"></script>
<script>
    // Replace '6LfXi5wqAAAAACCfme12iSCd2LbbXqeECqswcs95' with your actual site key
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfXi5wqAAAAACCfme12iSCd2LbbXqeECqswcs95', {action: 'login'}).then(function(token) {
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
</script>

<?php include('includes/layout-footer.php')?>