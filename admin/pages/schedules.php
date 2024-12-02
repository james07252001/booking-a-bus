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
    include('../controllers/schedule.php');
    include('../controllers/route.php');
    include('../controllers/location.php');
    include('../controllers/bus.php');
    include('../controllers/driver.php');
    include('../controllers/conductor.php');
    include('../controllers/vessel.php');

    $database = new Database();
    $db = $database->getConnection();
    
    $new_schedule = new Schedule($db);
    $schedules = $new_schedule->getAll();

    $new_location = new Location($db);
?>

<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>SCHEDULES</b></li>
      </ol>
    </nav>

    <div class="text-left mb-3">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newScheduleModal">
        <i class="fa fa-plus" >  New Schedule</i>
        </button>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist"  style="background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab" aria-controls="waiting" aria-selected="true" style="color: black;">Waiting</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false" style="color: black;">Cancelled</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="in-transit-tab" data-toggle="tab" href="#in-transit" role="tab" aria-controls="in-transit" aria-selected="false" style="color: black;">In-transit</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="arrived-tab" data-toggle="tab" href="#arrived" role="tab" aria-controls="arrived" aria-selected="false" style="color: black;">Arrived</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="waiting" role="tabpanel" aria-labelledby="waiting-tab">
            <div class="row mt-3" >
                <?php
                    foreach ($schedules as &$row) {
                        if($row['status'] === 'waiting'){
                            $new_route = new Route($db);
                        $route = $new_route->getById($row["route_id"]);
                        
                        
                        $location_from = $new_location->getById($route["route_from"]);
                        $location_to = $new_location->getById($route["route_to"]);


                        
                        $new_bus = new Bus($db);
                        $bus = $new_bus->getById($row["bus_id"]);

                        $new_bus_code = new Bus($db);
                        $bus_code = $new_bus_code->getById($row["bus_code_id"]);

                        $new_driver = new Driver($db);
                        $driver = $new_driver->getById($row["driver_id"]);
                        $status = null;

                        $new_conductor = new Conductor($db);
                        $conductor = $new_conductor->getById($row["conductor_id"]);
                        $status = null;

                        

                        if($row["status"] === 'waiting'){
                            $status = '<span class="badge badge-primary">WAITING</span>';
                        }else if($row["status"] === 'arrived'){
                            $status = '<span class="badge badge-success">ARRIVED</span>';
                        }else if($row["status"] === 'cancelled'){
                            $status = '<span class="badge badge-danger">CANCELLED</span>';
                        }else if($row["status"] === 'in-transit'){
                            $status = '<span class="badge badge-info">IN-TRANSIT</span>';
                        }

                        ?>
                        <div class="col-md-4" id="<?php echo $row["id"]; ?>">
                            <div class="bg-white shadow-sm">
                                <div class="p-3 bg-primary text-white"> 
                                    <h4 class="mb-0 text-center">
                                        <?php echo $location_from["location_name"].' &#x2192; '.$location_to["location_name"] ?>
                                    </h4>
                                </div>
                                <div class="p-3"  style="background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);">
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Date:</span>
                                        <strong><?php echo date_format(date_create($row['schedule_date']),'F j, Y') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block" style="color: gray">Bus Name :</span>
                                        <strong class="text"><?php echo $bus['bus_num'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Code :</span>
                                        <strong class="text"><?php echo $bus['bus_code'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Status:</span>
                                        <strong class="text"><?php echo $status ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Departure Time:</span>
                                        <strong><?php echo date_format(date_create($row['departure']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Estimated Arrival Time:</span>
                                        <strong><?php echo date_format(date_create($row['arrival']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Driver Name:</span>
                                        <strong class="text"><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Type:</span>
                                        <strong class="text"><?php echo $bus['bus_type'] ?></strong>
                                    </p>
                                    
                                    
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Fare:</span>
                                        <strong><?php echo $row['fare'] ?></strong>
                                    </p>
                                    
                                     <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Conductor Name:</span>
                                        <strong class="text"><?php echo $conductor['name'] ?></strong>
                                    </p> 
                                    

                                    <div class="mt-3">
    <a href="#scheduleEditModal" class="btn btn-sm btn-warning scheduleUpdate"
    data-id="<?php echo $row["id"]; ?>" 
    data-route_id="<?php echo $row["route_id"]; ?>"
    data-schedule_date="<?php echo $row["schedule_date"]; ?>" 
    data-departure="<?php echo $row["departure"]; ?>"
    data-arrival="<?php echo $row["arrival"]; ?>" 
    data-bus_id="<?php echo $row["bus_id"]; ?>" 
    data-bus_code_id="<?php echo $row["bus_code_id"]; ?>"
    data-driver_id="<?php echo $row["driver_id"]; ?>" 
    data-fare="<?php echo $row["fare"]; ?>" 
    data-conductor_id="<?php echo $row["conductor_id"]; ?>"
    data-toggle="modal">Edit</a>

                                        <a href="#scheduleDeleteModal" class="btn btn-sm btn-danger scheduleDelete"
                                        data-id="<?php echo $row["id"]; ?>" data-toggle="modal">Delete</a>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'waiting')">Waiting</button>
                                            <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'cancelled')">Cancel</button>
                                            <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'in-transit')">In-transit</button>
                                            <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'arrived')">Arrived</button>
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
        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
            <div class="row mt-3">
                    <?php
                        foreach ($schedules as &$row) {
                            if($row['status'] === 'cancelled'){
                                $new_route = new Route($db);
                            $route = $new_route->getById($row["route_id"]);
                            
                            $location_from = $new_location->getById($route["route_from"]);
                            $location_to = $new_location->getById($route["route_to"]);

                            $new_bus = new Bus($db);
                            $bus = $new_bus->getById($row["bus_id"]);

                            $new_bus_code = new Bus($db);
                            $bus_code = $new_bus_code->getById($row["bus_code_id"]);
    

                            

                            $new_driver = new Driver($db);
                            $driver = $new_driver->getById($row["driver_id"]);
                            $status = null;

                            $new_conductor = new Conductor($db);
                            $conductor = $new_conductor->getById($row["conductor_id"]);
                            $status = null;

                            

                            if($row["status"] === 'waiting'){
                                $status = '<span class="badge badge-primary">WAITING</span>';
                            }else if($row["status"] === 'arrived'){
                                $status = '<span class="badge badge-success">ARRIVED</span>';
                            }else if($row["status"] === 'cancelled'){
                                $status = '<span class="badge badge-danger">CANCELLED</span>';
                            }else if($row["status"] === 'in-transit'){
                                $status = '<span class="badge badge-info">IN-TRANSIT</span>';
                            }

                            ?>
                            <div class="col-md-4" id="<?php echo $row["id"]; ?>">
                                <div class="bg-white shadow-sm">
                                    <div class="p-3 bg-primary text-white">
                                        <h4 class="mb-0 text-center">
                                            <?php echo $location_from["location_name"].' &#x2192; '.$location_to["location_name"] ?>
                                        </h4>
                                    </div>
                                    <div class="p-3"  style="background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);">
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Date:</span>
                                        <strong><?php echo date_format(date_create($row['schedule_date']),'F j, Y') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Name :</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_num'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Code :</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_code'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Status:</span>
                                        <strong class="text-uppercase"><?php echo $status ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Departure Time:</span>
                                        <strong><?php echo date_format(date_create($row['departure']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Estimated Arrival Time:</span>
                                        <strong><?php echo date_format(date_create($row['arrival']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Driver Name:</span>
                                        <strong class="text-uppercase"><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Type:</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_type'] ?></strong>
                                    </p>
                                                                      
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Fare:</span>
                                        <strong><?php echo $row['fare'] ?></strong>
                                    </p>                                    
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Conductor Name:</span>
                                        <strong class="text-uppercase"><?php echo $conductor['name'] ?></strong>
                                    </p>
                                    

                                        <div class="mt-3">
                                        <a href="#scheduleEditModal" class="btn btn-sm btn-warning scheduleUpdate"
    data-id="<?php echo $row["id"]; ?>" 
    data-route_id="<?php echo $row["route_id"]; ?>"
    data-schedule_date="<?php echo $row["schedule_date"]; ?>" 
    data-departure="<?php echo $row["departure"]; ?>"
    data-arrival="<?php echo $row["arrival"]; ?>" 
    data-bus_id="<?php echo $row["bus_id"]; ?>"
    data-bus_code_id="<?php echo $row["bus_code_id"]; ?>" 
    data-driver_id="<?php echo $row["driver_id"]; ?>" 
    data-fare="<?php echo $row["fare"]; ?>"
    data-conductor_id="<?php echo $row["conductor_id"]; ?>" 
    data-toggle="modal">Edit</a>
                                            <a href="#scheduleDeleteModal" class="btn btn-sm btn-danger scheduleDelete"
                                            data-id="<?php echo $row["id"]; ?>" data-toggle="modal">Delete</a>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'waiting')">Waiting</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'cancelled')">Cancel</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'in-transit')">In-transit</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'arrived')">Arrived</button>
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
        <div class="tab-pane fade" id="in-transit" role="tabpanel" aria-labelledby="in-transit-tab">
            <div class="row mt-3">
                    <?php
                        foreach ($schedules as &$row) {
                            if($row['status'] === 'in-transit'){
                                $new_route = new Route($db);
                            $route = $new_route->getById($row["route_id"]);
                            
                            $location_from = $new_location->getById($route["route_from"]);
                            $location_to = $new_location->getById($route["route_to"]);

                            $new_bus = new Bus($db);
                            $bus = $new_bus->getById($row["bus_id"]);

                            $new_bus_code = new Bus($db);
                            $bus_code = $new_bus_code->getById($row["bus_code_id"]);


                            $new_driver = new Driver($db);
                            $driver = $new_driver->getById($row["driver_id"]);
                            $status = null;

                            $new_conductor = new Conductor($db);
                            $conductor = $new_conductor->getById($row["conductor_id"]);
                            $status = null;

                           

                            if($row["status"] === 'waiting'){
                                $status = '<span class="badge badge-primary">WAITING</span>';
                            }else if($row["status"] === 'arrived'){
                                $status = '<span class="badge badge-success">ARRIVED</span>';
                            }else if($row["status"] === 'cancelled'){
                                $status = '<span class="badge badge-danger">CANCELLED</span>';
                            }else if($row["status"] === 'in-transit'){
                                $status = '<span class="badge badge-info">IN-TRANSIT</span>';
                            }

                            ?>
                            <div class="col-md-4" id="<?php echo $row["id"]; ?>">
                                <div class="bg-white shadow-sm">
                                    <div class="p-3 bg-primary text-white">
                                        <h4 class="mb-0 text-center">
                                            <?php echo $location_from["location_name"].' &#x2192; '.$location_to["location_name"] ?>
                                        </h4>
                                    </div>
                                    <div class="p-3"  style="background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);">
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Date:</span>
                                        <strong><?php echo date_format(date_create($row['schedule_date']),'F j, Y') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Name :</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_num'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Name :</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_code'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Status:</span>
                                        <strong class="text-uppercase"><?php echo $status ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Departure Time:</span>
                                        <strong><?php echo date_format(date_create($row['departure']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Estimated Arrival Time:</span>
                                        <strong><?php echo date_format(date_create($row['arrival']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Driver Name:</span>
                                        <strong class="text-uppercase"><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Type:</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_type'] ?></strong>
                                    </p>
                                    
                                    
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Fare:</span>
                                        <strong><?php echo $row['fare'] ?></strong>
                                    </p>
                                    
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Conductor Name:</span>
                                        <strong class="text-uppercase"><?php echo $conductor['name'] ?></strong>
                                    </p>
                                    

                                        <div class="mt-3">
                                        <a href="#scheduleEditModal" class="btn btn-sm btn-warning scheduleUpdate"
    data-id="<?php echo $row["id"]; ?>" 
    data-route_id="<?php echo $row["route_id"]; ?>"
    data-schedule_date="<?php echo $row["schedule_date"]; ?>" 
    data-departure="<?php echo $row["departure"]; ?>"
    data-arrival="<?php echo $row["arrival"]; ?>" 
    data-bus_id="<?php echo $row["bus_id"]; ?>"
    data-bus_code_id="<?php echo $row["bus_code_id"]; ?>"  
    data-driver_id="<?php echo $row["driver_id"]; ?>" 
    data-conductor_id="<?php echo $row["conductor_id"]; ?>"
    data-fare="<?php echo $row["fare"]; ?>" 
    data-toggle="modal">Edit</a>
                                            <a href="#scheduleDeleteModal" class="btn btn-sm btn-danger scheduleDelete"
                                            data-id="<?php echo $row["id"]; ?>" data-toggle="modal">Delete</a>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'waiting')">Waiting</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'cancelled')">Cancel</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'in-transit')">In-transit</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'arrived')">Arrived</button>
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
        <div class="tab-pane fade" id="arrived" role="tabpanel" aria-labelledby="arrived-tab">
            <div class="row mt-3">
                    <?php
                        foreach ($schedules as &$row) {
                            if($row['status'] === 'arrived'){
                                $new_route = new Route($db);
                            $route = $new_route->getById($row["route_id"]);
                            
                            $location_from = $new_location->getById($route["route_from"]);
                            $location_to = $new_location->getById($route["route_to"]);

                            $new_bus = new Bus($db);
                            $bus = $new_bus->getById($row["bus_id"]);

                            $new_bus_code = new Bus($db);
                            $bus_code = $new_bus_code->getById($row["bus_code_id"]);

                            $new_driver = new Driver($db);
                            $driver = $new_driver->getById($row["driver_id"]);
                            $status = null;

                            $new_conductor = new Conductor($db);
                            $conductor = $new_conductor->getById($row["conductor_id"]);
                            $status = null;

                           

                            if($row["status"] === 'waiting'){
                                $status = '<span class="badge badge-primary">WAITING</span>';
                            }else if($row["status"] === 'arrived'){
                                $status = '<span class="badge badge-success">ARRIVED</span>';
                            }else if($row["status"] === 'cancelled'){
                                $status = '<span class="badge badge-danger">CANCELLED</span>';
                            }else if($row["status"] === 'in-transit'){
                                $status = '<span class="badge badge-info">IN-TRANSIT</span>';
                            }

                            ?>
                            <div class="col-md-4" id="<?php echo $row["id"]; ?>">
                                <div class="bg-white shadow-sm">
                                    <div class="p-3 bg-primary text-white">
                                        <h4 class="mb-0 text-center">
                                            <?php echo $location_from["location_name"].' &#x2192; '.$location_to["location_name"] ?>
                                        </h4>
                                    </div>
                                    <div class="p-3"  style="background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);">
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Date:</span>
                                        <strong><?php echo date_format(date_create($row['schedule_date']),'F j, Y') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Name :</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_num'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Code :</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_code'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Status:</span>
                                        <strong class="text-uppercase"><?php echo $status ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Departure Time:</span>
                                        <strong><?php echo date_format(date_create($row['departure']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Estimated Arrival Time:</span>
                                        <strong><?php echo date_format(date_create($row['arrival']), 'g:i A') ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Driver :</span>
                                        <strong class="text-uppercase"><?php echo $driver['name'] ?></strong>
                                    </p>
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Bus Type:</span>
                                        <strong class="text-uppercase"><?php echo $bus['bus_type'] ?></strong>
                                    </p>
                                    
                                    
                                    <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Fare:</span>
                                        <strong><?php echo $row['fare'] ?></strong>
                                    </p>
                                    
                                     <p class="d-flex align-items-center justify-content-between mb-0">
                                        <span class="text-muted d-block">Conductor Name:</span>
                                        <strong class="text-uppercase"><?php echo $conductor['name'] ?></strong>
                                    </p>
                                    

                                        <div class="mt-3">
                                        <a href="#scheduleEditModal" class="btn btn-sm btn-warning scheduleUpdate"
    data-id="<?php echo $row["id"]; ?>" 
    data-route_id="<?php echo $row["route_id"]; ?>"
    data-schedule_date="<?php echo $row["schedule_date"]; ?>" 
    data-departure="<?php echo $row["departure"]; ?>"
    data-arrival="<?php echo $row["arrival"]; ?>" 
    data-bus_id="<?php echo $row["bus_id"]; ?>" 
    data-bus_code_id="<?php echo $row["bus_code_id"]; ?>"
    data-driver_id="<?php echo $row["driver_id"]; ?>" 
    data-conductor_id="<?php echo $row["conductor_id"]; ?>"
    data-fare="<?php echo $row["fare"]; ?>" 
    data-toggle="modal">Edit</a>
                                            <a href="#scheduleDeleteModal" class="btn btn-sm btn-danger scheduleDelete"
                                            data-id="<?php echo $row["id"]; ?>" data-toggle="modal">Delete</a>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'waiting')">Waiting</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'cancelled')">Cancel</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'in-transit')">In-transit</button>
                                                <button type="button" class="btn btn-sm btn-dark" onclick="updateStatus('<?php echo $row['id'] ?>', 'arrived')">Arrived</button>
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
        </div>
    </div>

<!-- New Bus Modal -->
<div class="modal fade" id="newScheduleModal" tabindex="-1" aria-labelledby="newScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="schedule_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="newScheduleModalLabel">New Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="1" name="type">
                    <input type="hidden" value="waiting" name="status">

                    <div class="form-group">
                        <label>Route</label>
                        <select class="form-control" id="route_id" name="route_id" required>
                            <option value="">Select route</option>
                            <?php
                                $route_result = mysqli_query($conn,"SELECT * FROM tblroute");
                                while($route_row = mysqli_fetch_array($route_result)) {
                                $location_from = $new_location->getById($route_row["route_from"]);
                                $location_to = $new_location->getById($route_row["route_to"]);
                            ?>
                                <option value="<?php echo $route_row["id"]; ?>">
                                    <?php echo $location_from["location_name"]." - ".$location_to["location_name"]; ?>
                                </option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" id="schedule_date" name="schedule_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Bus Code</label>
                        <select class="form-control" id="bus_code_id" name="bus_code_id" required>
                            <option value="">Select bus</option>
                            <?php
                            $bus_result = mysqli_query($conn,"SELECT * FROM tblbus");
                            while($bus_row = mysqli_fetch_array($bus_result)) {
                        ?>
                            <option value="<?php echo $bus_row["id"]; ?>"><?php echo $bus_row["bus_code"]; ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bus Name</label>
                        <select class="form-control" id="bus_id" name="bus_id" required>
                            <option value="">Select bus</option>
                            <?php
                            $bus_result = mysqli_query($conn,"SELECT * FROM tblbus");
                            while($bus_row = mysqli_fetch_array($bus_result)) {
                        ?>
                            <option value="<?php echo $bus_row["id"]; ?>"><?php echo $bus_row["bus_num"]; ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Departure Time</label>
                        <input type="time" id="departure" name="departure" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Estimated Arrival Time</label>
                        <input type="time" id="arrival" name="arrival" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Bus Driver</label>
                        <select class="form-control" id="driver_id" name="driver_id" required>
                            <option value="">Select driver</option>
                            <?php
                            $driver_result = mysqli_query($conn,"SELECT * FROM tbldriver");
                            while($driver_row = mysqli_fetch_array($driver_result)) {
                        ?>
                            <option value="<?php echo $driver_row["id"]; ?>"><?php echo $driver_row["name"]; ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Bus Type</label>
                        <select class="form-control" id="bus_type" name="bus_type" required>
                            <option value="">Select bus type</option>
                            <option value="regular">Regular</option>
                            <option value="air_condition">Air Condition</option>
                        </select>
                    </div>

                     <div class="form-group">
                        <label>Conductor</label>
                        <select class="form-control" id="conductor_id" name="conductor_id" required>
                            <option value="">Select conductor</option>
                            <?php
                            $conductor_result = mysqli_query($conn,"SELECT * FROM tblconductor");
                            while($conductor_row = mysqli_fetch_array($conductor_result)) {
                        ?>
                            <option value="<?php echo $conductor_row["id"]; ?>"><?php echo $conductor_row["name"]; ?>
                            </option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>
                    

                    

                 <div class="form-group">
                        <label>Fare</label>
                        <input type="number" id="fare" name="fare" class="form-control" required>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="btn-add" type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Bus Modal -->
<div class="modal fade" id="scheduleEditModal" tabindex="-1" aria-labelledby="scheduleEditModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_schedule_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleEditModalLabel">Edit Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="2" name="type">
                    <input type="hidden" value="waiting" name="status">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>

                    <div class="form-group">
                        <label>Route</label>
                        <select class="form-control" id="route_id_u" name="route_id" required>
                            <option value="">Select route</option>
                            <?php
                                $route_result = mysqli_query($conn,"SELECT * FROM tblroute");
                                while($route_row = mysqli_fetch_array($route_result)) {
                                $location_from = $new_location->getById($route_row["route_from"]);
                                $location_to = $new_location->getById($route_row["route_to"]);
                            ?>
                                <option value="<?php echo $route_row["id"]; ?>">
                                    <?php echo $location_from["location_name"]." - ".$location_to["location_name"]; ?>
                                </option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>

                    

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" id="schedule_date_u" name="schedule_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Departure Time</label>
                        <input type="time" id="departure_u" name="departure" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Estimated Arrival Time</label>
                        <input type="time" id="arrival_u" name="arrival" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Bus Name</label>
                        <select class="form-control" id="bus_id_u" name="bus_id" required>
                            <option value="">Select bus</option>
                            <?php
                            $bus_result = mysqli_query($conn,"SELECT * FROM tblbus");
                            while($bus_row = mysqli_fetch_array($bus_result)) {
                        ?>
                            <option value="<?php echo $bus_row["id"]; ?>"><?php echo $bus_row["bus_num"]; ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Bus Code</label>
                        <select class="form-control" id="bus_code_id_u" name="bus_code_id" required>
                            <option value="">Select bus</option>
                            <?php
                            $bus_result = mysqli_query($conn,"SELECT * FROM tblbus");
                            while($bus_row = mysqli_fetch_array($bus_result)) {
                        ?>
                            <option value="<?php echo $bus_row["id"]; ?>"><?php echo $bus_row["bus_code"]; ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bus Driver</label>
                        <select class="form-control" id="driver_id_u" name="driver_id" required>
                            <option value="">Select driver</option>
                            <?php
                            $driver_result = mysqli_query($conn,"SELECT * FROM tbldriver");
                            while($driver_row = mysqli_fetch_array($driver_result)) {
                        ?>
                            <option value="<?php echo $driver_row["id"]; ?>"><?php echo $driver_row["name"]; ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Conductor</label>
                        <select class="form-control" id="conductor_id_u" name="conductor_id" required>
                            <option value="">Select conductor</option>
                            <?php
                            $conductor_result = mysqli_query($conn,"SELECT * FROM tblconductor");
                            while($conductor_row = mysqli_fetch_array($conductor_result)) {
                        ?>
                            <option value="<?php echo $conductor_row["id"]; ?>"><?php echo $conductor_row["name"]; ?>
                            </option>
                            <?php
                            }
                        ?>
                        </select>
                    </div> 
                    

                    <div class="form-group">
                        <label>Fare</label>
                        <input type="number" id="fare_u" name="fare" class="form-control" required readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="btn-update" type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
            </form>
        </div>
    </div>
</div>

<!-- Bus Delete Modal HTML -->
<div id="scheduleDeleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete_schedule_form">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Schedule</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_d" name="id" class="form-control">
                    <p class="mb-0">Are you sure you want to delete these Records?</p>
                    <p class="text-warning"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-danger" id="delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#myTable').DataTable();

$("#schedule_form").submit(function(event) {
    event.preventDefault();
    var data = $("#schedule_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/schedule.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#newScheduleModal").modal("hide");
                alert("New schedule added successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".scheduleUpdate", function(e) {
    var id = $(this).attr("data-id");
    var route_id = $(this).attr("data-route_id");
    var schedule_date = $(this).attr("data-schedule_date");
    var departure = $(this).attr("data-departure");
    var arrival = $(this).attr("data-arrival");
    var bus_id = $(this).attr("data-bus_id");
    var bus_code_id = $(this).attr("data-bus_code_id");
    var driver_id = $(this).attr("data-driver_id");
    var conductor_id = $(this).attr("data-conductor_id");
    var fare = $(this).attr("data-fare");

    $("#id_u").val(id);
    $("#route_id_u").val(route_id);
    $("#schedule_date_u").val(schedule_date);
    $("#departure_u").val(departure);
    $("#arrival_u").val(arrival);
    $("#bus_id_u").val(bus_id);
    $("#bus_code_id_u").val(bus_code_id);
    $("#driver_id_u").val(driver_id);
    $("#conductor_id_u").val(conductor_id);
    $("#fare_u").val(fare);
});

function formatDateTimeLocal(mydate) {
    const now = new Date(mydate);
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    return now.toISOString().slice(0, 16);
}

$("#edit_schedule_form").submit(function(event) {
    event.preventDefault();
    var data = $("#edit_schedule_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/schedule.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#scheduleEditModal").modal("hide");
                alert("Schedule updated successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".scheduleDelete", function() {
    var id = $(this).attr("data-id");
    $("#id_d").val(id);
});

$("#delete_schedule_form").submit(function(event) {
    event.preventDefault();
    $.ajax({
        cache: false,
        data: {
            type: 3,
            id: $("#id_d").val(),
        },
        type: "post",
        url: "backend/schedule.php",
        success: function(dataResult) {
            alert("Schedule deleted successfully!");
            $("#scheduleDeleteModal").modal("hide");
            $("#" + dataResult).remove();
        },
    });
});

function updateStatus(id, status) {
    console.log({id, status})
    if(confirm('Are you sure to update schedule status ?')){
        $.ajax({
            cache: false,
            data: {
                type: 4,
                id,
                status
            },
            type: "post",
            url: "backend/schedule.php",
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    $("#scheduleEditModal").modal("hide");
                    alert("Schedule status updated successfully!");
                    location.reload();
                } else if (dataResult.statusCode == 201) {
                    alert(dataResult);
                }
            },
        });
    }
}
    const schedule_date = document.getElementById("schedule_date");
    const schedule_date_u = document.getElementById("schedule_date_u");
    schedule_date.min =new Date().toISOString().split("T")[0];
    schedule_date_u.min =new Date().toISOString().split("T")[0];

</script>

<?php include('includes/scripts.php')?>
