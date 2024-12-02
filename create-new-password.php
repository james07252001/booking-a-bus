<?php
    include('includes/layout-header.php');
    
    if(isset($_SESSION["userId"])){
        header("location: account.php");
        exit;
    }

    if(isset($_POST["reset-password-submit"])){
        if(empty($_POST["pwd"]) || empty($_POST["pwdRepeat"])){
            header("location: create-new-password.php?selector=".$_POST["selector"]."&validator=".$_POST["validator"]."&newPwd=empty");
            exit();
        }
        
        if($_POST["pwd"] !== $_POST["pwdRepeat"]){
            header("location: create-new-password.php?selector=". $_POST["selector"] . "&validator=". $_POST["validator"] ."&newPwd=mismatchPwd");
            exit();
        }
    
        $selector = $_POST["selector"];
        $validator = $_POST["validator"];
        $pwd = $_POST["pwd"];
        $pwdRepeat = $_POST["pwdRepeat"];
    
        $currentDate = date("U");
        
        include('controllers/db.php');
        $database = new Database();
        $conn = $database->getConnection();
    
        include('controllers/passenger.php');
        $new_passenger = new Passenger($conn);
    
        $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector = ? AND pwdResetExpires >= ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "There was an error.";
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
            mysqli_stmt_execute($stmt);
    
            $result = mysqli_stmt_get_result($stmt);
            if(!$row = mysqli_fetch_assoc($result)){
                header("location: create-new-password.php?selector=".$selector."&validator=".$validator."&newPwd=invalid");
                exit();
            }else{
                $tokenBin = hex2bin($validator);
                $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);
    
                if($tokenCheck == false){
                    header("location: create-new-password.php?selector=".$selector."&validator=".$validator."&newPwd=invalid");
                    exit();
                }else if($tokenCheck == true){
                    $tokenEmail = $row["pwdResetEmail"];
    
                    $sql = "SELECT * FROM tblpassenger WHERE email = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo "There was an error.";
                        exit();
                    }else{
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);
    
                        $result = mysqli_stmt_get_result($stmt);
    
                        if(!$row = mysqli_fetch_assoc($result)){
                            header("location: create-new-password.php?selector=".$selector."&validator=".$validator."&newPwd=invalid");
                            exit();
                        }else{
                            $sql = "UPDATE tblpassenger SET password = ? WHERE email = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                echo "There was an error.";
                                exit();
                            }else{
                                $newPwdHash = password_hash($pwd, PASSWORD_DEFAULT);
    
                                mysqli_stmt_bind_param($stmt, "ss",$newPwdHash, $tokenEmail);
                                mysqli_stmt_execute($stmt);
    
                                $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                                $stmt = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmt, $sql)){
                                    echo 'There was an error!';
                                    exit();
                                }else{
                                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                    mysqli_stmt_execute($stmt);
    
                                    header("location: login.php?newpwd=passwordUpdated");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>

<main>
    <div class="container mt-5">
        <div class="w-100 m-auto bg-white shadow-sm" style="max-width: 500px">
            <div class="bg-primary p-3">
                <h1 class="text-center">Create new password</h1>
            </div>

            <div class="p-3">
                <?php
                    if(empty($_GET["selector"]) || empty($_GET["validator"])){
                        echo '
                        <div class="alert alert-danger" role="alert">
                            <span>Could not validate your request. </span>
                            <a href="forget-password.php">Go to reset password</a>
                        </div>
                        ';
                    }else{
                        $selector = $_GET["selector"];
                        $validator = $_GET["validator"];
                    
                        if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
                            if(isset($_GET["newPwd"])){
                                if($_GET["newPwd"] == "mismatchPwd"){
                                    echo '<div class="alert alert-danger" role="alert">
                                    Mismatch password.
                                </div>';
                                }else if($_GET["newPwd"] == "invalid"){
                                    echo '<div class="alert alert-danger" role="alert">
                                    <span>Invalid! You need to re-submit your reset password request.</span>
                                    <a href="forget-password.php">Go to reset password</a>
                                </div>';
                                }
                            }
                            ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="selector" value="<?php echo $selector ?>" />
                                    <input type="hidden" name="validator" value="<?php echo $validator ?>" />

                                    <div class="form-group">
                                        <label for="pwd">Password</label>
                                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder=" " required />
                                    </div>
                                    <div class="form-group">
                                        <label for="pwdRepeat">Confirm Password</label>
                                        <input type="password" class="form-control" id="pwdRepeat" name="pwdRepeat" placeholder=" "
                                            required />
                                    </div>

                                    <button type="submit" class="btn btn-block btn-dark" name="reset-password-submit">Reset
                                        Password</button>

                                    <!-- <div class="text-center">
                                        <a href="login.php" class="btn btn-link">Log in instead.</a>
                                    </div> -->
                                </form>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>