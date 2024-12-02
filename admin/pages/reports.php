<?php
    // Database connection
    $conn = mysqli_connect('localhost', 'root', '', 'bobrs');
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
            width: 90%; /* Adjust width as needed */
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
                <th scope="col">Schedule Date</th>
                <th scope="col">Total Fare (Per Day)</th> <!-- Added Total Fare Column -->
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
                            <td>{$row["schedule_date"]}</td>
                            <td>{$row["total_fare_per_day"]}</td> <!-- Display Total Fare Per Day -->
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

    // JavaScript function to print chart and table
    function printContent() {
        // Hide the Action column for print
        var actionCells = document.getElementsByClassName('hide-on-print');
        for (var i = 0; i < actionCells.length; i++) {
            actionCells[i].style.display = 'none';
        }

        // Print the chart
        var chartContainer = document.getElementById('googleChart');
        var chartSVG = chartContainer.getElementsByTagName('svg')[0].outerHTML;
        
        // Print the table
        var table = document.getElementById('myTable').outerHTML;

        // Combine both for printing
        var combinedContent = '<html><head><title>Print Report</title>';
        combinedContent += '<style>';
        combinedContent += 'table { width: 1000px; border-collapse: collapse; margin-bottom: 20px; }';
        combinedContent += 'th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; font-size: 20px}';
        combinedContent += '@media print { body { margin: 1in; }}'; // Adding margin for print
        combinedContent += '</style>';
        combinedContent += '</head><body>' + chartSVG + table + '</body></html>';

        // Open a new window and print
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(combinedContent);
        printWindow.document.close();
        printWindow.print();

        // Restore Action column visibility after printing
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
