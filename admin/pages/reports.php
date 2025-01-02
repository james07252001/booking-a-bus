<?php
// Database connection
$conn = mysqli_connect('localhost', 'u510162695_bobrs', '1Bobrs_password', 'u510162695_bobrs');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch driver, conductor, bus, route, schedule, and fare data based on schedule_id
$query = "
    SELECT 
        d.id AS driver_id, 
        d.name AS driver_name, 
        c.name AS conductor_name, 
        b1.bus_num AS bus_name, 
        b1.bus_code, 
        rl.location_name AS route_from, 
        rl2.location_name AS route_to, 
        s.schedule_date, 
        IFNULL(SUM(bk.total), 0) AS total_fare_per_day
    FROM tbldriver d
    LEFT JOIN tblconductor c ON d.id = c.driver_id
    LEFT JOIN tblbus b1 ON d.id = b1.driver_id
    LEFT JOIN tblroute r ON r.driver_id = d.id
    LEFT JOIN tbllocation rl ON r.route_from = rl.id
    LEFT JOIN tbllocation rl2 ON r.route_to = rl2.id
    LEFT JOIN tblschedule s ON s.driver_id = d.id
    LEFT JOIN tblbook bk ON bk.schedule_id = s.id
    GROUP BY d.id, d.name, c.name, b1.bus_num, b1.bus_code, rl.location_name, rl2.location_name, s.schedule_date
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/styles.css" />
    <title>Bantayan Online Bus Reservation</title>
    <style>
        #myTable { width: 100%; margin: auto; font-size: 14px; }
        .hide-on-print { display: none; }
        @media print {
            body { margin: 1in; }
            .breadcrumb, .btn { display: none; }
        }
        .table-responsive { overflow-x: auto; }
    </style>
</head>
<body>
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><b>Reports</b></li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="text-right mb-3">
            <button class="btn btn-primary" onclick="printContent()">
                <i class="fa fa-print"></i> Print Report
            </button>
        </div>

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
                        echo "<tr>
                                <td>{$i}</td>
                                <td>{$row["driver_name"]}</td>
                                <td>{$row["conductor_name"]}</td>
                                <td>{$row["bus_name"]}</td>
                                <td>{$row["bus_code"]}</td>
                                <td>{$row["route_from"]}</td>
                                <td>{$row["route_to"]}</td>
                                <td>{$row["total_fare_per_day"]}</td>
                                <td>{$row["schedule_date"]}</td>
                                <td class='hide-on-print'>
                                    <button class='btn btn-sm btn-secondary'>Print</button>
                                </td>
                            </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function printContent() {
        var actionCells = document.getElementsByClassName('hide-on-print');
        for (var i = 0; i < actionCells.length; i++) {
            actionCells[i].style.display = 'none';
        }

        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Table</title></head><body>');
        printWindow.document.write(document.getElementById('myTable').outerHTML);
        printWindow.document.write('</body></html>');
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
mysqli_close($conn);
?>
