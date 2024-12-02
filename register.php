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

    if(isset($_POST["sign-up-submit"])){
        $new_passenger = new Passenger($db);
        
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $address = $_POST["address"];
        $password = $_POST["password"];
        $agree_terms = isset($_POST["agree_terms"]); // Check if the checkbox is checked
        
        // Regular expressions for validation
        $name_pattern = "/^[a-zA-Z]+$/";
        $address_pattern = "/^[a-zA-Z\s]+$/"; // Allows spaces for address

        // Validation
        if (!preg_match($name_pattern, $first_name)) {
            $error = "First name can only contain letters.";
        } elseif (!preg_match($name_pattern, $last_name)) {
            $error = "Last name can only contain letters.";
        } elseif (!preg_match($address_pattern, $address)) {
            $error = "Address can only contain letters and spaces.";
        } elseif (strlen($password) < 7) {
            $error = "Password must be at least 7 characters long.";
        } elseif (!$agree_terms) { // Check if the terms are agreed
            $error = "You must agree to the terms and conditions.";
        } else {
            // Create the new passenger
            $new_passenger->create($first_name, $last_name, $email, $address, $password);
            header("Location: success.php");
            exit;
        }
    }
?>

<main>
    <div class="signup-container d-flex align-items-center justify-content-center">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px; ">
            <div class="bg-primary p-3" style="background: rgb(51,122,183);background: radial-gradient(circle, rgba(51,122,183,1) 0%, rgba(4,92,167,1) 50%, rgba(0,137,255,1) 100%);">
                <h1 class="text-center">Create an Account</h1>
            </div>

            <div class="p-3" style="white">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                    }
                    if(isset($_GET["error"])){
                        if($_GET["error"] == "emailExist"){
                            echo '<div class="alert alert-danger" role="alert">Email already exists.</div>';
                        } else if($_GET["error"] == "stmtfailed"){
                            echo '<div class="alert alert-danger" role="alert">Error creating an account.</div>';
                        }
                    }
                ?>

                <form method="POST" action="" id="signupForm">
                    <div class="form-group">
                        <label for="first_name" style="color: black; font-weight: bold">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required />
                        <small id="firstNameError" class="form-text text-danger" style="display:none;">First name can only contain letters.</small>
                    </div>
                    <div class="form-group">
                        <label for="last_name" style="color: black; font-weight: bold">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required />
                        <small id="lastNameError" class="form-text text-danger" style="display:none;">Last name can only contain letters.</small>
                    </div>
                    <div class="form-group">
                        <label for="address" style="color: black; font-weight: bold">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required />
                    </div>
                    <div class="form-group">
                        <label for="email" style="color: black; font-weight: bold">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="password" style="color: black; font-weight: bold">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required />
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" id="toggle-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" style="color: black; font-weight: bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required />
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" id="toggle-confirm-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required />
                        <label class="form-check-label" for="agree_terms">I agree to the <a href="#" id="termsLink" style="color: skyblue;">terms and conditions</a></label>
                    </div> -->
                    <button type="submit" class="btn btn-block glow-button" name="sign-up-submit">Register</button>

                    <div class="text-center" style="color: black; font-weight: bold">
                        <span>Already have an account? </span>
                        <a href="login.php" style="color: skyblue; font-weight: bold">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Modal for Terms and Conditions -->
<!-- <div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Terms and Conditions</h2>
        <p> -->
            <!-- Your terms and conditions text goes here -->
            <!--These terms and conditions outline the rules and regulations for the use of Our Service. 
            By accessing or using the Service, you agree to be bound by these terms.
        </p>
        <p> -->
            <!-- Add more text as needed -->
           <!--  If you do not agree with any part of the terms, you must not use our Service.
        </p>
    </div>
</div> -->

<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>

<script>
    // Modal handling
    var modal = document.getElementById("termsModal");
    var termsLink = document.getElementById("termsLink");
    var span = document.getElementsByClassName("close")[0];

    termsLink.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Real-time validation for first name and last name fields
    document.getElementById('first_name').addEventListener('input', function() {
        var firstName = this.value;
        var firstNameError = document.getElementById('firstNameError');
        if (/[^a-zA-Z]/.test(firstName)) {
            firstNameError.style.display = 'block';  // Show error message
        } else {
            firstNameError.style.display = 'none';  // Hide error message
        }
    });

    document.getElementById('last_name').addEventListener('input', function() {
        var lastName = this.value;
        var lastNameError = document.getElementById('lastNameError');
        if (/[^a-zA-Z]/.test(lastName)) {
            lastNameError.style.display = 'block';  // Show error message
        } else {
            lastNameError.style.display = 'none';  // Hide error message
        }
    });

    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function(e) {
            var passwordField = (icon.id === 'toggle-password') ? document.getElementById('password') : document.getElementById('confirm_password');
            var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            icon.querySelector('i').classList.toggle('fa-eye');
            icon.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>

<style>
    /* Modal Styles */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgb(0,0,0); 
        background-color: rgba(0,0,0,0.4); 
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; 
        padding: 20px;
        border: 1px solid #888;
        width: 80%; 
        max-width: 600px; 
        border-radius: 5px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    main {
        background-image: url('assets/img/d3.png'); /* Replace with your image path */
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .signup-container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .glow-button {
        position: relative;
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        background-image: linear-gradient(-20deg, #337ab7 0%, #337ab7 100%);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        outline: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden; /* To clip the pseudo-elements */
    }

    .glow-button:before,
    .glow-button:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0) 100%);
        transition: all 0.5s ease;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        z-index: 0;
    }

    .glow-button:before {
        animation: ledGlow 1.5s infinite;
    }

    .glow-button:hover:before,
    .glow-button:active:before {
        transform: translate(-50%, -50%) scale(1);
    }

    .glow-button:hover {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }

    .glow-button:focus {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
    }

    @keyframes ledGlow {
        0%, 100% {
            opacity: 0.6;
        }
        50% {
            opacity: 1;
        }
    }

</style>
