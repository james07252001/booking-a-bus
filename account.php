<?php 
    include('includes/layout-header.php');
    include('controllers/check-auth.php');

    include('controllers/db.php');
    $database = new Database();
    $db = $database->getConnection();

    include('controllers/book.php');
    $new_book = new Book($db);
    $bookings = $new_book->getPassengersBooking($_SESSION["userId"]);

    include('controllers/location.php');
    $new_location = new Location($db);

    include('controllers/bus.php');
    $new_bus = new Bus($db);

    include('controllers/driver.php');
    $new_driver= new Driver($db);


    include('controllers/vessel.php');
    $new_vessel = new Vessel($db);


    include('controllers/passenger.php');
    $new_passenger = new Passenger($db);
    $passenger = $new_passenger->getById($_SESSION["userId"]);

    if(isset($_POST["update-passenger-submit"])){
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $address = $_POST["address"];
        $password = $_POST["password"];

        $new_passenger->update($first_name, $last_name, $email, $address, $password, $_SESSION["userId"]);
    }

    $request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

?>

<main >
<div class="container mt-5">
        <?php
            // Error and success messages
            if(isset($_GET['error']) && !empty($_GET['error'])){
                if($_GET['error'] == 'stmtfailed'){
                    echo '<div class="alert alert-danger" role="alert">
                Oops something went wrong.
                </div>';
                }
            }

            if(isset($_GET['success']) && !empty($_GET['success'])){
                if($_GET['success'] == 'updatedPassenger'){
                    echo '<div class="alert alert-success" role="alert">
                Account updated successfully.
                </div>';
                }
            }
        ?>

