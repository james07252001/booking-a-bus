<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/styles.css" />
    
    <title>Bantayan Online Bus Reservation</title>
    <style>
        @keyframes ledBorder {
            0% { border-color: #FCCF31; }
            50% { border-color: #F55555; }
            100% { border-color: #FCCF31; }
        }
        
        
        .nav-tabs {
            background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            padding: 10px;
            border-radius: 5px;
            border: 3px solid transparent;
            animation: ledBorder 1.5s infinite alternate;
        }

        .nav-tabs .nav-link {
            position: relative;
            padding: 10px 15px;
            margin: 0 -1px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background-color: #fff;
            color: #007bff;
            border-color: #007bff;
        }

        .nav-tabs .nav-link:hover {
            background-color: #e9ecef;
        }
    </style>
  </head>
<?php
    include('../controllers/db.php');
    $database = new Database();
    $db = $database->getConnection();

    include('../controllers/book.php');
    $new_book = new Book($db);
    $bookings = $new_book->getAll();

    include('../controllers/location.php');
    $new_location = new Location($db);

    include('../controllers/bus.php');
    $new_bus = new Bus($db);

    include('../controllers/driver.php');
    $new_driver = new Driver($db);

    include('../controllers/passenger.php');
    $new_passenger = new Passenger($db);

    include('../controllers/conductor.php');
    $new_conductor = new Conductor($db);

    // Redirect from .php URLs to remove the extension
    $request = $_SERVER['REQUEST_URI'];
    if (substr($request, -4) == '.php') {
        $new_url = substr($request, 0, -4);
        header("Location: $new_url", true, 301);
        exit();
    }
?>

<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/ceres/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>BOOKINGS</b></li>
        </ol>
    </nav>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                        $passenger = $new_passenger->getById($row["passenger_id"]);
                        $conductor = $new_conductor->getById($row["conductor_id"]);

                        // Calculate discount
                        $discount = 0;
                        if ($row['passenger_type'] == 'student' || $row['passenger_type'] == 'senior' || $row['passenger_type'] == 'pwd') {
                            $discount = 0.20; // 20% discount
                        }
                        $fare = $row['fare'];
                        $discount_amount = $fare * $discount;
                        $total = $fare - $discount_amount;

                        // Luggage calculation
                        $luggage_count = isset($row['luggage_count']) ? $row['luggage_count'] : 0;
                        $luggage_fee_per_piece = 30; // Set the luggage fee per piece
                        $luggage_fee = $luggage_count * $luggage_fee_per_piece;
                        $total_with_luggage = $total + $luggage_fee;
            ?>
            <div class="col-md-4 mb-3">
                <div class="border bg-light" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
                    <div id="<?php echo 'print_'.$row['book_id'] ?>">
                        <div class="bg-primary text-white p-3">
                            <h4 class="mb-0">
                                <?php echo $route_from["location_name"] . ' &#x2192; ' . $route_to["location_name"] ?>
                            </h4>
                        </div>
                        <div class="p-3">
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
                                <span class="text-muted">Passenger Type:</span>
                                <span class="font-weight-bold"><?php echo $row['passenger_type'] ?></span>
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
                                <strong class="text"><?php echo $driver['name'] ?></strong>
                            </p>
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Bus Conductor:</span>
                                <strong class="text"><?php echo $conductor['name'] ?></strong>
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
                                <span class="font-weight-bold text-uppercase badge badge-warning"><?php echo $row['payment_status'] ?></span>
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
                                <strong><?php echo number_format($total_with_luggage, 2); ?></strong>
                            </p>
                        </div>
                        <div class="p-3">
                            <button class="confirm-booking btn btn-sm btn-primary" onclick="confirmBook('<?php echo $row['book_id'] ?>', '<?php echo $passenger['email'] ?>')">Confirm</button>
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
</div>



<div class="tab-pane fade p-3" id="Confirmed" role="tabpanel" aria-labelledby="Confirmed-tab">
    <div class="row">
        <?php
            foreach ($bookings as &$row) {
                if ($row['payment_status'] == 'confirmed') {
                    $route_from = $new_location->getById($row['route_from']);
                    $route_to = $new_location->getById($row['route_to']);
                    $bus = $new_bus->getById($row["bus_id"]);
                    $driver = $new_driver->getById($row["driver_id"]);
                    $passenger = $new_passenger->getById($row["passenger_id"]);
                    $conductor = $new_conductor->getById($row["conductor_id"]);

                    // Calculate discount
                    $discount = 0;
                    if ($row['passenger_type'] == 'student' || $row['passenger_type'] == 'senior' || $row['passenger_type'] == 'pwd') {
                        $discount = 0.20; // 20% discount
                    }
                    $fare = $row['fare'];
                    $discount_amount = $fare * $discount;
                    $total = $fare - $discount_amount;

                    // Luggage calculation
                    $luggage_count = isset($row['luggage_count']) ? $row['luggage_count'] : 0;
                    $luggage_fee_per_piece = 30; // Set the luggage fee per piece
                    $luggage_fee = $luggage_count * $luggage_fee_per_piece;
                    $total_with_luggage = $total + $luggage_fee;
        ?>
        <div class="col-md-4 mb-3">
            <div class="border bg-light" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
                <div id="<?php echo 'print_'.$row['book_id'] ?>">
                    <div class="bg-primary text-white p-3">
                        <h4 class="mb-0">
                            <?php echo $route_from["location_name"] . ' &#x2192; ' . $route_to["location_name"] ?>
                        </h4>
                    </div>
                    <div class="p-3">
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
                            <span class="text-muted">Passenger Type:</span>
                            <span class="font-weight-bold"><?php echo $row['passenger_type'] ?></span>
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
                            <strong class="text"><?php echo $driver['name'] ?></strong>
                        </p>
                        <p class="d-flex align-items-center justify-content-between mb-0">
                            <span class="text-muted d-block">Bus Conductor:</span>
                            <strong class="text"><?php echo $conductor['name'] ?></strong>
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
                        <p class="d-flex align-items-center justify-content-between mb-0">
                            <span class="text-muted d-block">Discount Amount:</span>
                            <strong><?php echo number_format($discount_amount, 2) ?></strong>
                        </p>                        
                        <p class="d-flex align-items-center justify-content-between mb-0">
                            <span class="text-muted d-block">Luggage Fee:</span>
                            <strong><?php echo number_format($luggage_fee, 2); ?></strong>
                        </p>
                        <p class="d-flex align-items-center justify-content-between mb-0">
                            <span class="text-muted d-block">Total:</span>
                            <strong><?php echo number_format($total_with_luggage, 2); ?></strong>
                        </p>
                    </div>
                    <div class="p-3">
                        <button class="confirm-booking btn btn-sm btn-danger" onclick="cancelBook('<?php echo $row['book_id'] ?>', '<?php echo $passenger['email'] ?>')">Cancel</button>
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


<div class="tab-pane fade p-3" id="Cancelled" role="tabpanel" aria-labelledby="Cancelled-tab">
    <div class="row">
        <?php
            foreach ($bookings as &$row) {
                if ($row['payment_status'] == 'cancelled') {
                    $route_from = $new_location->getById($row['route_from']);
                    $route_to = $new_location->getById($row['route_to']);
                    $bus = $new_bus->getById($row["bus_id"]);
                    $driver = $new_driver->getById($row["driver_id"]);
                    $passenger = $new_passenger->getById($row["passenger_id"]);
                    $conductor = $new_conductor->getById($row["conductor_id"]);

                    // Calculate discount
                    $discount = 0;
                    if ($row['passenger_type'] == 'student' || $row['passenger_type'] == 'senior' || $row['passenger_type'] == 'pwd') {
                        $discount = 0.20; // 20% discount
                    }
                    $fare = $row['fare'];
                    $discount_amount = $fare * $discount;
                    $total = $fare - $discount_amount;

                    // Luggage calculation
                    $luggage_count = isset($row['luggage_count']) ? $row['luggage_count'] : 0;
                    $luggage_fee_per_piece = 30; // Set the luggage fee per piece
                    $luggage_fee = $luggage_count * $luggage_fee_per_piece;
                    $total_with_luggage = $total + $luggage_fee;
        ?>
        <div class="col-md-4 mb-3">
            <div class="border bg-light" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
                <div id="<?php echo 'print_'.$row['book_id'] ?>">
                    <div class="bg-primary text-white p-3">
                        <h4 class="mb-0">
                            <?php echo $route_from["location_name"] . ' &#x2192; ' . $route_to["location_name"] ?>
                        </h4>
                    </div>
                    <div class="p-3">
                        <div class="p-3">
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
                                <span class="text-muted">Passenger Type:</span>
                                <span class="font-weight-bold"><?php echo $row['passenger_type'] ?></span>
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
                                <strong class="text"><?php echo $driver['name'] ?></strong>
                            </p>
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Bus Conductor:</span>
                                <strong class="text"><?php echo $conductor['name'] ?></strong>
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
                                <span class="font-weight-bold text-uppercase badge badge-danger"><?php echo $row['payment_status'] ?></span>
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
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Discount Amount:</span>
                                <strong><?php echo number_format($discount_amount, 2) ?></strong>
                            </p>                            
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Luggage Fee:</span>
                                <strong><?php echo number_format($luggage_fee, 2); ?></strong>
                            </p>
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Total:</span>
                                <strong><?php echo number_format($total_with_luggage, 2); ?></strong>
                            </p>
                        </div>
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


<div class="tab-pane fade p-3" id="Pending" role="tabpanel" aria-labelledby="Pending-tab">
    <div class="row">
        <?php
            foreach ($bookings as &$row) {
                if ($row['payment_status'] == 'pending') {
                    $route_from = $new_location->getById($row['route_from']);
                    $route_to = $new_location->getById($row['route_to']);
                    $bus = $new_bus->getById($row["bus_id"]);
                    $driver = $new_driver->getById($row["driver_id"]);
                    $passenger = $new_passenger->getById($row["passenger_id"]);
                    $conductor = $new_conductor->getById($row["conductor_id"]);

                    // Luggage calculation
                    $luggage_count = isset($row['luggage_count']) ? $row['luggage_count'] : 0;
                    $luggage_fee_per_piece = 5; // Set the luggage fee per piece
                    $luggage_fee = $luggage_count * $luggage_fee_per_piece;
        ?>
        <div class="col-md-4 mb-3">
            <div class="border bg-light" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
                <div id="<?php echo 'print_'.$row['book_id'] ?>">
                    <div class="bg-primary text-white p-3">
                        <h4 class="mb-0">
                            <?php echo $route_from["location_name"] . ' &#x2192; ' . $route_to["location_name"] ?>
                        </h4>
                    </div>
                    <div class="p-3">
                        <div class="p-3">
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
                                <span class="text-muted">Bus Name:</span>
                                <span class="font-weight-bold"><?php echo $bus['bus_num'] ?></span>
                            </p>
                            <p class="mb-0 d-flex align-items-center justify-content-between">
                                <span class="text-muted">Bus Number:</span>
                                <span class="font-weight-bold"><?php echo $bus['bus_code'] ?></span>
                            </p>
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Bus Driver:</span>
                                <strong class="text"><?php echo $driver['name'] ?></strong>
                            </p>
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Bus Conductor:</span>
                                <strong class="text"><?php echo $conductor['name'] ?></strong>
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
                                <strong><?php echo number_format($row['fare'], 2) ?></strong>
                            </p>
                            <!-- Luggage Count and Fee -->
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Luggage Count:</span>
                                <strong><?php echo $luggage_count; ?></strong>
                            </p>
                            <p class="d-flex align-items-center justify-content-between mb-0">
                                <span class="text-muted d-block">Luggage Fee:</span>
                                <strong><?php echo number_format($luggage_fee, 2); ?></strong>
                            </p>
                        </div>
                    </div>
                    <div class="p-3">
                        <button class="confirm-booking btn btn-sm btn-primary" onclick="cancelBook('<?php echo $row['book_id'] ?>', '<?php echo $passenger['email'] ?>')">Cancel</button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function confirmBook(id, email) {
    // SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to confirm this booking?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const confirmBooking = document.querySelector('.confirm-booking');
            confirmBooking.disabled = true;
            confirmBooking.textContent = 'Loading...';

            $.ajax({
                cache: false,
                data: {
                    type: 2,
                    id,
                    payment_status: 'confirmed',
                    email
                },
                type: "post",
                url: "../controllers/update-booking.php",
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmed!',
                            text: 'Booking confirmed successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload after confirmation
                        });
                    } else {
                        // Error handling with SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: dataResult.title || 'An error occurred while confirming the booking.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong with the server request.',
                    });
                }
            });
        }
    });
}

</script>
<?php include('includes/scripts.php')?>
