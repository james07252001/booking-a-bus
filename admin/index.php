<?php
    session_start();
    include 'dbconfig.php';

    if(!isset($_SESSION["userId"])){
        header("location: login.php");
        exit;
    }

    // else{
    //     if($_SESSION["userIsAdmin"] == "0"){
    //         header("location: backend/logout.php");
    //     }else{
    //         if($_SESSION["userEmailVerifiedAt"] == false){
    //             header("location: verifyEmail.php");
    //         }
    //     }
    // }

     // Fetch pending bookings
     $query = "SELECT * FROM tblbook WHERE payment_status = 'pending'";
     $result = mysqli_query($conn, $query);
     $pendingBookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
     $pendingCount = count($pendingBookings);

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/adminStyles.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../assets/img/bus.ico" type="image/ico">
    <title>Bantayan Online Bus Reservation</title>

     <style>
        body {
            background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url(../assets/img/buscv1.jpg);
            min-height: 100vh;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .dropdown, .dropleft, .dropright, .dropup {
    position: relative;
    left: 325px;
    top:-35px;
}
    </style>


  
    </style>
</head>

<body class="bg-light">
    <script src="../assets/bootstrap/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/jquery.dataTables.min.js"></script>

    <div class="wrapper">
        <div class="navigation" style="background-image: linear-gradient(-20deg, #b721ff 0%, #21d4fd 100%);">
            <ul class="nav flex-column" style="background-image: linear-gradient(-20deg, #b721ff 0%, #21d4fd 100%);">
                <center><img src="../assets/images/bobrs.png" class="w3-circle" alt="" style="width:50%"></center>
                
             <li>
                   <a class="nav-item <?php if((isset($_GET['page']) && $_GET['page'] === 'dashboard') || empty($_GET['page'])) echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php">
                        <span class="icon">

                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                                    d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'booking') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=booking">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px"
                                viewBox="0 0 24 24" width="24px" fill="#FFFF">
                                <rect fill="none" height="24" width="24" />
                                <path
                                    d="M17,4H7V3h10V4z M17,21H7v-1h10V21z M17,1H7C5.9,1,5,1.9,5,3v18c0,1.1,0.9,2,2,2h10c1.1,0,2-0.9,2-2V3C19,1.9,18.1,1,17,1 L17,1z M7,6h10v12H7V6z M16,11V9.14C16,8.51,15.55,8,15,8H9C8.45,8,8,8.51,8,9.14l0,1.96c0.55,0,1,0.45,1,1c0,0.55-0.45,1-1,1 l0,1.76C8,15.49,8.45,16,9,16h6c0.55,0,1-0.51,1-1.14V13c-0.55,0-1-0.45-1-1C15,11.45,15.45,11,16,11z M12.5,14.5h-1v-1h1V14.5z M12.5,12.5h-1v-1h1V12.5z M12.5,10.5h-1v-1h1V10.5z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Bookings</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'schedules') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=schedules">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M7 11h2v2H7v-2zm14-5v14c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2l.01-14c0-1.1.88-2 1.99-2h1V2h2v2h8V2h2v2h1c1.1 0 2 .9 2 2zM5 8h14V6H5v2zm14 12V10H5v10h14zm-4-7h2v-2h-2v2zm-4 0h2v-2h-2v2z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Schedules</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'routes') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=routes">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z" />
                                <circle cx="12" cy="9" r="2.5" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Routes</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'location') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=location">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z" />
                                <circle cx="12" cy="9" r="2.5" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Locations</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'bus') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=bus">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="32px"
                                viewBox="0 0 20 20" width="32px" fill="#FFF">
                                <g>
                                    <rect fill="none" height="20" width="20" x="0" />
                                </g>
                                <g>
                                    <g />
                                    <g>
                                        <polygon points="8,5 11,5 11,8 12,8 12,4 7,4 7,7 4,7 4,16 5,16 5,8 8,8" />
                                        <rect height="1" width="1" x="6" y="9" />
                                        <rect height="1" width="1" x="9" y="6" />
                                        <rect height="1" width="1" x="6" y="11" />
                                        <rect height="1" width="1" x="6" y="13" />
                                        <path
                                            d="M15.11,9.34C15.05,9.14,14.85,9,14.64,9H9.36C9.15,9,8.95,9.14,8.89,9.34L8,12v2v0.5V15v0.5C8,15.78,8.22,16,8.5,16 S9,15.78,9,15.5V15h6v0.5c0,0.28,0.22,0.5,0.5,0.5s0.5-0.22,0.5-0.5V15v-0.5V14v-2L15.11,9.34z M9.72,10h4.56l0.67,2H9.05L9.72,10 z M9.5,14C9.22,14,9,13.78,9,13.5S9.22,13,9.5,13s0.5,0.22,0.5,0.5S9.78,14,9.5,14z M14.5,14c-0.28,0-0.5-0.22-0.5-0.5 s0.22-0.5,0.5-0.5s0.5,0.22,0.5,0.5S14.78,14,14.5,14z" />
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Bus</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'driver') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=driver">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Drivers</span>
                    </a>
                </li>
                <li
                    class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'conductor') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=conductor">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#ffff">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Conductors</span>
                    </a>
                </li>
                
                <li
                    class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'customer') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=customer">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Passengers</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'users') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=users">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                fill="#FFF">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </span>
                        <span class="title" style="color: white; font-weight: bold">Users</span>
                    </a>
                </li>
                <li class="nav-item <?php if(isset($_GET['page']) && $_GET['page'] === 'reports') echo 'bg-primary' ?>">
                    <a class="nav-link text-white" href="index.php?page=reports">
                        <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFF">
    <path d="M0 0h24v24H0V0z" fill="none"/>
    <path d="M12 2a2 2 0 0 1 2 2h6v16H4V4h6a2 2 0 0 1 2-2zm0 2H6v14h12V6h-6V4zm1 4h4v2h-4V8zm0 4h4v2h-4v-2zm-6-4h4v2H7V8zm0 4h4v2H7v-2z"/>
