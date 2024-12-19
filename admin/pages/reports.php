<?php
    // Database connection
    $conn = mysqli_connect('localhost', 'u510162695_bobrs', '1Bobrs_password', 'u510162695_bobrs');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to fetch driver, conductor, bus, route, schedule, and fare data based on schedule_id
    $query = "
        SELECT d.id as driver_id, d.name as driver_name, c.name as conductor_name, 
               rl.location_name as route_from, rl2.location_name as route_to, 
               s.schedule_date, b1.bus_num, b2.bus_code, 
               IFNULL(SUM(bk.total), 0) as total_fare_per_day
        FROM tbldriver d
        LEFT JOIN tblconductor c ON d.id = c.id
        LEFT JOIN tblbus b1 ON d.id = b1.id
        LEFT JOIN tblbus b2 ON d.id = b2.id
        LEFT JOIN tblroute r ON d.id = r.id
        LEFT JOIN tblschedule s ON d.id = s.driver_id
        LEFT JOIN tbllocation rl ON r.route_from = rl.id
        LEFT JOIN tbllocation rl2 ON r.route_to = rl2.id
        LEFT JOIN tblbook bk ON s.id = bk.schedule_id  -- Join based on schedule_id from tblbook
        GROUP BY s.id, s.schedule_date, d.id, c.id, rl.location_name, rl2.location_name, b1.bus_num, b2.bus_code
        ORDER BY s.schedule_date ASC";
        
    $result = mysqli_query($conn, $query);

    // Check for any errors in the query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/styles.css" />

    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <title>Bantayan Online Bus Reservation</title>

    <style>
        /* Custom CSS for larger table and hiding action column */
        #myTable {
            width: 100%; /* Full width of container */
            margin: auto; /* Center the table */
            font-size: 14px; /* Adjust font size as needed */
        }

        .hide-on-print {
            display: none;
        }

        @media print {
            body {
                margin-top: 1in;
                margin-bottom: 1in;
                margin-left: 1in;
                margin-right: 2in;
            }
        }

        /* Make the table scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
        }

        /* Chart responsiveness */
        #googleChart {
            width: 100% !important;
            height: 400px;
        }

        /* Responsive styles for smaller devices */
        @media (max-width: 576px) {
            #myTable {
                font-size: 12px; /* Smaller font for mobile */
            }

            .breadcrumb {
                font-size: 14px; /* Adjust breadcrumb font size */
            }

            .btn {
                font-size: 12px; /* Smaller buttons */
            }
        }

        /* Custom print styles */
        @media print {
            .table-responsive {
                overflow-x: visible;
            }
            .breadcrumb, .btn {
                display: none; /* Hide breadcrumb and buttons on print */
            }
        }
    </style>
</head>
<body>
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>Reports</b></li>
        </ol>
    </nav>

    <!-- Google Chart placeholder -->
    <div class="row mt-4" style="background-color: #D9AFD9; background-image: linear-gradient(0deg, #D9AFD9 0%, #97D9E1 100%);">
        <div class="col-12">
            <div id="googleChart"></div>
        </div>
    </div>
</div>

<div class="card-body">
    <!-- Print button for chart and table -->
    <div class="text-right mb-3">
        <button class="btn btn-primary" onclick="printContent()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

    <div class="table-responsive">
        <table id="myTable" class="table table-striped" style="background-color: #D9AFD9; background-image: linear-gradient(0deg, #D9AFD9 0%, #97D9E1 100%);">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Drivers Name</th>
                    <th scope="col">Conductors Name</th>
                    <th scope="col">Bus Name</th>
                    <th scope="col">Bus Code</th>
                    <th scope="col">From</th>
                    <th scope="col">To</th>
                    <th scope="col">Total Fare (Per Day)</th> <!-- Added Total Fare Column -->
                    <th scope="col">Schedule Date</th>
                    <th scope="col" class="hide-on-print">Actions</th> <!-- Hide this column on print -->
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    // Loop through data
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr id='{$row["driver_id"]}'>
                                <th scope='row'>{$i}</th>
                                <td>{$row["driver_name"]}</td>
                                <td>{$row["conductor_name"]}</td>
                                <td>{$row["bus_num"]}</td>
                                <td>{$row["bus_code"]}</td>
                                <td>{$row["route_from"]}</td>
                                <td>{$row["route_to"]}</td>
                                <td>{$row["total_fare_per_day"]}</td> <!-- Display Total Fare Per Day -->
                                <td>{$row["schedule_date"]}</td>
                                <td class='hide-on-print'>
                                    <button class='btn btn-sm btn-secondary' onclick='printRow(\"{$row["driver_id"]}\")'>
                                        Print
                                    </button>
                                </td>
                            </tr>";
                        $i++;
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Google Charts script -->
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        <?php
            // Fetch total fare
            $query = "SELECT SUM(total) AS total_fare FROM tblbook";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $totalFare = $row['total_fare'];

            // Fetch booking count
            $bookingCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblbook"));
        ?>

        var data = google.visualization.arrayToDataTable([
            ['Category', 'Amount', { role: 'style' }],
            ['Bookings', <?php echo $bookingCount; ?>, '#FA8072'],
            ['Fare', <?php echo $totalFare; ?>, '#6CB4EE']
        ]);

        var options = {
            legend: { position: 'none' },
            colors: ['#FA8072', '#6CB4EE'],
            bar: { groupWidth: '75%' },
            chartArea: { width: '50%' },
            hAxis: {
                title: 'Amount',
                minValue: 0
            },
            vAxis: {
                title: 'Category'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('googleChart'));
        chart.draw(data, options);
    }

    function printContent() {
    // Hide the "Actions" column for print
    var actionCells = document.getElementsByClassName('hide-on-print');
    for (var i = 0; i < actionCells.length; i++) {
        actionCells[i].style.display = 'none';
    }

    // Capture the table content
    var table = document.getElementById('myTable').outerHTML;

    // Define the header content with logo, title, and contact information
    var headerContent = `
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <img src="assets/img/bus.ico" alt="Logo" style="width: 100px; height: 100px; margin-right: 20px;">
                <div>
                    <h1 style="margin: 0; font-size: 24px;">Bantayan Staffs Report</h1>
                    <p style="margin: 0; font-size: 18px;">Bantayan Online Bus Reservation System</p>
                    <p style="margin: 0; font-size: 14px;">Rl Fitness & Sports Hub, Bantayan, Cebu</p>
                    <p style="margin: 0; font-size: 14px;">09153312395 / pastorillo.james25@gmail.com</p>
                </div>
            </div>
        </div>
    `;

    // Generate the printable HTML content
    var printWindowContent = `
        <html>
            <head>
                <title>Print Table</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; font-size: 14px; }
                    th { background-color: #f4f4f4; }
                </style>
            </head>
            <body>
                ${headerContent}
                ${table}
            </body>
        </html>
    `;

    // Open a new window and print
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write(printWindowContent);
    printWindow.document.close();
    printWindow.print();

    // Restore the "Actions" column visibility after printing
    for (var i = 0; i < actionCells.length; i++) {
        actionCells[i].style.display = '';
    }
}

</script>
<?php include('includes/scripts.php')?>

</body>
</html>

<?php
    // Close the database connection
    mysqli_close($conn);
?>
