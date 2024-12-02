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
            0% { border-color: #f00; }
            50% { border-color: #0f0; }
            100% { border-color: #00f; }
        }
        
        .card {
            border: 3px solid transparent;
            border-radius: 5px;
            animation: ledBorder 1.5s infinite alternate;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .fa-plus {
            margin-right: 5px;
        }
    </style>

  </head>
<?php
include('../controllers/db.php');
include('../controllers/location.php');

$database = new Database();
$db = $database->getConnection();

$new_location = new Location($db);
$locations = $new_location->getAll();
?>

<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/ceres/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>ROUTES</b></li>
       </ol>
    </nav>

    <div class="card" >
        <div class="card-header">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newRouteModal">
            <i class="fa fa-plus" >    New Route</i>
            </button>
        </div>
        <div class="card-body" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn,"SELECT * FROM tblroute");
                    $i=1;
                    while($row = mysqli_fetch_array($result)) {
                        $location_from = $new_location->getById($row["route_from"]);
                        $location_to = $new_location->getById($row["route_to"]);
                ?>
                    <tr id="<?php echo $row["id"]; ?>">
                        <th scope="row"><?php echo $i; ?></th>
                        <td><?php echo $location_from["location_name"]; ?></td>
                        <td><?php echo $location_to["location_name"]; ?></td>
                        <td>
                            <a href="#routeEditModal" class="btn btn-sm btn-warning routeUpdate"
                                data-id="<?php echo $row["id"]; ?>" data-route_from="<?php echo $row["route_from"]; ?>"
                                data-route_to="<?php echo $row["route_to"]; ?>"
                                data-toggle="modal">Edit</a>
                            <a href="#routeDeleteModal" class="btn btn-sm btn-danger routeDelete"
                                data-id="<?php echo $row["id"]; ?>" data-toggle="modal">Delete</a>
                        </td>
                    </tr>
                    <?php
				    $i++;
				    }
				?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Bus Modal -->
<div class="modal fade" id="newRouteModal" tabindex="-1" aria-labelledby="newRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="route_form" style="background: #8e9eab;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #eef2f3, #8e9eab);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #eef2f3, #8e9eab); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRouteModalLabel">New Route</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: #8e9eab;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #eef2f3, #8e9eab);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #eef2f3, #8e9eab); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
                    <input type="hidden" value="1" name="type">

                    <div class="form-group">
                        <label>Route From</label>
                        <select class="form-control" id="route_from" name="route_from" required>
                            <option value="">Please select location</option>
                            <?php
                                foreach ($locations as $row) {
                                    echo '<option value="'.$row['id'].'">'.$row['location_name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Route To</label>
                        <select class="form-control" id="route_to" name="route_to" required>
                            <option value="">Please select location</option>
                            <?php
                                foreach ($locations as $row) {
                                    echo '<option value="'.$row['id'].'">'.$row['location_name'].'</option>';
                                }
                            ?>
                        </select>
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
<div class="modal fade" id="routeEditModal" tabindex="-1" aria-labelledby="routeEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_route_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="routeEditModalLabel">Edit Conductor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="2" name="type">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>

                    <div class="form-group">
                        <label>Route From</label>
                        <select class="form-control" id="route_from_u" name="route_from" required>
                            <option value="">Please select location</option>
                            <?php
                                foreach ($locations as $row) {
                                    echo '<option value="'.$row['id'].'">'.$row['location_name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Route To</label>
                        <select class="form-control" id="route_to_u" name="route_to" required>
                            <option value="">Please select location</option>
                            <?php
                                foreach ($locations as $row) {
                                    echo '<option value="'.$row['id'].'">'.$row['location_name'].'</option>';
                                }
                            ?>
                        </select>
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

<!-- Bus Delete Modal HTML -->
<div id="routeDeleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete_route_form">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Driver</h4>
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

$("#route_form").submit(function(event) {
    event.preventDefault();
    var data = $("#route_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/route.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#newRouteModal").modal("hide");
                alert("New route added successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".routeUpdate", function(e) {
    var id = $(this).attr("data-id");
    var route_from = $(this).attr("data-route_from");
    var route_to = $(this).attr("data-route_to");
    $("#id_u").val(id);
    $("#route_from_u").val(route_from);
    $("#route_to_u").val(route_to);
});

$("#edit_route_form").submit(function(event) {
    event.preventDefault();
    var data = $("#edit_route_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/route.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#routeEditModal").modal("hide");
                alert("Route updated successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".routeDelete", function() {
    var id = $(this).attr("data-id");
    $("#id_d").val(id);
});

$("#delete_route_form").submit(function(event) {
    event.preventDefault();
    $.ajax({
        cache: false,
        data: {
            type: 3,
            id: $("#id_d").val(),
        },
        type: "post",
        url: "backend/route.php",
        success: function(dataResult) {
            alert("Route deleted successfully!");
            $("#routeDeleteModal").modal("hide");
            $("#" + dataResult).remove();
        },
    });
});
</script>
<?php include('includes/scripts.php')?>
