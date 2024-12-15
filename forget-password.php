<?php
include('includes/layout-header.php');

if (isset($_SESSION["userId"])) {
    header("location: account.php");
    exit;
}

if (isset($_POST["forget-pass-submit"])) {
    $selector = bin2hex(random_bytes(8));
    $shortToken = bin2hex(random_bytes(8)); // Shorter token for URL
    $longToken = random_bytes(32); // Full-length token for storage

    $root_url = 'https://bantayanbusbooking.com/';
    $url = $root_url . "/create-new-password.php?selector=" . $selector . "&validator=" . $shortToken;

    $expires = date("U") + 1800;

    include('controllers/db.php');
    $database = new Database();
    $db = $database->getConnection();

    // Check if the `pwdReset` table exists, and create it if it doesn't
    $checkTableSql = "SHOW TABLES LIKE 'pwdReset'";
    $result = mysqli_query($db, $checkTableSql);

    if (mysqli_num_rows($result) == 0) {
        $createTableSql = "
            CREATE TABLE pwdReset (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                pwdResetEmail VARCHAR(255) NOT NULL,
                pwdResetSelector VARCHAR(255) NOT NULL,
                pwdResetToken TEXT NOT NULL,
                pwdResetExpires BIGINT(20) NOT NULL
            );
        ";

        if (!mysqli_query($db, $createTableSql)) {
            echo "Error creating table: " . mysqli_error($db);
            exit();
        }
    }

    include('controllers/passenger.php');
    $new_passenger = new Passenger($db);

    $userEmail = $_POST["email"];

    if ($new_passenger->isEmailExist($userEmail) == false) {
        header("location: forget-password.php?reset=emailNotExist");
        exit();
    }

    // Delete any existing reset request for the user
    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'There was an error! ' . mysqli_error($db);
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }

    // Insert new reset request
    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'There was an error! ' . mysqli_error($db);
        exit();
    } else {
        $hashedToken = password_hash($longToken, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);

    $to = $userEmail;

    $subject = 'Reset password';
    $message = '<p>We received a password reset request. Click the link below to reset your password:</p>';
    $message .= '<p><a href="' . $url . '" target="_blank">Reset Password</a></p>';
    $message .= '<p>If you did not request this, please ignore this email.</p>';

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    mail($to, $subject, $message, $headers);

    header("location: forget-password.php?reset=success");
}

$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}
?>


<style>
    .btn-glow {
    border: 5px solid transparent; /* Set the border to transparent initially */
    border-image: linear-gradient(to right, #00bf8f, #001510); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    border-image-slice: 1; /* This tells the browser to use the gradient for the border */
    transition: box-shadow 0.3s ease-in-out; /* Smooth transition for glow effect */
    position: relative;
}

.btn-glow:hover {
    box-shadow: 0 0 15px 5px #4e73df; /* Glowing effect */
}

</style>

<main>
    <div class="container mt-5">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px">
        <div class="bg-primary p-3" style="background: #F7971E;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #FFD200, #F7971E);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #FFD200, #F7971E); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
            <h1 class="text-center">Forget Password</h1>
        </div>


            <div class="p-3">
                <?php
                if (isset($_GET["reset"])) {
                    if ($_GET["reset"] == "success") {
                        echo '<div class="alert alert-success" role="alert">
                            Check your email for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder.
                        </div>';
                    } else if ($_GET["reset"] == "emailNotExist") {
                        echo '<div class="alert alert-danger" role="alert">
                            You are not registered yet. Please create an account first.
                        </div>';
                    }
                }
                ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                    
                    <button type="submit" class="btn btn-block btn-glow" name="forget-pass-submit">
    Request reset password
</button>

                    <div class="text-center">
                        <a href="login.php">Login instead</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('includes/scripts.php') ?>
<?php include('includes/layout-footer.php') ?>
