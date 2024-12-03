<?php 
include('includes/layout-header.php');

if (isset($_SESSION["userId"])) {
    header("location: account.php");
    exit;
}

include('controllers/db.php');
include('controllers/passenger.php');

$database = new Database();
$db = $database->getConnection();

if (isset($_POST["sign-up-submit"])) {
    $new_passenger = new Passenger($db);

    // Sanitize user inputs to prevent XSS attacks
    $first_name = htmlspecialchars(trim($_POST["first_name"]), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars(trim($_POST["last_name"]), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone_number = trim($_POST["phone_number"]);
    $address = htmlspecialchars(trim($_POST["address"]), ENT_QUOTES, 'UTF-8');
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $terms_agreed = isset($_POST["terms"]) ? true : false;

    // Input validation patterns
    $name_pattern = "/^[a-zA-ZñÑ\s]+$/";
    $address_pattern = "/^[a-zA-ZñÑ\s,]+$/";
    $phone_pattern = "/^09[0-9]{9}$/";

    // Validate inputs
    if (!preg_match($name_pattern, $first_name)) {
        $error = "First name can only contain letters.";
    } elseif (!preg_match($name_pattern, $last_name)) {
        $error = "Last name can only contain letters.";
    } elseif (!preg_match($address_pattern, $address)) {
        $error = "Address can only contain letters, spaces, and commas.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match($phone_pattern, $phone_number)) {
        $error = "Invalid phone number format. It must start with 09 and contain exactly 11 digits.";
    } elseif (strlen($password) < 7) {
        $error = "Password must be at least 7 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!$terms_agreed) {
        $error = "You must agree to the Terms and Conditions.";
    } else {
        // Choose password hashing algorithm (Argon2i or bcrypt)
        $use_argon2 = true; // Change to false to use bcrypt
        if ($use_argon2 && defined('PASSWORD_ARGON2I')) {
            $hashed_password = password_hash($password, PASSWORD_ARGON2I);
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        }

        // Attempt to create a new user
        if ($new_passenger->create($first_name, $last_name, $email, $phone_number, $address, $hashed_password)) {
            header("Location: success.php");
            exit;
        } else {
            $error = "Failed to create account. Please try again.";
        }
    }
}

$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

?>

<main>
    <div class="signup-container d-flex align-items-center justify-content-center">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px;">
            <div class="bg-primary p-3 header-gradient">
                <h1 class="text-center">Create an Account</h1>
            </div>

            <div class="p-3">
                <?php
                if (isset($error)) {
                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
                }
                ?>

                <form method="POST" action="" id="signupForm">
                    <div class="form-group">
                        <label for="first_name" class="label-bold">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required />
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="label-bold">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required />
                    </div>
                    <div class="form-group">
                        <label for="address" class="label-bold">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required />
                    </div>
                    <div class="form-group">
                        <label for="email" class="label-bold">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="label-bold">Phone</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}" maxlength="11" inputmode="numeric" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="label-bold">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required minlength="7" />
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" id="toggle-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="label-bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="7" />
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" id="toggle-confirm-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="form-group">
                        <label class="form-check-label" for="terms">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            I agree to the <a href="#" id="terms-link">Terms and Conditions</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-block glow-button" name="sign-up-submit">Register</button>

                    <div class="text-center mt-3 label-bold">
                        <span>Already have an account? </span>
                        <a href="login" class="link-skyblue">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Modal for Terms and Conditions -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Terms and Conditions</strong></p>
        <p><strong>1. Booking & Payment</strong></p>
        <p>• All booking must be made through our website and mobile application.</p>
        <p>• Payment is required to confirm your confirm your booking. Prices are displayed at the time of booking.</p>
        <p><strong>2. Cancellation</strong></p>
        <p>• You can cancel your booking before the scheduled departure.</p>
        <p>• Cancellation may incur a fee or no refund depending on the policy.</p>
        <p>• No refunds are available after the bus departs.</p>
        <p><strong>3. Changes to Bookings</strong></p>
        <p>• You cannot change your booking once you book but you can cancel before the administrator accepts or confirms your booking.</p>
        <p><strong>4. Passenger Responsibility</strong></p>
        <p>• Ensure you have a valid ID and booking confirmation for travel.</p>
        <p>• Follow all safety guidelines and regulations during travel.</p>
        <p><strong>5. Bus Schedule</strong></p>
        <p>• We strive to maintain on-time departures, but delays may occur due to traffic or other factors. We are not liable for delays or missed connections.</p>
        <p><strong>6. Prohibited Items</strong></p>
        <p>Dangerous or illegal items are not allowed on the bus. The Company reserves the right to refuse service to passengers with prohibited items.</p>
        <p><strong>7. Liability</strong></p>
        <p>• We are not responsible for personal injury, loss, or damage to property during your journey, except where required by law.</p>
        <p><strong>8. Privacy</strong></p>
        <p>• By booking you agree to our Privacy Policy regarding how your personal information is collected and used.</p>
        <p><strong>9. Changes to Terms</strong></p>
        <p>• We may update these Terms at any time. Any changes will be posted on our website and will be effective immediately.</p>
        <p><strong>10. Governing Law</strong></p>
        <p>These Terms are governed by the laws of Bantayan Island.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<?php include('includes/scripts.php') ?>
<?php include('includes/layout-footer.php') ?>

<script>
// Updated validation to accept letters, spaces, and ñ
document.getElementById('first_name').addEventListener('input', function() {
    const firstName = this.value;
    const firstNameError = document.getElementById('firstNameError');
    if (/[^a-zA-ZñÑ\s]/.test(firstName)) {
        firstNameError.style.display = 'block';
    } else {
        firstNameError.style.display = 'none';
    }
});

// JavaScript to handle Terms and Conditions popup
document.getElementById('terms-link').addEventListener('click', function(e) {
    e.preventDefault();
    var termsModal = new bootstrap.Modal(document.getElementById('termsModal'));
    termsModal.show();
});

// Optional: Close modal manually if needed
var closeButton = document.querySelector('[data-bs-dismiss="modal"]');
closeButton.addEventListener('click', function() {
    var termsModal = bootstrap.Modal.getInstance(document.getElementById('termsModal'));
    termsModal.hide();
});
document.getElementById('last_name').addEventListener('input', function() {
    const lastName = this.value;
    const lastNameError = document.getElementById('lastNameError');
    if (/[^a-zA-ZñÑ\s]/.test(lastName)) {
        lastNameError.style.display = 'block';
    } else {
        lastNameError.style.display = 'none';
    }
});

// Updated address validation to allow letters, spaces, ñ, and commas
document.getElementById('address').addEventListener('input', function() {
    const address = this.value;
    if (/[^a-zA-ZñÑ\s,]/.test(address)) {
        alert("Address can only contain letters, spaces, ñ, and commas.");
    }
});

// Password match validation with color feedback
const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('confirm_password');
const message = document.createElement('small'); // Create a small element for the message
message.style.display = 'block'; // Ensure the message is displayed below the input
confirmPasswordField.parentNode.appendChild(message); // Add the message element below the confirm password field

confirmPasswordField.addEventListener('input', function() {
    const password = passwordField.value;
    const confirmPassword = confirmPasswordField.value;

    if (password === confirmPassword && confirmPassword !== '') {
        // Passwords match
        confirmPasswordField.style.borderColor = 'green';
        message.textContent = 'Passwords match';
        message.style.color = 'green';
    } else {
        // Passwords do not match
        confirmPasswordField.style.borderColor = 'red';
        message.textContent = 'Passwords do not match';
        message.style.color = 'red';
    }
});

// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(function(icon) {
    icon.addEventListener('click', function() {
        const passwordInput = icon.id === 'toggle-password' ? passwordField : confirmPasswordField;
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        icon.querySelector('i').classList.toggle('fa-eye');
        icon.querySelector('i').classList.toggle('fa-eye-slash');
    });
});

