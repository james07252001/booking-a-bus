<?php
    // Database connection
    $conn = mysqli_connect('localhost', 'u510162695_bobrs', '1Bobrs_password', 'u510162695_bobrs');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to fetch driver, conductor, bus, route, schedule, and fare data
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

    // Redirect if the request URI ends with '.php'
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
        /* Table container for scrolling */
        .table-responsive {
            max-height: 500px; /* Adjust to desired height */
            overflow-y: auto;
        }

        .hide-on-print {
            display: none;
        }

        /* Print styles */
        @media print {
            body {
                margin: 1in;
            }
            .table-responsive {
                overflow: visible;
            }
            .breadcrumb, .btn {
                display: none;
            }
        }

        /* Smaller font for mobile devices */
        @media (max-width: 576px) {
            #myTable {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><b>Reports</b></li>
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
    <!-- Print button -->
    <div class="text-right mb-3">
        <button class="btn btn-primary" onclick="printContent()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table id="myTable" class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Driver Name</th>
                    <th>Conductor Name</th>
                    <th>Bus Name</th>
                    <th>Bus Code</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Total Fare (Per Day)</th>
                    <th>Schedule Date</th>
                    <th class="hide-on-print">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr id='{$row["driver_id"]}'>
                                <td>{$i}</td>
                                <td>{$row["driver_name"]}</td>
                                <td>{$row["conductor_name"]}</td>
                                <td>{$row["bus_num"]}</td>
                                <td>{$row["bus_code"]}</td>
                                <td>{$row["route_from"]}</td>
                                <td>{$row["route_to"]}</td>
                                <td>{$row["total_fare_per_day"]}</td>
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

<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        <?php
            $query = "SELECT SUM(total) AS total_fare FROM tblbook";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $totalFare = $row['total_fare'];

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
            hAxis: { title: 'Amount', minValue: 0 },
            vAxis: { title: 'Category' }
        };

        var chart = new google.visualization.BarChart(document.getElementById('googleChart'));
        chart.draw(data, options);
    }

    function printContent() {
        var actionCells = document.getElementsByClassName('hide-on-print');
        for (var i = 0; i < actionCells.length; i++) {
            actionCells[i].style.display = 'none';
        }
        var table = document.getElementById('myTable').outerHTML;
        var headerContent = `
            <div style="text-align: center;">
                <h1>Bantayan Staffs Report</h1>
                <p>Bantayan Online Bus Reservation System</p>
            </div>`;
        var printWindowContent = `<html><body>${headerContent}${table}</body></html>`;
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(printWindowContent);
        printWindow.document.close();
        printWindow.print();
        for (var i = 0; i < actionCells.length; i++) {
            actionCells[i].style.display = '';
        }
    }
</script>
<?php include('includes/scripts.php') ?>
</body>
</html>

<?php
    // Close the database connection
    mysqli_close($conn);
?>
