<?php
include('includes/layout-header.php');

if (isset($_SESSION["userId"])) {
    header("location: account.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["reset-password-submit"])) {
    // Extract POST data
    $selector = $_POST["selector"] ?? null;
    $validator = $_POST["validator"] ?? null;
    $pwd = $_POST["pwd"] ?? null;
    $pwdRepeat = $_POST["pwdRepeat"] ?? null;

    // Validation: Ensure passwords are provided
    if (empty($pwd) || empty($pwdRepeat)) {
        header("location: create-new-password.php?selector=$selector&validator=$validator&newPwd=empty");
        exit();
    }

    // Validation: Ensure passwords match
    if ($pwd !== $pwdRepeat) {
        header("location: create-new-password.php?selector=$selector&validator=$validator&newPwd=mismatchPwd");
        exit();
    }

    include('controllers/db.php');
    $database = new Database();
    $conn = $database->getConnection();

    $currentDate = date("U");

    // Validate token and selector
    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector = ? AND pwdResetExpires >= ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Database error.";
        exit();
    }
    $stmt->bind_param("ss", $selector, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Convert validator to binary and verify token
        $tokenBin = hex2bin($validator);
        $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

        if ($tokenCheck) {
            // Extract user email from the reset request
            $tokenEmail = $row["pwdResetEmail"];

            // Hash the new password
            $newPwdHash = password_hash($pwd, PASSWORD_DEFAULT);

            // Update the user's password
            $sql = "UPDATE tblpassenger SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "Database error.";
                exit();
            }
            $stmt->bind_param("ss", $newPwdHash, $tokenEmail);
            if ($stmt->execute()) {
                // Delete the reset token after password reset
                $sql = "DELETE FROM pwdReset WHERE pwdResetEmail = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $tokenEmail);
                $stmt->execute();

                // Redirect to login with success message
                header("location: login.php?newpwd=passwordUpdated");
                exit();
            } else {
                echo "Failed to update password. Please try again.";
                exit();
            }
        } else {
            header("location: create-new-password.php?selector=$selector&validator=$validator&newPwd=invalid");
            exit();
        }
    } else {
        header("location: create-new-password.php?selector=$selector&validator=$validator&newPwd=invalid");
        exit();
    }
}
?>

<main>
    <div class="container mt-5">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px">
            <div class="bg-primary p-3">
                <h1 class="text-center">Create New Password</h1>
            </div>
            <div class="p-3">
                <?php
                // Display error messages based on the query parameters
                if (isset($_GET["newPwd"])) {
                    if ($_GET["newPwd"] === "empty") {
                        echo '<div class="alert alert-danger" role="alert">Password fields cannot be empty.</div>';
                    } elseif ($_GET["newPwd"] === "mismatchPwd") {
                        echo '<div class="alert alert-danger" role="alert">Passwords do not match.</div>';
                    } elseif ($_GET["newPwd"] === "invalid") {
                        echo '<div class="alert alert-danger" role="alert">
                            Invalid request. Please re-submit your password reset request.
                            <a href="forget-password.php">Go to reset password</a>
                        </div>';
                    }
                }

                // Display the form if valid tokens are provided
                if (!empty($_GET["selector"]) && !empty($_GET["validator"]) &&
                    ctype_xdigit($_GET["selector"]) && ctype_xdigit($_GET["validator"])) {
                ?>
                    <form method="POST" action="">
                        <input type="hidden" name="selector" value="<?php echo htmlspecialchars($_GET["selector"]); ?>" />
                        <input type="hidden" name="validator" value="<?php echo htmlspecialchars($_GET["validator"]); ?>" />

                        <div class="form-group">
                            <label for="pwd">Password</label>
                            <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter new password" required />
                        </div>
                        <div class="form-group">
                            <label for="pwdRepeat">Confirm Password</label>
                            <input type="password" class="form-control" id="pwdRepeat" name="pwdRepeat" placeholder="Repeat new password" required />
                        </div>
                        <button type="submit" class="btn btn-dark btn-block" name="reset-password-submit">Create Password</button>
                    </form>
                <?php
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                        Invalid request. Please <a href="register.php">start the registration process</a> again.
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include('includes/scripts.php'); ?>
<?php include('includes/layout-footer.php'); ?>

<style>
    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }

        .w-100 {
            width: 100%;
        }

        .bg-primary {
            padding: 15px;
        }

        .form-group label {
            font-size: 14px;
        }

        .form-control {
            font-size: 14px;
            padding: 10px;
        }

        .btn-block {
            font-size: 16px;
            padding: 12px;
        }
    }
</style>