document.getElementById('phone_number').addEventListener('input', function() {
    // Allow only numeric input and prevent letters, symbols, and spaces
    this.value = this.value.replace(/[^0-9]/g, ''); // Removes any non-digit characters
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>


<style>
    main {
    background-image: url('assets/img/d3.png');
    background-size: cover;
    background-position: center;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.signup-container {
    width: 100%;
    max-width: 500px;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.header-gradient {
    background: radial-gradient(circle, rgba(51, 122, 183, 1) 0%, rgba(4, 92, 167, 1) 50%, rgba(0, 137, 255, 1) 100%);
    text-align: center;
    padding: 15px;
    color: #fff;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.glow-button {
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    background-image: linear-gradient(-20deg, #337ab7 0%, #337ab7 100%);
    border: none;
    border-radius: 5px;
    margin-top: 20px;
}

.label-bold {
    font-weight: bold;
    color: #333;
}

.link-skyblue {
    color: #337ab7;
    font-weight: bold;
}

.alert {
    margin-bottom: 20px;
}

.form-check-label {
    margin-bottom: 0;
    margin-left: 23%;
}


/* Styling for the password match message */
small {
    font-size: 0.9em;
    margin-top: 5px;
}


@media (max-width: 576px) {
    .signup-container {
        padding: 15px;
    }

    h1 {
        font-size: 1.5rem;
    }
}

</style>