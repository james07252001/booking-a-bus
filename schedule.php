<?php
include('includes/layout-header.php');

$route_from = isset($_GET['route_from']) && !empty($_GET['route_from']) ? $_GET['route_from'] : "";
$route_to = isset($_GET['route_to']) && !empty($_GET['route_to']) ? $_GET['route_to'] : "";
$schedule_date = isset($_GET['schedule_date']) && !empty($_GET['schedule_date']) ? $_GET['schedule_date'] : "";

if (empty($route_from) || empty($route_to) || empty($schedule_date)) {
    header('Location: index.php');
} else {
    include('controllers/db.php');
    include('controllers/bus.php');
    include('controllers/schedule.php');
    include('controllers/route.php');
    include('controllers/location.php');
    include('controllers/driver.php');
    include('controllers/conductor.php');

    $database = new Database();
    $db = $database->getConnection();

    $new_location = new Location($db);
    $locations = $new_location->getAll();
    $location_from = $new_location->getByLocation($route_from);
    $location_to = $new_location->getByLocation($route_to);

    $new_route = new Route($db);
    $route = $new_route->getByFromTo($location_from["id"], $location_to["id"]);
}

// Redirect from .php URLs to remove the extension
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

?>

<main>
    <div style="background-image: url('assets/img/buscv1.jpg'); background-size: 100% 150%;" class="d-flex align-items-center justify-content-center p-4">
        <?php include("includes/forms/schedule-form.php") ?>
    </div>

    <div class="container mt-3">
        <?php
        if (empty($route["id"])) {
            echo '<div class="alert alert-danger" role="alert">
                    No schedule available. Please choose another date or try editing your search details.
                </div>';
        } else {
            $new_schedule = new Schedule($db);
            $schedules = $new_schedule->findSchedule($route["id"], $schedule_date);

            if (count($schedules) == 0) {
                echo '<div class="alert alert-danger" role="alert">
                        No schedule available. Please choose another date or try editing your search details.
                    </div>';
            } else {
                ?>
                <div class="mb-3">
                    <h4 class="mb-0">
                        <?php echo $location_from["location_name"] . ' &#x2192; ' . $location_to["location_name"] ?>
                    </h4>
                </div>

                <div class="row">
                    <?php
                    foreach ($schedules as &$row) {
                        $status = null;

                        if ($row["status"] === 'waiting') {
                            $status = '<span class="badge badge-primary">WAITING</span>';
                        } else if ($row["status"] === 'arrived') {
                            $status = '<span class="badge badge-success">ARRIVED</span>';
                        } else if ($row["status"] === 'cancelled') {
                            $status = '<span class="badge badge-danger">CANCELLED</span>';
                        } else if ($row["status"] === 'in-transit') {
                            $status = '<span class="badge badge-info">IN-TRANSIT</span>';
                        }

                        $new_bus = new Bus($db);
                        $bus = $new_bus->getById($row["bus_id"]);

                        $new_driver = new Driver($db);
                        $driver = $new_driver->getById($row["driver_id"]);

                        $new_conductor = new Conductor($db);
                        $conductor = $new_conductor->getById($row["conductor_id"]);
                    ?>
                        <div class="col-md-4 col-12 mb-3" id="<?php echo $row["id"]; ?>">
                            <div class="bg-white shadow-sm rounded">
                                <div class="p-3 bg-primary text-white text-center">
                                    <h5><?php echo $location_from["location_name"] . ' &#x2192; ' . $location_to["location_name"] ?></h5>
                                </div>
                                <div class="p-3">
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Date :</span>
                                        <strong><?php echo date_format(date_create($row['schedule_date']), 'F j, Y') ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Status:</span>
                                        <strong><?php echo $status ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Departure Time :</span>
                                        <strong><?php echo date_format(date_create($row['departure']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Arrival Time :</span>
                                        <strong><?php echo date_format(date_create($row['arrival']), 'g:i A') ?></strong>
                                    </p>
                                    <hr>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Bus Driver :</span>
                                        <strong><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Conductor :</span>
                                        <strong><?php echo $conductor['name'] ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Bus Name:</span>
                                        <strong><?php echo $bus['bus_num'] ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Bus Number:</span>
                                        <strong><?php echo $bus['bus_code'] ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Bus Type :</span>
                                        <strong><?php echo $bus['bus_type'] ?></strong>
                                    </p>
                                    <p class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Fare:</span>
                                        <strong><?php echo $row['fare'] ?></strong>
                                    </p>

                                    <a href="booked.php?schedule_id=<?php echo $row["id"]; ?>" class="btn btn-sm btn-outline-dark btn-block mt-3">
                                        Select
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
        <?php
            }
        }
        ?>
    </div>
</main>

<?php include('includes/scripts.php') ?>
<?php include('includes/layout-footer.php') ?>

<style>
    main {
        background-image: url('assets/img/buscv1.jpg');
        background-size: cover;
        background-position: center;
    }

    .bg-primary {
        background-color: #007bff !important;
    }

    .container {
        padding: 20px;
    }

    /* Responsive styles */
    @media (max-width: 576px) {
        h4, h5 {
            font-size: 1.2rem;
        }
        .btn {
            font-size: 0.8rem;
            padding: 0.6rem;
        }
        .bg-white {
            padding: 10px;
        }
    }
</style>
