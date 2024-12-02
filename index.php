<?php 
    include('includes/layout-header.php');
    include('controllers/db.php');
    include('controllers/location.php');
?>

<!-- Add the icon link here -->
<link rel="icon" href="../assets/img/bus.ico" type="image/ico">

<?php 
    // Sanitize input function
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Validate and sanitize route parameters
    $route_from = isset($_GET['route_from']) ? sanitizeInput($_GET['route_from']) : "";
    $route_to = isset($_GET['route_to']) ? sanitizeInput($_GET['route_to']) : "";
    $schedule_date = isset($_GET['schedule_date']) ? sanitizeInput($_GET['schedule_date']) : "";

    $database = new Database();
    $db = $database->getConnection();

    $new_location = new Location($db);

    // Use prepared statements for fetching location data
    $locations = $new_location->getAll();
    $location_from = $new_location->getByLocation($route_from);
    $location_to = $new_location->getByLocation($route_to);
?>

<?php include('includes/scripts.php') ?>
<main style="background-color: #FFDEE9; background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);">
   
    <div class="d-flex align-items-center justify-content-center p-4">
        <?php include("includes/forms/schedule-form.php") ?>
    </div>
    <p style="margin-left: 420px; margin-top: -30px; font-weight: 1000;">What's Your Next Stop?</p>

    <br>
    <br>
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel" style="color: black;">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" style="color: black" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="container1">
            <center>
                <img src="assets/images/bg1.png" style='width:1100px; height: 500px;'> 
                <div class="carousel-item active">
                    <img src="assets/images/slideshow5.jpg" class="d-block m-auto" style='width:90%; height: 500px; border: 2px solid black; border-radius: 40px;' alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/slideshow6.jpg" class="d-block m-auto" style='width:90%; height: 500px; border: 2px solid black; border-radius: 40px;' alt="...">
                </div>
            </center>
        </div>
        <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </button>
    </div>

    <center style="margin-top: 50px;">
        <h2>Destinations</h2>
    </center>

    <div class="container">
        <div class="destinations">
            <div class="card">
                <img src="assets/img/d1.jpg" class="card-img-top" alt="Destination 1" style="height: 200px;">
                <div class="card-body">
                    <h5 class="card-title">Madridejos</h5>
                    <p class="card-text" style="max-width: 300px;">Madridejos is a municipality on Bantayan Island, located in the northern part of Cebu Province in the Philippines. It's known for its laid-back atmosphere and beautiful coastal scenery.</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/d3.png" class="card-img-top" alt="Destination 2">
                <div class="card-body">
                    <h5 class="card-title">Bantayan</h5>
                    <p class="card-text" style="max-width: 300px;">Bantayan Island is a beautiful destination in the Philippines, known for its pristine beaches and crystal-clear waters.</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/d2.png" class="card-img-top" alt="Destination 3">
                <div class="card-body">
                    <h5 class="card-title">Santa Fe</h5>
                    <p class="card-text" style="max-width: 300px;">Santa Fe is a charming town located on Bantayan Island, known for its laid-back vibe and beautiful natural scenery.</p>
                </div>
            </div>
        </div>
    </div>

    <center style="margin-top: 60px;">
        <h2>Bantayan Bus Terminal</h2>
        <div style="display: flex; align-items: center; justify-content: center;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d885.5465016292059!2d123.7259123695161!3d11.169638815014642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a888a10743cc17%3A0xed6e9bbdb8a9737c!2sRl%20Fitness%20%26%20Sports%20Hub!5e1!3m2!1sen!2sph!4v1722437136974!5m2!1sen!2sph" width="600" height="450" style="border:5; margin-left: -250px; margin-top: 50px" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <p style="margin-left: 50px; font-size: 150%">
                <strong>Address:</strong> Rl Fitness & Sports Hub Bantayan, Cebu
            </p>
        </div>
    </center>

    <div style="margin-top: 50px; text-align: center;">
    <div style="background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%); padding: 20px; border-radius: 10px; color: black; width: 1500px; font-family: Arial, Helvetica, sans-serif; display: inline-block;">
        <h2 style="font-weight: bold;">About Us</h2><br><br>
        <p style="font-size: 100%">An Overview of BantayanBusBooking.com</p><br>
        <p style="text-align: center;">Buses are a practical and affordable mode of transportation for various purposes. BantayanBusBooking.com is a user-friendly website that offers convenient access to bus schedules specifically for Bantayan Island.</p>
        <p style="text-align: center;">BantayanBusBooking.com was developed by Dianna Lyn Cena, Maria Cristine Batiancila, Jemaica Corridor, and James Vincent Pastorillo.</p>
    </div>
</div>

</main>

<br>
<br>
<?php include('includes/layout-footer.php')?>

<style>
  .destinations {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
  }

  .card {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 30%;
    margin: 10px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add transition for smooth animation */
  }

  .card:hover {
    transform: translateY(-20px); /* Raise the card by 20px */
    box-shadow: 0 0 15px rgba(0, 0, 255, 0.6); /* Add a glowing effect */
  }

  .card-img-top {
    width: 100%;
    height: auto;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  .card-body {
    padding: 15px;
  }

  .card-title {
    margin-bottom: 10px;
    font-size: 1.25rem;
  }

  .card-text {
    font-size: 1rem;
  }
</style>