</svg>

                        </span>
                        <span class="title" style="color: white; font-weight: bold">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main">
            <nav class="navbar navbar-light bg-white justify-content-between">
                <a class="navbar-brand toggle" href="#" onclick="toggleMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                        fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
                    </svg>
                    <span style="font-family: 'Times New Roman', "><b> BANTAYAN ONLINE BUS RESERVATION SYSTEM</b></span>
                </a>

                <!-- Notification Bell Start -->
<li class="nav-item dropdown">
    <a class="nav-link text-white" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="icon">
            <img src="../assets/img/notif.png" alt="Notifications" style="width: 30px; height: 26px; top: 50px; left: -19px; position: absolute;">
        </span>
        <?php if ($pendingCount > 0): ?>
            <span class="badge badge-danger"><?php echo $pendingCount; ?></span>
        <?php endif; ?>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
        <?php if ($pendingCount > 0): ?>
            <?php foreach ($pendingBookings as $booking): ?>
                <a class="dropdown-item booking-item" href="index.php?page=booking&id=<?php echo $booking['id']; ?>">
                    Reference #: <?php echo $booking['book_reference']; ?> - <span class="text-warning">Pending</span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <a class="dropdown-item" href="#">No new bookings</a>
        <?php endif; ?>
    </div>
</li>
<!-- Notification Bell End -->

<audio id="notificationSound" src="../assets/sounds/notification-sound.mp3" preload="auto"></audio>
<script>
    // Function to play sound
    function playNotificationSound() {
        var sound = document.getElementById('notificationSound');
        sound.play().catch(function(error) {
            console.error("Error playing sound: ", error);
        });
    }

    // Function to check pending bookings
    function checkPendingBookings() {
        var pendingCount = <?php echo $pendingCount; ?>;
        if (pendingCount > 0) {
            playNotificationSound();
        }
    }

    // Check for pending bookings when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        checkPendingBookings();
    });

    // Play sound when booking item is clicked
    document.querySelectorAll('.booking-item').forEach(function(item) {
        item.addEventListener('click', function() {
            // Play sound when booking is clicked
            playNotificationSound();
        });
    });
</script>



                <div>
                    <a class="btn btn-link mr-auto" href="./backend/logout.php"><i class="fa fa-sign-out icon w3-large ">Logout</a></i>
                </div>
            </nav>

            <section class="container-fluid mt-3">
                <?php 
            if(isset($_GET['page']) && !empty($_GET['page'])){
                if (is_file('./pages/'.$_GET['page'].'.php')) {
                    include('./pages/'.$_GET['page'].'.php');
                }else{
                    include('./pages/notFound.php');
                }
            } else{
                include('./pages/dashboard.php');
            }
        ?>
            </section>
        </div>
    </div>

    <script>
    function toggleMenu() {
        let toggle = document.querySelector('.toggle')
        toggle.classList.toggle('active')

        let navigation = document.querySelector('.navigation')
        navigation.classList.toggle('active')

        let main = document.querySelector('.main')
        main.classList.toggle('active')
    }
    </script>

<?php include('includes/scripts.php')?>

</body>

</html>
