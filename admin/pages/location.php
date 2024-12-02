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
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/ceres/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>LOCATIONS</b></li>
     </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newLocationModal">
            <i class="fa fa-plus" >  New Location</i> 
            </button>
        </div>
        <div class="card-body" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
        <table id="myTable" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Location Name</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = mysqli_query($conn,"SELECT * FROM tbllocation");
                    $i=1;
                    while($row = mysqli_fetch_array($result)) {
                ?>
                <tr id="<?php echo $row["id"]; ?>">
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $row["location_name"]; ?></td>

                    <td>
                        <a href="#locationEditModal" class="btn btn-sm btn-warning locationUpdate"
                            data-id="<?php echo $row["id"]; ?>" data-location_name="<?php echo $row["location_name"]; ?>"
                            data-toggle="modal">Edit</a>
                        <a href="#locationDeleteModal" class="btn btn-sm btn-danger locationDelete"
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

<!-- New Location Modal -->
<div class="modal fade" id="newLocationModal" tabindex="-1" aria-labelledby="newLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="location_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="newLocationModalLabel">New Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="1" name="type">

                    <div class="form-group">
                        <label>Location Name</label>
                        <input type="text" id="name" name="location_name" class="form-control" required>
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

<!-- Edit Location Modal -->
<div class="modal fade" id="locationEditModal" tabindex="-1" aria-labelledby="locationEditModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_location_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationEditModalLabel">Edit Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="2" name="type">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>

                    <div class="form-group">
                        <label>Location Name</label>
                        <input type="text" id="name_u" name="location_name" class="form-control" required>
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

<!-- Location Delete Modal HTML -->
<div id="locationDeleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete_location_form">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Location</h4>
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

$("#location_form").submit(function(event) {
    event.preventDefault();
    var data = $("#location_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/location.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#newLocationModal").modal("hide");
                alert("New location added successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".locationUpdate", function(e) {
    var id = $(this).attr("data-id");
    var name = $(this).attr("data-location_name");
    $("#id_u").val(id);
    $("#name_u").val(name);
});

$("#edit_location_form").submit(function(event) {
    event.preventDefault();
    var data = $("#edit_location_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/location.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#locationEditModal").modal("hide");
                alert("Location updated successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".locationDelete", function() {
    var id = $(this).attr("data-id");
    $("#id_d").val(id);
});

$("#delete_location_form").submit(function(event) {
    event.preventDefault();
    $.ajax({
        cache: false,
        data: {
            type: 3,
            id: $("#id_d").val(),
        },
        type: "post",
        url: "backend/location.php",
        success: function(dataResult) {
            alert("Location deleted successfully!");
            $("#locationDeleteModal").modal("hide");
            $("#" + dataResult).remove();
            location.reload();
        },
    });
});
</script>
<?php include('includes/scripts.php')?>
