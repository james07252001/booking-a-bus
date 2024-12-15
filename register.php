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

    // Initialize error arrays
    $errors = [
        'first_name' => '',
        'last_name' => '',
        'address' => '',
        'phone_number' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => ''
    ];

    if(isset($_POST["sign-up-submit"])){
        $new_passenger = new Passenger($db);
        
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $phone_number = $_POST["phone_number"];
        $address = $_POST["address"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $terms_accepted = isset($_POST["terms_accepted"]) ? $_POST["terms_accepted"] : false;

        // Sanitize first name and last name (only letters, spaces, and optional period)
        if (!preg_match('/^[a-zA-Z\s\.]+$/', $first_name)) {
            $errors['first_name'] = "Name can only contain letters, spaces, and optional periods.";
        }

        if (!preg_match('/^[a-zA-Z\s\.]+$/', $last_name)) {
            $errors['last_name'] = "Name can only contain letters, spaces, and optional periods.";
        }

        // Sanitize phone number (must be 11 digits starting with '09')
        if (!preg_match('/^09\d{9}$/', $phone_number)) {
            $errors['phone_number'] = "Phone number must be exactly 11 digits starting with '09'.";
        }

        // Sanitize email (only allow @gmail.com)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
            $errors['email'] = "Email must be a valid Gmail address.";
        }

        // Validate password strength (must be at least 8 characters and contain only letters and numbers)
        if (!preg_match('/^[a-zA-Z0-9]{8,}$/', $password)) {
            $errors['password'] = "Password must be at least 8 characters and contain only numbers and letters.";
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        // Check if there are no errors before proceeding
        $hasErrors = false;
        foreach ($errors as $error) {
            if (!empty($error)) {
                $hasErrors = true;
                break;
            }
        }

        if (!$hasErrors) {
            // Proceed with creating the account
            $new_passenger->create($first_name, $last_name, $email, $phone_number, $address, $password);
        }

        if (!$terms_accepted) {
            $errors['terms'] = "You must accept the Terms and Conditions to register.";
        }
    }
?>

<main>
    <div class="container mt-3 main-container">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px">
            <div class="bg-primary p-3">
                <h1 class="text-center">Create an Account</h1>
            </div>

            <div class="p-3">
                <form method="POST" action="">
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control <?php echo !empty($errors['first_name']) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required />
                            <?php if(!empty($errors['first_name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control <?php echo !empty($errors['last_name']) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required />
                            <?php if(!empty($errors['last_name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address ?? ''); ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Contact Number</label>
                        <input type="text" class="form-control <?php echo !empty($errors['phone_number']) ? 'is-invalid' : ''; ?>" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number ?? ''); ?>" required />
                        <?php if(!empty($errors['phone_number'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['phone_number']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />
                        <?php if(!empty($errors['email'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group position-relative">
                        <label for="password">Password</label>
                        <input type="password" class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required />
                        <i id="togglePassword" class="fa fa-eye position-absolute" style="top: 42px; right: 10px; cursor: pointer;"></i>
                        
                        <!-- Password Strength Indicator -->
                        <div class="password-strength-container mt-2">
                            <div class="password-strength-bar d-flex">
                                <div id="strength-1" class="strength-segment flex-grow-1 mr-1"></div>
                                <div id="strength-2" class="strength-segment flex-grow-1 mr-1"></div>
                                <div id="strength-3" class="strength-segment flex-grow-1"></div>
                            </div>
                            <small id="password-strength-text" class="form-text text-muted">Password Strength: Weak</small>
                        </div>

                        <?php if(!empty($errors['password'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group position-relative">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control <?php echo !empty($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required />
                        <i id="toggleConfirmPassword" class="fa fa-eye position-absolute" style="top: 42px; right: 10px; cursor: pointer;"></i>
                        
                        <!-- Password Match Indicator -->
                        <div class="password-match-container mt-2">
                            <div class="password-match-bar d-flex">
                                <div id="match-indicator" class="match-segment flex-grow-1"></div>
                            </div>
                            <small id="password-match-text" class="form-text text-muted">Passwords do not match</small>
                        </div>

                        <?php if(!empty($errors['confirm_password'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms_accepted" name="terms_accepted">
                            <label class="custom-control-label" for="terms_accepted">
                                I have read and agree to the 
                                <a href="#" data-toggle="modal" data-target="#termsModal" style="color: brown;">Terms and Conditions</a>
                            </label>
                        </div>
                        <?php if(!empty($errors['terms'])): ?>
                            <div class="text-danger small mt-1"><?php echo $errors['terms']; ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-grad" name="sign-up-submit">Register</button>

                    <div class="text-center">
                        <span>Already have an account? </span>
                        <a href="login.php">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
</main>

<style>

body {
    background-image: url('assets/img/bantayanplaza.png');
    background-size: cover; /* Ensures the image covers the entire page */
    background-position: center; /* Centers the background image */
    background-attachment: fixed; /* Keeps the image fixed when scrolling */
    color: black; /* Adjust text color if needed */
}

.main-container {
    background: rgba(255, 255, 255, 0); /* Optional: Add a transparent background for content readability */
}

.p-3 {
    background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

/* Background gradient for the register button */
.btn-grad {
    background-image: linear-gradient(to right, #403B4A 0%, #E7E9BB 51%, #403B4A 100%);
    margin: 10px;
    padding: 15px 45px;
    text-align: center;
    text-transform: uppercase;
    transition: 0.5s;
    background-size: 200% auto;
    color: white;
    box-shadow: 0 0 20px #eee;
    border-radius: 10px;
    display: block;
    position: relative; /* Required for the glow effect */
    overflow: hidden;
    width: 500px;
}

/* Glowing LED border effect */
.btn-grad::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, #ff00ff, #00ffff, #ffff00);
    z-index: -1;
    filter: blur(8px);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    border-radius: 10px;
}

/* Hover effect: glowing LED and gradient movement */
.btn-grad:hover::before {
    opacity: 1;
}

.btn-grad:hover {
    background-position: right center; /* change the direction of the gradient shift */
    color: #fff;
    text-decoration: none;
}



    .password-strength-bar .strength-segment,
    .password-match-bar .match-segment {
        height: 5px;
        background-color: #e0e0e0;
        transition: background-color 0.3s ease;
    }

    .password-strength-bar .strength-segment.weak {
        background-color: #ff4136;
    }

    .password-strength-bar .strength-segment.medium {
        background-color: #ff851b;
    }

    .password-strength-bar .strength-segment.strong {
        background-color: #2ecc40;
    }

    .password-match-bar .match-segment.match {
        background-color: #2ecc40;
    }

    .password-match-bar .match-segment.no-match {
        background-color: #ff4136;
    }
</style>


<script>
// Terms and Conditions Checkbox Handling
document.addEventListener('DOMContentLoaded', function() {
        const termsCheckbox = document.getElementById('terms_accepted');
        const registerButton = document.getElementById('registerButton');
        const closeTermsModal = document.getElementById('closeTermsModal');

        termsCheckbox.addEventListener('change', function() {
            registerButton.disabled = !this.checked;
        });

        // Optional: Add close modal logic if needed
        closeTermsModal.addEventListener('click', function() {
            // You can add any additional logic here before closing the modal
            // For now, it will just close the modal due to data-bs-dismiss
        });
    });

    // Show password functionality using eye icon for password and confirm password
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmPasswordField = document.getElementById('confirm_password');
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });

    // Password Strength and Match Indicators
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthSegments = [
            document.getElementById('strength-1'),
            document.getElementById('strength-2'),
            document.getElementById('strength-3')
        ];
        const strengthText = document.getElementById('password-strength-text');
        const confirmPassword = document.getElementById('confirm_password').value;

        // Password strength calculation
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;

        // Reset segments
        strengthSegments.forEach(segment => {
            segment.classList.remove('weak', 'medium', 'strong');
        });

        // Color and text based on strength
        if (strength === 0 || password.length < 8) {
            strengthSegments[0].classList.add('weak');
            strengthText.textContent = 'Password Strength: Weak';
            strengthText.style.color = '#ff4136';
        } else if (strength === 1) {
            strengthSegments[0].classList.add('weak');
            strengthSegments[1].classList.add('weak');
            strengthText.textContent = 'Password Strength: Moderate';
            strengthText.style.color = '#ff851b';
        } else {
            strengthSegments[0].classList.add('strong');
            strengthSegments[1].classList.add('strong');
            strengthSegments[2].classList.add('strong');
            strengthText.textContent = 'Password Strength: Strong';
            strengthText.style.color = '#2ecc40';
        }

        // Check password match if confirm password is not empty
        if (confirmPassword) {
            checkPasswordMatch();
        }
    });

    document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const matchIndicator = document.getElementById('match-indicator');
        const matchText = document.getElementById('password-match-text');

        matchIndicator.classList.remove('match', 'no-match');
        
        if (password === confirmPassword && password !== '') {
            matchIndicator.classList.add('match');
            matchText.textContent = 'Passwords match';
            matchText.style.color = '#2ecc40';
        } else {
            matchIndicator.classList.add('no-match');
            matchText.textContent = 'Passwords do not match';
            matchText.style.color = '#ff4136';
        }
    }
</script>

<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>