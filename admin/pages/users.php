<?php
// Remove '.php' from the URL
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
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

        /* Responsive styling */
        @media (max-width: 768px) {
            .breadcrumb-item a, .breadcrumb-item.active {
                font-size: 0.9em;
            }
            .btn {
                font-size: 0.8em;
                padding: 8px 12px;
            }
            .card-body {
                padding: 1em;
            }
            table {
                font-size: 0.9em;
            }
        }

        @media (max-width: 576px) {
            .breadcrumb-item a, .breadcrumb-item.active {
                font-size: 0.8em;
            }
            .btn-group {
                flex-direction: column;
            }

            /* Responsive table design */
            #myTable {
                display: block;
            }
            #myTable thead {
                display: none;
            }
            #myTable tbody, #myTable tr, #myTable td {
                display: block;
                width: 100%;
            }
            #myTable tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #ddd;
                padding-bottom: 15px;
            }
            #myTable td {
                display: flex;
                justify-content: space-between;
                padding: 8px;
            }
            #myTable td::before {
                content: attr(data-label);
                font-weight: bold;
                width: 50%;
                display: inline-block;
                color: #555;
            }
        }
    </style>

  </head>
  <body>
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/ceres/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
          <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>USERS</b></li>
        </ol>
      </nav>

      <div class="card">
        <div class="card-header">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newUserModal">
            <i class="fa fa-plus"> New User </i>
          </button>
        </div>

        <div class="card-body" style="background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);">
          <table id="myTable" class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Fullname</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Date Created</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $result = mysqli_query($conn,"SELECT * FROM tbluser");
                $i=1;
                while($row = mysqli_fetch_array($result)) {
              ?>
              <tr id="<?php echo $row["id"]; ?>">
                <td data-label="#"> <?php echo $i; ?></td>
                <td data-label="Fullname"> <?php echo $row["fullname"]; ?></td>
                <td data-label="Username"> <?php echo $row["username"]; ?></td>
                <td data-label="Email"> <?php echo $row["email"]; ?></td>
                <td data-label="Date Created"> <?php echo date('m-d-Y', strtotime($row["date_created"])); ?></td>
                <td data-label="Actions">
                  <?php
                    if($row['status'] === '1'){
                  ?>
                  <div class="btn-group btn-group-sm" role="group" aria-label="<?php echo $row["id"]; ?>">
                    <button class="btn btn-dark" disabled>Active</button>
                    <button class="btn btn-outline-dark" onclick="handleUserStatus(<?php echo $row['id']; ?>, '0')">Deactivate</button>
                  </div>
                  <?php
                    } else {
                  ?>
                  <div class="btn-group btn-group-sm" role="group" aria-label="<?php echo $row["id"]; ?>">
                    <button class="btn btn-outline-dark" onclick="handleUserStatus(<?php echo $row['id']; ?>, '1')">Active</button>
                    <button class="btn btn-dark" disabled>Deactivate</button>
                  </div>
                  <?php
                    }
                  ?>
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

    <!-- New User Modal -->
    <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="user_form">
            <div class="modal-header">
              <h5 class="modal-title" id="newUserModalLabel">New User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" value="1" name="type">
              <div class="form-group">
                <label>Fullname</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
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

    <script>
      $('#myTable').DataTable();

      $("#user_form").submit(function(event) {
        event.preventDefault();
        var data = $("#user_form").serialize();
        $.ajax({
          data: data,
          type: "post",
          url: "backend/user.php",
          success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
              $("#newUserModal").modal("hide");
              alert("New user added successfully!");
              location.reload();
            } else {
              alert(dataResult.title);
            }
          },
        });
      });

      function handleUserStatus(id, status) {
        const text = status === '0' ? "Are you sure you want to deactivate this user?" :
          "Are you sure you want to activate this user?"

        if (confirm(text)) {
          $.ajax({
            data: {
              type: 2,
              id,
              status
            },
            type: "post",
            url: "backend/user.php",
            success: function(dataResult) {
              location.reload();
            }
          });
        }
      }
    </script>
    <script src="./assets/jquery/jquery.min.js"></script>
    <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/datatables/jquery.dataTables.min.js"></script>
    <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>
  </body>
</html>
