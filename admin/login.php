<?php
    session_start();
    include 'dbconfig.php';

    if(isset($_SESSION["userId"])){
        header("location: index.php");
        exit;
    }

    // Redirect from .php URLs to remove the extension
    $request = $_SERVER['REQUEST_URI'];
    if (substr($request, -4) == '.php') {
        $new_url = substr($request, 0, -4);
        header("Location: $new_url", true, 301);
        exit();
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="icon" href="../assets/img/bus.ico" type="image/ico">
    <title>Bantayan Online Bus Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        #lockout-timer {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            display: none;
        }
        #login-button {
            border: 3px solid transparent; /* Transparent border initially */
            border-image: linear-gradient(to top, #dbdcd7 0%, #dddcd7 24%, #e2c9cc 30%, #e7627d 46%, #b8235a 59%, #801357 71%, #3d1635 84%, #1c1a27 100%); /* Gradient border */
            border-image-slice: 1; /* Apply the gradient to the border */
            font-family: 'Times New Roman', serif; /* Set the font */
            font-size: 20px; /* Adjust the font size */
            transition: box-shadow 0.3s ease-in-out; /* Smooth transition for glow effect */
            position: relative; /* Position for pseudo-element */
        }

        #login-button:hover {
            box-shadow: 0 0 15px 5px #4e73df; /* Glowing effect when hovered */
        }

    </style>
</head>

