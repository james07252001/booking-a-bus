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
    $new_passenger = new Passenger($db);

    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $new_passenger->login($email, $password);
}
?>

<main>
    <div class="login-container d-flex align-items-center justify-content-center">
        <div class="w-50 bg-white shadow-sm p-4 login-form">
            <div class="bg-primary p-3" style="background-image: linear-gradient(109.6deg, rgba(254,253,205,1) 11.2%, rgba(163,230,255,1) 91.1%);">
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
                        }
                    }
                ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required />
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggle-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <a href="forget-password.php">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-block btn-dark" name="sign-in-submit">Login</button>
                    <div class="text-center">
                        <span>Not registered yet? </span>
                        <a href="register.php">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>

<style>
    /* Add background image to main and make the container full height */
    main {
        background-image: url('assets/img/boundary.jpg'); /* Replace with your image path */
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-form {
        width: 100%;
        max-width: 400px; /* Adjust the width for the form */
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    #toggle-password {
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }
</style>

<script>
    document.getElementById('toggle-password').addEventListener('click', function (e) {
        var password = document.getElementById('password');
        var icon = e.target;
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
