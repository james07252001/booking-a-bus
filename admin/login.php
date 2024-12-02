<?php
    session_start();
    include 'dbconfig.php';

    if(isset($_SESSION["userId"])){
        header("location: index.php");
        exit;
    }
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="icon" href="../assets/img/bus.ico" type="image/ico">
    <title>Bantayan Online Bus Reservation</title>
</head>

<body>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/styles.css" />

    <title>Bantayan Online Bus Reservation System</title>

  </head>
  <body class="bg-light">

  <!-- Just an image -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" style="background-image: linear-gradient(-20deg, #b721ff 0%, #21d4fd 100%);">
  <div class="container">
    <a class="navbar-brand" href="index.php" style="font-family: 'Times New Roman', serif;">
    
    </a></b>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
    <li class="nav-item">
              <a class="nav-link" href="/bantayanbus/index.php"> <i class="fa fa-home w3-large " style="color: black"> <b>Home</a></b></i>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="/bantayanbus/login.php"> <i class="fa fa-user icon w3-large " style="color: black"> <b>User</a></b></i>
              </li>
       
    </ul>
  </div>
  </div>
</nav>
    <div style="width: 100vw; height: 80vh" class="bg-light">
        <div class="h-100 d-flex flex-column justify-content-center align-items-center" style="background-color: #FFDEE9; background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);">
            <div class="container">
                <div class="w-100 m-auto" style="max-width: 500px;">
                    <div class="bg-white rounded shadow p-3" style="background: #ADA996; background: -webkit-linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996);  background: linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996);">
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
                            <button type="submit" class="btn btn-block btn-primary" style="font-family: 'Times New Roman', serif; text-size: 20px;">LOGIN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/jquery.dataTables.min.js"></script>

    <!-- Script to toggle password visibility -->
    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $("#login_form").submit(function(event) {
            event.preventDefault();
            var data = $("#login_form").serialize();
            console.log(data);

            $.ajax({
                data: data,
                type: "post",
                url: "backend/user.php",
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        alert("Login successfully!");
                        window.location.replace("index.php");
                    } else {
                        alert(dataResult.title);
                    }
                }
            });
        });
    </script>
</body>

</html>