<body class="bg-light">
    <!-- Navigation remains the same -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" style="background-image: linear-gradient(-225deg, #77FFD2 0%, #6297DB 48%, #1EECFF 100%);">
        <div class="container">
            <a class="navbar-brand" href="index.php" style="font-family: 'Times New Roman', serif;"></a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php"> <i class="fa fa-home w3-large " style="color: black"> <b>Home</a></b></i>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php"> <i class="fa fa-user icon w3-large " style="color: black"> <b>User</a></b></i>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="width: 100vw; height: 80vh" class="bg-light">
        <div class="h-100 d-flex flex-column justify-content-center align-items-center" style="background-image: linear-gradient(to top, lightgrey 0%, lightgrey 1%, #e0e0e0 26%, #efefef 48%, #d9d9d9 75%, #bcbcbc 100%);">
            <div class="container">
                <div class="w-100 m-auto" style="max-width: 500px;">
                    <div class="bg-white rounded shadow p-3" style="background-image: linear-gradient(to top, #c1dfc4 0%, #deecdd 100%);">
                        <div class="text-center mb-5">
                            <img class="img-fluid" alt="login" src="../assets/images/bobrs3.png" style=" width: 300px" />
                            <br>
                            <br>
                            <h4 style="font-family: 'Times New Roman', serif;">BUS ADMINISTRATOR</h4>
                        </div>

                        <?php
                            if(isset($_GET["newpwd"])){
                                if($_GET["newpwd"] == "passwordUpdated"){
                        ?>
                        <div class="alert alert-success" role="alert">
                            Password updated successfully.
                        </div>
                        <?php
                                }
                            }
                        ?>

                        <!-- New Lockout Timer -->
                        <div id="lockout-timer" class="alert alert-danger"></div>

                        <form id="login_form">
                            <input type="hidden" value="3" name="type">

                            <div class="form mb-3">
                                <input type="email" class="form__input" id="email" name="email" style="border-color: black" placeholder=" " required />
                                <label for="email" class="form__label" style="font-family: 'Times New Roman', serif; background-color: #F2F2F2">Email address</label>
                            </div>
                            
                            <!-- Password Field with Eye Icon -->
                            <div class="mb-3">
                                <div class="form position-relative">
                                    <input type="password" class="form__input" id="password" name="password" style="border-color: black" placeholder=" " required />
                                    <label for="password" class="form__label" style="font-family: 'Times New Roman', serif; text-size: 20px; background-color: #F2F2F2">Password</label>
                                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;"></span>
                                </div>
                                <a href="reset-password.php" style="font-family: 'Times New Roman', serif; text-size: 20px; background-color: #F2F2F2">Forgot password?</a>
                            </div>
                            <button type="submit" id="login-button" class="btn btn-block btn-primary" style="font-family: 'Times New Roman', serif; text-size: 20px;">LOGIN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/jquery.dataTables.min.js"></script>

    <script src="https://www.google.com/recaptcha/api.js?render=6LfRhIoqAAAAAGw8WMJ_Gd7hZGhdFVzvTNDAt8dw"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        var lockoutTimer; // For storing the countdown interval
        var $lockoutTimerDisplay = $('#lockout-timer');

        // Check if login is locked out on page load
        checkLoginLockout();

        // Password toggle functionality
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        // Start the lockout countdown timer
        function startLockoutTimer(remainingSeconds) {
            // Clear any existing timer
            if (lockoutTimer) {
                clearInterval(lockoutTimer);
            }

            // Show the timer container
            $lockoutTimerDisplay.show();

            lockoutTimer = setInterval(function() {
                remainingSeconds--;

                // Calculate minutes and seconds
                var minutes = Math.floor(remainingSeconds / 60);
                var seconds = remainingSeconds % 60;

                // Format the time
                var timeDisplay = `Login Locked: ${minutes}m ${seconds}s remaining`;
                $lockoutTimerDisplay.text(timeDisplay);

                // When timer reaches zero
                if (remainingSeconds <= 0) {
                    clearInterval(lockoutTimer);
                    $lockoutTimerDisplay.hide();
                    $("#login-button").prop('disabled', false).text('LOGIN');
                    localStorage.removeItem('loginLockout');
                }
            }, 1000);
        }

        // Check login lockout status
        function checkLoginLockout() {
            var lockoutData = localStorage.getItem('loginLockout');
            if (lockoutData) {
                var lockoutInfo = JSON.parse(lockoutData);
                var currentTime = new Date().getTime();
                
                if (currentTime < lockoutInfo.lockoutExpiry) {
                    // Still locked out
                    var remainingSeconds = Math.ceil((lockoutInfo.lockoutExpiry - currentTime) / 1000);
                    
                    $("#login-button").prop('disabled', true)
                        .text('Locked');
                    
                    // Start the visible countdown timer
                    startLockoutTimer(remainingSeconds);
                }
            }
        }

        // Login form submission
        $("#login_form").submit(function(event) {
            event.preventDefault();

            // Check if login is currently locked out
            var lockoutData = localStorage.getItem('loginLockout');
            if (lockoutData) {
                var lockoutInfo = JSON.parse(lockoutData);
                var currentTime = new Date().getTime();
                
                if (currentTime < lockoutInfo.lockoutExpiry) {
                    Swal.fire({
                        title: 'Login Locked',
                        text: 'Too many failed attempts. Please try again later.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }

            grecaptcha.ready(function() {
                grecaptcha.execute('6LfRhIoqAAAAAGw8WMJ_Gd7hZGhdFVzvTNDAt8dw', {action: 'login'}).then(function(token) {
                    var data = $("#login_form").serialize() + "&g-recaptcha-response=" + token;

                    $.ajax({
                        data: data,
                        type: "post",
                        url: "backend/user.php",
                        success: function(dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            
                            if (dataResult.statusCode == 200) {
                                // Successful login - reset attempt tracking
                                localStorage.removeItem('loginAttempts');
                                
                                Swal.fire({
                                    title: 'Login successful!',
                                    text: 'You have successfully logged in.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.replace("index.php");
                                });
                            } else {
                                // Handle login failure
                                var attempts = JSON.parse(localStorage.getItem('loginAttempts') || '{"count": 0}');
                                attempts.count++;

                                if (attempts.count >= 3) {
                                    // Lockout for 3 minutes
                                    var lockoutExpiry = new Date().getTime() + (3 * 60 * 1000); // 3 minutes
                                    localStorage.setItem('loginLockout', JSON.stringify({
                                        lockoutExpiry: lockoutExpiry
                                    }));

                                    // Disable login button
                                    $("#login-button").prop('disabled', true)
                                        .text('Locked');

                                    // Start the visible countdown timer
                                    startLockoutTimer(3 * 60);

                                    Swal.fire({
                                        title: 'Too Many Attempts',
                                        text: 'Login locked for 3 minutes due to multiple failed attempts.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    // Store attempt count
                                    localStorage.setItem('loginAttempts', JSON.stringify(attempts));

                                    Swal.fire({
                                        title: 'Error!',
                                        text: dataResult.title + ` (Attempt ${attempts.count}/3)`,
                                        icon: 'error',
                                        confirmButtonText: 'Try again'
                                    });
                                }
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Connection Error',
                                text: 'Unable to process login. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            });
        });
    });
    </script>
</body>
</html>