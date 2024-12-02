<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/ceres/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>PASSENGERS</b></li>
        </ol>
    </nav>

    <div class="card">
        <!-- <div class="card-header">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newDriverModal">
                New Conductor
            </button>
        </div> -->
        <div class="card-body" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
        <table id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                    <!-- <th scope="col">Actions</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = mysqli_query($conn,"SELECT * FROM tblpassenger");
                    $i=1;
                    while($row = mysqli_fetch_array($result)) {
                ?>
                <tr id="<?php echo $row["id"]; ?>">
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $row["first_name"]." ".$row["last_name"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["address"]; ?></td>
                    <!-- <td>
                        <a href="#customerEditModal" class="btn btn-sm btn-warning customerUpdate"
                            data-id="<?php echo $row["id"]; ?>" data-firstname="<?php echo $row["firstname"]; ?>"
                            data-lastname="<?php echo $row["lastname"]; ?>" data-email="<?php echo $row["email"]; ?>"
                            data-contact="<?php echo $row["contact"]; ?>" data-address="<?php echo $row["address"]; ?>"
                            data-toggle="modal">Edit</a>
                        <a href="#customerDeleteModal" class="btn btn-sm btn-danger customerDelete"
                            data-id="<?php echo $row["id"]; ?>" data-toggle="modal">Delete</a>
                    </td> -->
                </tr>
                <?php
				    $i++;
				    }
				?>
            </tbody>
        </table>
        </div>
    </div>

    <!-- <div class="bg-white shadow p-3 rounded">
        <div class="text-right mb-3">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newCustomerModal">
                New Customer
            </button>
        </div>
    </div> -->
</div>

<!-- New Bus Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="customer_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCustomerModalLabel">New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="1" name="type">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Firstname</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Lastname</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Contact #</label>
                        <input type="number" id="contact" name="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="address" name="address" class="form-control" required>
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
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="customerEditModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_customer_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerEditModalLabel">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="2" name="type">
                    <input type="hidden" id="id_u" name="id" class="form-control" required>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Firstname</label>
                            <input type="text" id="firstname_u" name="firstname" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Lastname</label>
                            <input type="text" id="lastname_u" name="lastname" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email_u" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Contact #</label>
                        <input type="number" id="contact_u" name="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="address_u" name="address" class="form-control" required>
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
<div id="customerDeleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete_customer_form">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Customer</h4>
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
<script>
$('#myTable').DataTable();

$("#customer_form").submit(function(event) {
    event.preventDefault();
    var data = $("#customer_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/customer.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#newCustomerModal").modal("hide");
                alert("New customer added successfully!");
                location.reload();
            } else {
                alert(dataResult.title);
            }
        },
    });
});

$(document).on("click", ".customerUpdate", function(e) {
    var id = $(this).attr("data-id");
    var firstname = $(this).attr("data-firstname");
    var lastname = $(this).attr("data-lastname");
    var email = $(this).attr("data-email");
    var contact = $(this).attr("data-contact");
    var address = $(this).attr("data-address");
    $("#id_u").val(id);
    $("#firstname_u").val(firstname);
    $("#lastname_u").val(lastname);
    $("#email_u").val(email);
    $("#contact_u").val(contact);
    $("#address_u").val(address);
});

$("#edit_customer_form").submit(function(event) {
    event.preventDefault();
    var data = $("#edit_customer_form").serialize();
    $.ajax({
        data: data,
        type: "post",
        url: "backend/customer.php",
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#customerEditModal").modal("hide");
                alert("Customer updated successfully!");
                location.reload();
            } else {
                alert(dataResult.title);
            }
        },
    });
});

$(document).on("click", ".customerDelete", function() {
    var id = $(this).attr("data-id");
    $("#id_d").val(id);
});

$("#delete_customer_form").submit(function(event) {
    event.preventDefault();
    $.ajax({
        cache: false,
        data: {
            type: 3,
            id: $("#id_d").val(),
        },
        type: "post",
        url: "backend/customer.php",
        success: function(dataResult) {
            alert("Customer deleted successfully!");
            $("#customerDeleteModal").modal("hide");
            $("#" + dataResult).remove();
            location.reload();
        },
    });
});
</script>
<?php include('includes/scripts.php')?>