<ul class="nav nav-tabs bg-white sm" id="myTab" role="tablist">
    <li class="nav-item" style="background-image: linear-gradient(109.6deg, rgba(254,253,205,1) 11.2%, rgba(163,230,255,1) 91.1%);">
        <a class="nav-link active" id="booking-tab" data-toggle="tab" href="#booking" role="tab" aria-controls="booking" aria-selected="true"><b>My Booking</b></a>
    </li>
    <li class="nav-item" style="background-image: linear-gradient(109.6deg, rgba(254,253,205,1) 11.2%, rgba(163,230,255,1) 91.1%);">
        <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false"><b>Account Settings</b></a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade bg-white p-3 border-right border-left border-bottom show active" id="booking" role="tabpanel" aria-labelledby="booking-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-image: linear-gradient(109.6deg, rgba(254,253,205,1) 11.2%, rgba(163,230,255,1) 91.1%);">
            <li class="nav-item">
                <a class="nav-link active" id="Pending-tab" data-toggle="tab" href="#Pending" role="tab" aria-controls="Pending" aria-selected="true">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="Confirmed-tab" data-toggle="tab" href="#Confirmed" role="tab" aria-controls="Confirmed" aria-selected="false">Confirmed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="Cancelled-tab" data-toggle="tab" href="#Cancelled" role="tab" aria-controls="Cancelled" aria-selected="false">Cancelled</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active p-3" id="Pending" role="tabpanel" aria-labelledby="Pending-tab">
                <div class="row">
                    <?php
                    foreach ($bookings as &$row) {
                        if ($row['payment_status'] == 'pending') {
                            $route_from = $new_location->getById($row['route_from']);
                            $route_to = $new_location->getById($row['route_to']);
                            $bus = $new_bus->getById($row["bus_id"]);
                            $driver = $new_driver->getById($row["driver_id"]);
                            $vessel = $new_vessel->getById($row["vessel_id"]);
                            
                            // Calculate discount
                            $discount = 0;
                            if ($row['passenger_type'] == 'student' || $row['passenger_type'] == 'senior' || $row['passenger_type'] == 'pwd') {
                                $discount = 0.20; // 20% discount
                            }
                            $fare = $row['fare'];
                            $discount_amount = $fare * $discount;
                            $total = $fare - $discount_amount;

                            // Luggage fee calculation
                            $luggage_count = $row['luggage_count'];
                            $luggage_fee = $luggage_count * 30; // 30 per luggage
                            $total_with_luggage = $total + $luggage_fee; // Add luggage fee to total

                            // Determine the color and capitalize passenger type
                            $passengerType = ucfirst($row['passenger_type']); // Capitalize first letter
                            $color = '';
                            switch ($row['passenger_type']) {
                                case 'regular':
                                    $color = 'chocolate';
                                    break;
                                case 'student':
                                    $color = 'deeppink';
                                    break;
                                case 'senior':
                                    $color = 'orangered';
                                    break;
                                case 'pwd':
                                    $color = 'red';
                                    break;
                                default:
                                    $color = 'black'; // Fallback color
                            }
                    ?>
                        <div class="col-12 col-md-4 mb-3"> <!-- Adjusted to col-12 for mobile -->
                            <div class="border bg-light">
                                <div id="<?php echo 'print_'.$row['book_id'] ?>">
                                    <div class="bg-primary p-3">
                                        <h4 class="mb-0">
                                            <?php echo $route_from["location_name"].' &#x2192; '.$route_to["location_name"] ?>
                                        </h4>
                                    </div>
                                    <div class="p-3" style="background-image: linear-gradient( 109.6deg,  rgba(254,253,205,1) 11.2%, rgba(163,230,255,1) 91.1% );">
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Booked Date:</span>
                                            <span class="font-weight-bold"><?php echo date_format(date_create($row['book_date']), 'F j, Y') ?></span>
                                        </p>
                                        <hr>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Reference:</span>
                                            <span class="font-weight-bold"><?php echo $row['book_reference'] ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Passenger:</span>
                                            <span class="font-weight-bold"><?php echo $passenger['first_name'].' '. $passenger['last_name'] ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Passenger Type:</span>
                                            <span class="font-weight-bold" style="color: <?php echo $color; ?>;"><?php echo $passengerType; ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Bus Name:</span>
                                            <span class="font-weight-bold"><?php echo $bus['bus_num'] ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Bus Number:</span>
                                            <span class="font-weight-bold"><?php echo $bus['bus_code'] ?></span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between mb-0">
                                            <span class="text-muted d-block">Bus Driver:</span>
                                            <strong class="text-uppercase"><?php echo $driver['name'] ?></strong>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Bus Type:</span>
                                            <span class="font-weight-bold"><?php echo $bus['bus_type'] ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Seat Number:</span>
                                            <span class="font-weight-bold"><?php echo $row['seat_num'] ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Luggage Count:</span>
                                            <span class="font-weight-bold"><?php echo $luggage_count ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Status:</span>
                                            <span class="font-weight-bold text-uppercase badge badge-success"><?php echo $row['payment_status'] ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Schedule Date:</span>
                                            <span class="font-weight-bold"><?php echo date_format(date_create($row['schedule_date']), 'F j, Y') ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Departure Time:</span>
                                            <span class="font-weight-bold"><?php echo date_format(date_create($row["departure"]), 'g:i A') ?></span>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Arrival Time:</span>
                                            <span class="font-weight-bold"><?php echo date_format(date_create($row["arrival"]), 'g:i A') ?></span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between mb-0">
                                            <span class="text-muted d-block">Fare:</span>
                                            <strong><?php echo number_format($fare, 2) ?></strong>
                                        </p>
                                        <p class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Luggage Fee:</span>
                                            <span class="font-weight-bold"><?php echo number_format($luggage_fee, 2) ?></span>
                                        </p>

                                        <p class="d-flex align-items-center justify-content-between mb-0">
                                            <span class="text-muted d-block">Discount Amount:</span>
                                            <strong><?php echo number_format($discount_amount, 2) ?></strong>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between mb-0">
                                            <span class="text-muted d-block">Total:</span>
                                            <strong><?php echo number_format($total_with_luggage, 2) ?></strong> <!-- Total with luggage fee -->
                                        </p>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <button class="btn btn-sm btn-danger w-100" onclick="cancelBook('<?php echo $row['book_id'] ?>')">Cancel</button>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom mobile styles */
    @media (max-width: 576px) {
        .nav-tabs {
            flex-direction: column;
        }
        
        .nav-item {
            width: 100%; /* Make each tab take the full width */
        }
        
        .nav-link {
            text-align: center; /* Center the text in tabs */
        }
        
        .col-md-4 {
            margin-bottom: 15px; /* Add margin for spacing between columns on mobile */
        }
    }
</style>



<div class="tab-pane fade p-3" id="Confirmed" role="tabpanel" aria-labelledby="Confirmed-tab">
    <div class="row">
        <?php
            foreach ($bookings as &$row) {
                if ($row['payment_status'] == 'confirmed') {
                    $route_from = $new_location->getById($row['route_from']);
                    $route_to = $new_location->getById($row['route_to']);
                    $bus = $new_bus->getById($row["bus_id"]);
                    $driver = $new_driver->getById($row["driver_id"]);
                    $vessel = $new_vessel->getById($row["vessel_id"]);
                    
                    // Calculate discount
                    $discount = 0;
                    if ($row['passenger_type'] == 'student' || $row['passenger_type'] == 'senior' || $row['passenger_type'] == 'pwd') {
                        $discount = 0.20; // 20% discount
                    }
                    $fare = $row['fare'];
                    $discount_amount = $fare * $discount;
                    $total = $fare - $discount_amount;

                    // Determine the color and capitalize passenger type
                    $passengerType = ucfirst($row['passenger_type']); // Capitalize first letter
                    $color = '';
                    switch ($row['passenger_type']) {
                        case 'regular':
                            $color = 'chocolate';
                            break;
                        case 'student':
                            $color = 'deeppink';
                            break;
                        case 'senior':
                            $color = 'orangered';
                            break;
                        case 'pwd':
                            $color = 'red';
                            break;
                        default:
                            $color = 'black'; // Fallback color
                    }

                    // Luggage calculation
                    $luggage_count = isset($row['luggage_count']) ? $row['luggage_count'] : 0;
                    $luggage_fee_per_piece = 30; // Set the luggage fee per piece
                    $luggage_fee = $luggage_count * $luggage_fee_per_piece;
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="border bg-light">
                            <div id="<?php echo 'print_'.$row['book_id'] ?>">
                                <div class="bg-primary p-3">
                                    <h4 class="mb-0">
                                        <?php echo $route_from["location_name"].' &#x2192; '.$route_to["location_name"] ?>
                                    </h4>
                                </div>
                                <div class="p-3">
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Booked Date:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row['book_date']),'F j, Y') ?></span>
                                    </p>
                                    <hr>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Reference:</span>
                                        <span class="font-weight-bold"><?php echo $row['book_reference'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Passenger:</span>
                                        <span class="font-weight-bold"><?php echo $passenger['first_name'].' '. $passenger['last_name'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Passenger Type:</span>
                                        <span class="font-weight-bold" style="color: <?php echo $color; ?>;"><?php echo $passengerType; ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Bus Name:</span>
                                        <span class="font-weight-bold"><?php echo $bus['bus_num'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Bus Number:</span>
                                        <span class="font-weight-bold"><?php echo $bus['bus_code'] ?></span>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Driver:</span>
                                        <strong class="text-uppercase"><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Bus Type:</span>
                                        <span class="font-weight-bold"><?php echo $bus['bus_type'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Seat Number:</span>
                                        <span class="font-weight-bold"><?php echo $row['seat_num'] ?></span>
                                    </p>
                                    <!-- Luggage Count and Fee -->
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Luggage Count:</span>
                                        <strong><?php echo $luggage_count; ?></strong>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Status:</span>
                                        <span class="font-weight-bold text-uppercase badge badge-success"><?php echo $row['payment_status'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Schedule Date:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row['schedule_date']),'F j, Y') ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Departure Time:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row["departure"]), 'g:i A') ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Arrival Time:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row["arrival"]), 'g:i A') ?></span>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Fare:</span>
                                        <strong><?php echo number_format($fare, 2) ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Luggage Fee:</span>
                                        <strong><?php echo number_format($luggage_fee, 2); ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Discount Amount:</span>
                                        <strong><?php echo number_format($discount_amount, 2) ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Total:</span>
                                        <strong><?php echo number_format($total, 2) ?></strong>
                                    </p>                                    
                                    
                                </div>
                            </div>
                            <div class="p-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="PrintElem('<?php echo 'print_'.$row['book_id'] ?>')">Print</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        ?>
    </div>
</div>



<div class="tab-pane fade p-3" id="Cancelled" role="tabpanel" aria-labelledby="Cancelled-tab">
    <div class="row">
        <?php
            foreach ($bookings as &$row) {
                if ($row['payment_status'] == 'cancel') {
                    $route_from = $new_location->getById($row['route_from']);
                    $route_to = $new_location->getById($row['route_to']);
                    
                    // Calculate discount
                    $discount = 0;
                    if ($row['passenger_type'] == 'student' || $row['passenger_type'] == 'senior' || $row['passenger_type'] == 'pwd') {
                        $discount = 0.20; // 20% discount
                    }
                    $fare = $row['fare'];
                    $discount_amount = $fare * $discount;
                    $total = $fare - $discount_amount;

                    // Determine the color and capitalize passenger type
                    $passengerType = ucfirst($row['passenger_type']); // Capitalize first letter
                    $color = '';
                    switch ($row['passenger_type']) {
                        case 'regular':
                            $color = 'chocolate';
                            break;
                        case 'student':
                            $color = 'deeppink';
                            break;
                        case 'senior':
                            $color = 'orangered';
                            break;
                        case 'pwd':
                            $color = 'red';
                            break;
                        default:
                            $color = 'black'; // Fallback color
                    }

                    // Luggage calculation
                    $luggage_count = isset($row['luggage_count']) ? $row['luggage_count'] : 0;
                    $luggage_fee_per_piece = 30; // Set the luggage fee per piece
                    $luggage_fee = $luggage_count * $luggage_fee_per_piece;
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="border bg-light">
                            <div id="<?php echo 'print_'.$row['book_id'] ?>">
                                <div class="bg-primary p-3">
                                    <h4 class="mb-0">
                                        <?php echo $route_from["location_name"].' &#x2192; '.$route_to["location_name"] ?>
                                    </h4>
                                </div>
                                <div class="p-3">
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Booked Date:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row['book_date']),'F j, Y') ?></span>
                                    </p>
                                    <hr>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Reference:</span>
                                        <span class="font-weight-bold"><?php echo $row['book_reference'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Passenger:</span>
                                        <span class="font-weight-bold"><?php echo $passenger['first_name'].' '. $passenger['last_name'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Passenger Type:</span>
                                        <span class="font-weight-bold" style="color: <?php echo $color; ?>;"><?php echo $passengerType; ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Bus Name:</span>
                                        <span class="font-weight-bold"><?php echo $bus['bus_num'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Bus Number:</span>
                                        <span class="font-weight-bold"><?php echo $bus['bus_code'] ?></span>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Driver:</span>
                                        <strong class="text-uppercase"><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Bus Type:</span>
                                        <span class="font-weight-bold"><?php echo $bus['bus_type'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Seat Number:</span>
                                        <span class="font-weight-bold"><?php echo $row['seat_num'] ?></span>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Luggage Count:</span>
                                        <strong><?php echo $luggage_count; ?></strong>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Status:</span>
                                        <span class="font-weight-bold text-uppercase badge badge-danger"><?php echo $row['payment_status'] ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Schedule Date:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row['schedule_date']),'F j, Y') ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Departure Time:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row["departure"]), 'g:i A') ?></span>
                                    </p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-muted">Arrival Time:</span>
                                        <span class="font-weight-bold"><?php echo date_format(date_create($row["arrival"]), 'g:i A') ?></span>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Fare:</span>
                                        <strong><?php echo number_format($fare, 2) ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Luggage Fee:</span>
                                        <strong><?php echo number_format($luggage_fee, 2); ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Discount Amount:</span>
                                        <strong><?php echo number_format($discount_amount, 2) ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Total:</span>
                                        <strong><?php echo number_format($total, 2) ?></strong>
                                    </p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        ?>
    </div>
</div>




<div class="tab-pane fade bg-white p-3 border-right border-left border-bottom" id="settings" role="tabpanel" aria-labelledby="settings-tab">
    <form method="POST" action="" onsubmit="return validateForm()">
        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="first_name"><b>First Name</b></label>
                <input type="text" class="form-control" id="first_name" name="first_name" 
                       value="<?php echo htmlspecialchars($passenger['first_name']) ?>" 
                       required pattern="[A-Za-z]+" title="Only letters are allowed." />
            </div>
            <div class="col-md-6">
                <label for="last_name"><b>Last Name</b></label>
                <input type="text" class="form-control" id="last_name" name="last_name" 
                       value="<?php echo htmlspecialchars($passenger['last_name']) ?>" 
                       required pattern="[A-Za-z]+" title="Only letters are allowed." />
            </div>
        </div>
        <div class="form-group">
            <label for="address"><b>Address</b></label>
            <input type="text" class="form-control" id="address" name="address" 
                   value="<?php echo htmlspecialchars($passenger['address']) ?>" required />
        </div>
        <div class="form-group">
            <label for="email"><b>Email address</b></label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($passenger['email']) ?>" required />
        </div>
        <div class="form-group">
            <label for="password"><b>Password</b></label>
            <input type="password" class="form-control" id="password" name="password" />
        </div>

        <button type="submit" class="btn btn-primary" name="update-passenger-submit"><b>Update</b></button>
    </form>
</div>
        </div>
    </div>
</main>

<script>
    function PrintElem(divId)
    {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body><div style='margin: auto; max-width: 500px'>" + printContents + "</div></body>";
        window.print();
        document.body.innerHTML = originalContents;
    }

    function cancelBook(id)
    {
        if(confirm("Are you sure to cancel this booking?")){
            console.log('cancelBook', id)
            $.ajax({
                cache: false,
                data: {
                    type: 2,
                    id,
                    payment_status: 'cancel'
                },
                type: "post",
                url: "controllers/update-booking.php",
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        alert("Booking cancelled successfully.");
                        location.reload();
                    } else {
                        alert(dataResult.title);
                    }
                },
            });
        }
    }

</script>

<script>
function validateForm() {
    var firstName = document.getElementById('first_name').value;
    var lastName = document.getElementById('last_name').value;

    // Regex to match only letters
    var regex = /^[A-Za-z]+$/;

    if (!regex.test(firstName)) {
        alert("First name can only contain letters.");
        return false;
    }

    if (!regex.test(lastName)) {
        alert("Last name can only contain letters.");
        return false;
    }

    return true;
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


<?php include('includes/scripts.php')?>
<?php include('includes/layout-footer.php')?>