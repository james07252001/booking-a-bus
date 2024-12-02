<?php 
    include('includes/layout-header.php');
    include('controllers/db.php');
    include('controllers/location.php');

    $request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

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
    <p class="next-stop-text">What's Your Next Stop?</p>

    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/slideshow5.jpg" class="d-block m-auto carousel-image" alt="...">
            </div>
            <div class="carousel-item">
                <img src="assets/images/slideshow6.jpg" class="d-block m-auto carousel-image" alt="...">
            </div>
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

    <center class="section-title">
        <h2>Destinations</h2>
    </center>

    <div class="container">
        <div class="destinations">
            <div class="card">
                <img src="assets/img/d1.jpg" class="card-img-top" alt="Destination 1">
                <div class="card-body">
                    <h5 class="card-title">Madridejos</h5>
                    <p class="card-text">Madridejos is known for its laid-back atmosphere and beautiful coastal scenery.</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/d3.png" class="card-img-top" alt="Destination 2">
                <div class="card-body">
                    <h5 class="card-title">Bantayan</h5>
                    <p class="card-text">Bantayan Island is famous for its pristine beaches and clear waters.</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/d2.png" class="card-img-top" alt="Destination 3">
                <div class="card-body">
                    <h5 class="card-title">Santa Fe</h5>
                    <p class="card-text">Santa Fe is known for its charming vibe and natural scenery.</p>
                </div>
            </div>
        </div>
    </div>

    <center class="section-title">
        <h2>Bantayan Bus Terminal</h2>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d885.5465016292059!2d123.7259123695161!3d11.169638815014642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a888a10743cc17%3A0xed6e9bbdb8a9737c!2sRl%20Fitness%20%26%20Sports%20Hub!5e1!3m2!1sen!2sph!4v1722437136974!5m2!1sen!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <p class="map-address"><strong>Address:</strong> Rl Fitness & Sports Hub Bantayan, Cebu</p>
        </div>
    </center>

    <div class="about-section">
        <h2>About Us</h2>
        <p>An Overview of BantayanBusBooking.com</p>
        <p>At Bantayan Bus Booking, we believe every journey should be as remarkable as the destination. As Bantayan Island's dedicated travel partner, we’re here to simplify your travel experience with seamless online bus booking options, designed for both locals and tourists alike. Our commitment to reliability, comfort, and affordable fares ensures that your trip to and from Bantayan Island is smooth and memorable. Let us take care of the details, so you can focus on the adventure ahead—explore Bantayan Island with the confidence that your travel is in trusted hands.</p>
    </div>
</main>

<br>
<br>
<?php include('includes/layout-footer.php')?>

<style>
  .next-stop-text {
    margin: 0 auto;
    font-weight: 1000;
    text-align: center;
  }

  .carousel-image {
    width: 75%;
    height: auto;
    border-radius: 10px;
    margin: 0 auto;
  }

  .destinations {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
  }

  .card {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 30%;
    margin: 10px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 0 15px rgba(0, 0, 255, 0.6);
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

  .section-title {
    margin-top: 20px;
  }

  .map-container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .about-section {
    background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    max-width: 90%;
    margin: 0 auto;
  }

  /* Responsive design for mobile view */
  @media (max-width: 768px) {
    .destinations {
      flex-direction: column;
      align-items: center;
    }

    .card {
      width: 90%;
      margin: 10px 0;
    }

    .map-container iframe {
      width: 95%;
      height: 300px;
    }

    .next-stop-text {
      font-size: 1.2rem;
      margin: 20px 0;
    }

    .about-section {
      font-size: 0.9rem;
    }

    .carousel-image {
    width: 95%;
    height: auto;
    border-radius: 10px;
    margin: 0 auto;
    }

  }
</style>
