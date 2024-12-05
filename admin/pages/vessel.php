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


  </head>
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/onlinebusreservation/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>VESSELS</b></li>
     </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newDriverModal">
            <i class="fa fa-plus" >  New Vessel </i>
            </button>
        </div>
        <div class="card-body">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vessel name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn,"SELECT * FROM tblvessel");
                    $i=1;
                    while($row = mysqli_fetch_array($result)) {
                ?>
                    <tr id="<?php echo $row["id"]; ?>">
                        <th scope="row"><?php echo $i; ?></th>
                        <td><?php echo $row["vessel_name"]; ?></td>
                        <td>
                            <a href="#driverEditModal" class="btn btn-sm btn-warning driverUpdate"
                                data-id="<?php echo $row["id"]; ?>" data-vessel_name="<?php echo $row["vessel_name"]; ?>"
                                 data-toggle="modal">Edit</a>
                            <a href="#driverDeleteModal" class="btn btn-sm btn-danger driverDelete"
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
<div class="modal fade" id="newDriverModal" tabindex="-1" aria-labelledby="newBusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="driver_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="newBusModalLabel">New Vessel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="1" name="type">

                    <div class="form-group">
                        <label>Vessel name</label>
                        <input type="text" id="vessel_name" name="vessel_name" class="form-control" required>
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
<div class="modal fade" id="driverEditModal" tabindex="-1" aria-labelledby="driverEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_driver_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="driverEditModalLabel">Edit Vessel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="2" name="type">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>

                    <div class="form-group">
                        <label>Vessel name</label>
                        <input type="text" id="vessel_name_u" name="vessel_name" class="form-control" required>
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
<div id="driverDeleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete_driver_form">
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

$("#driver_form").submit(function(event) {
    event.preventDefault();
    var data = $("#driver_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/vessel.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#newDriverModal").modal("hide");
                alert("New vessel added successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".driverUpdate", function(e) {
    var id = $(this).attr("data-id");
    var vessel_name = $(this).attr("data-vessel_name");
     $("#id_u").val(id);
    $("#vessel_name_u").val(vessel_name);
  
});

$("#edit_driver_form").submit(function(event) {
    event.preventDefault();
    var data = $("#edit_driver_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/vessel.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#driverEditModal").modal("hide");
                alert("Vessel updated successfully!");
                location.reload();
            } else if (dataResult.statusCode == 201) {
                alert(dataResult);
            }
        },
    });
});

$(document).on("click", ".driverDelete", function() {
    var id = $(this).attr("data-id");
    $("#id_d").val(id);
});

$("#delete_driver_form").submit(function(event) {
    event.preventDefault();
    $.ajax({
        cache: false,
        data: {
            type: 3,
            id: $("#id_d").val(),
        },
        type: "post",
        url: "backend/vessel.php",
        success: function(dataResult) {
            alert("Vessel deleted successfully!");
            $("#driverDeleteModal").modal("hide");
            $("#" + dataResult).remove();
            location.reload();
        },
    });
});
</script>