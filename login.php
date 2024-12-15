<?php 
    session_start(); // Ensure session is started at the beginning
    include('includes/layout-header.php');
    
    if(isset($_SESSION["userId"])){
        header("location: account.php");
        exit;
    }

    include('controllers/db.php');
    include('controllers/passenger.php');

    $database = new Database();
    $db = $database->getConnection();

    // Function to reset login attempts
    function resetLoginAttempts() {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_login_attempt_time']);
    }

    // Check if login attempts should be reset
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    // Check for lockout
    $is_locked_out = false;
    if (isset($_SESSION['last_login_attempt_time'])) {
        $lockout_time = 30; // 30 seconds lockout
        $time_since_last_attempt = time() - $_SESSION['last_login_attempt_time'];
        
        if ($time_since_last_attempt < $lockout_time) {
            $is_locked_out = true;
            $remaining_time = $lockout_time - $time_since_last_attempt;
        } else {
            // Lockout time has passed, reset attempts
            resetLoginAttempts();
        }
    }

    if(isset($_POST["sign-in-submit"])){
        // Check if account is locked out
        if ($is_locked_out) {
            header("Location: login.php?signin=locked&time={$remaining_time}");
            exit;
        }

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
        
        // Attempt login
        $login_result = $new_passenger->login($email, $password);

        if(!$login_result) {
            // Increment login attempts
            $_SESSION['login_attempts']++;
            $_SESSION['last_login_attempt_time'] = time();

            // Check if max attempts reached
            if ($_SESSION['login_attempts'] >= 3) {
                header("Location: login.php?signin=locked&time=30");
                exit;
            }

            header("Location: login.php?signin=fail");
            exit;
        }
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
                            $attempts_left = 3 - $_SESSION['login_attempts'];
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    Invalid email or password. 
                                    <?php echo "Attempts left: {$attempts_left}"; ?>
                                </div>
                            <?php
                        } else if($_GET["signin"] == "captcha"){
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    reCAPTCHA verification failed. Please try again.
                                </div>
                            <?php
                        } else if($_GET["signin"] == "locked"){
                            $remaining_time = isset($_GET['time']) ? intval($_GET['time']) : 30;
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    Too many failed login attempts. Please wait <?php echo $remaining_time; ?> seconds before trying again.
                                </div>
                            <?php
                        }
                    }
                ?>

                <form method="POST" action="" id="login-form">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               <?php echo $is_locked_out ? 'disabled' : ''; ?> />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required 
                               <?php echo $is_locked_out ? 'disabled' : ''; ?> />
                        <a href="forget-password.php">Forgot password?</a>
                    </div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <button type="submit" class="btn btn-block btn-dark" name="sign-in-submit"
                            <?php echo $is_locked_out ? 'disabled' : ''; ?>>
                        Login
                    </button>

                    <div class="text-center">
                        <span>Not register yet? </span>
                        <a href="register.php">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

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

    // Add a countdown timer for lockout
    <?php if($is_locked_out): ?>
    document.addEventListener('DOMContentLoaded', function() {
        let remainingTime = <?php echo $remaining_time; ?>;
        const lockoutMessage = document.querySelector('.alert-danger');
        
        const countdownTimer = setInterval(function() {
            remainingTime--;
            lockoutMessage.textContent = `Too many failed login attempts. Please wait ${remainingTime} seconds before trying again.`;
            
            if (remainingTime <= 0) {
                clearInterval(countdownTimer);
                location.reload(); // Reload to reset the form
            }
        }, 1000);
    });
    <?php endif; ?>
</script>

<?php include('includes/layout-footer.php')?>