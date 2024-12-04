<?php
    include '../dbconfig.php';
    include '../../controllers/bus.php';
    include '../../controllers/route.php';

    if(count($_POST)>0){
        if($_POST['type']==1){
            $bus_id=$_POST['bus_id'];
            $bus_code_id=$_POST['bus_code_id'];
            $driver_id=$_POST['driver_id'];
            $conductor_id=$_POST['conductor_id'];
            $route_id=$_POST['route_id'];
            $departure=$_POST['departure'];
            $arrival=$_POST['arrival'];
            $status=$_POST['status'];
            $schedule_date=$_POST['schedule_date'];

            $new_bus = new Bus($conn);
            $bus = $new_bus->getById($bus_id);
            $new_route = new Route($conn);
            $route = $new_route->getById($route_id);

           // $fare=$bus['rate_km']*$route['distance'];
            
           $fare=$_POST['fare'];

            $sql = "INSERT INTO `tblschedule`( `bus_id`, `bus_code_id`, `driver_id`, `conductor_id`, `route_id`, `departure`, `arrival`, `status`, `fare`, `schedule_date`) 
            VALUES ('$bus_id', '$bus_code_id', '$driver_id', '$conductor_id', '$route_id', '$departure', '$arrival', '$status', '$fare', '$schedule_date')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==2){
            $id=$_POST['id'];
            $bus_id=$_POST['bus_id'];
            $bus_code_id=$_POST['bus_code_id'];
            $driver_id=$_POST['driver_id'];
            $conductor_id=$_POST['conductor_id'];
            $route_id=$_POST['route_id'];
            $departure=$_POST['departure'];
            $arrival=$_POST['arrival'];
            $status=$_POST['status'];
            $schedule_date=$_POST['schedule_date'];

            $new_bus = new Bus($conn);
            $bus = $new_bus->getById($bus_id);
            $new_route = new Route($conn);
            $route = $new_route->getById($route_id);

            $fare=$_POST['fare'];
            
            $sql = "UPDATE `tblschedule` SET `bus_id`='$bus_id', `bus_code_id`='$bus_code_id', `driver_id`='$driver_id', `conductor_id`='$conductor_id',  `route_id`='$route_id', `departure`='$departure', `arrival`='$arrival', `fare`='$fare', `schedule_date`='$schedule_date' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==3){
            $id=$_POST['id'];
            $sql = "DELETE FROM `tblschedule` WHERE id=$id ";
            if (mysqli_query($conn, $sql)) {
                echo $id;
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

    if(count($_POST)>0){
        if($_POST['type']==4){
            $id=$_POST['id'];
            $status=$_POST['status'];
            
            $sql = "UPDATE `tblschedule` SET `status`='$status' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(array("statusCode"=>200));
            } 
            else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }
?>