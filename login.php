<?php 
    include('includes/layout-header.php');
    
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION["userId"])){
        header("location: account.php");
        exit;
    }

    include('controllers/db.php');
    include('controllers/passenger.php');

    $database = new Database();
    $db = $database->getConnection();

    // Initialize login attempts tracking
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    if (!isset($_SESSION['last_login_attempt'])) {
        $_SESSION['last_login_attempt'] = 0;
    }

    // Check if account is temporarily locked
    $is_locked = false;
    if ($_SESSION['login_attempts'] >= 3) {
        $lock_duration = 30; // 30 seconds lockout
        $time_since_last_attempt = time() - $_SESSION['last_login_attempt'];
        
        if ($time_since_last_attempt < $lock_duration) {
            $is_locked = true;
            $remaining_time = $lock_duration - $time_since_last_attempt;
        } else {
            // Reset attempts after lockout period
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_login_attempt'] = 0;
        }
    }

    if(isset($_POST["sign-in-submit"])){
        // Check if account is locked
        if ($is_locked) {
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
        
        $login_result = $new_passenger->login($email, $password);

        if (!$login_result) {
            // Login failed
            $_SESSION['login_attempts']++;
            $_SESSION['last_login_attempt'] = time();

            if ($_SESSION['login_attempts'] >= 3) {
                // Redirect with locked status
                header("Location: login.php?signin=locked&time=30");
            } else {
                // Redirect with login fail status and remaining attempts
                $remaining_attempts = 3 - $_SESSION['login_attempts'];
                header("Location: login.php?signin=fail&attempts={$remaining_attempts}");
            }
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
                            $remaining_attempts = isset($_GET['attempts']) ? intval($_GET['attempts']) : 0;
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    Invalid email or password. You have <?php echo $remaining_attempts; ?> attempt(s) left.
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
                                    Too many failed attempts. Please wait <?php echo $remaining_time; ?> seconds before trying again.
                                </div>
                            <?php
                        }
                    }
                ?>

                <form method="POST" action="" id="login-form" 
                    <?php echo $is_locked ? 'onsubmit="return false;"' : ''; ?>>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                            <?php echo $is_locked ? 'disabled' : 'required'; ?> />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" 
                            <?php echo $is_locked ? 'disabled' : 'required'; ?> />
                        <a href="forget-password.php">Forgot password?</a>
                    </div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <button type="submit" class="btn btn-block btn-dark" name="sign-in-submit"
                        <?php echo $is_locked ? 'disabled' : ''; ?>>
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

    <?php if ($is_locked): ?>
    // Countdown timer for account unlock
    function startCountdown() {
        let remainingTime = <?php echo $remaining_time; ?>;
        const countdownElement = document.createElement('div');
        countdownElement.classList.add('text-danger', 'mt-2', 'text-center');
        document.getElementById('login-form').appendChild(countdownElement);

        const timer = setInterval(() => {
            if (remainingTime > 0) {
                countdownElement.textContent = `Please wait ${remainingTime} seconds`;
                remainingTime--;
            } else {
                clearInterval(timer);
                location.reload(); // Reload page to reset form
            }
        }, 1000);
    }
    startCountdown();
    <?php endif; ?>
</script>

<?php include('includes/layout-footer.php'); ?>