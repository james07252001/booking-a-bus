<?php
    // Database connection
    $conn = mysqli_connect('localhost', 'u510162695_bobrs', '1Bobrs_password', 'u510162695_bobrs');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Modified query to correctly join tables and show all data
    $query = "
        SELECT 
            s.id as schedule_id,
            d.id as driver_id, 
            d.name as driver_name, 
            c.name as conductor_name,
            b.bus_num,
            b.bus_code,
            rl1.location_name as route_from, 
            rl2.location_name as route_to,
            s.schedule_date,
            IFNULL(SUM(bk.total), 0) as total_fare_per_day
        FROM tblschedule s
        LEFT JOIN tbldriver d ON s.driver_id = d.id
        LEFT JOIN tblconductor c ON s.conductor_id = c.id
        LEFT JOIN tblbus b ON s.bus_id = b.id
        LEFT JOIN tblroute r ON s.route_id = r.id
        LEFT JOIN tbllocation rl1 ON r.route_from = rl1.id
        LEFT JOIN tbllocation rl2 ON r.route_to = rl2.id
        LEFT JOIN tblbook bk ON s.id = bk.schedule_id
        GROUP BY 
            s.id,
            d.id,
            d.name,
            c.name,
            b.bus_num,
            b.bus_code,
            rl1.location_name,
            rl2.location_name,
            s.schedule_date
        ORDER BY s.schedule_date DESC";
        
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
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/styles.css" />
    
    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <title>Bantayan Online Bus Reservation</title>

    <style>
        #myTable {
            width: 100%;
            margin: auto;
            font-size: 14px;
        }

        .hide-on-print {
            display: none !important;
        }

        @media print {
            body {
                margin: 1in 2in 1in 1in;
            }
            .no-print {
                display: none !important;
            }
        }

        .table-responsive {
            overflow-x: auto;
        }

        #googleChart {
            width: 100% !important;
            height: 400px;
        }

        @media (max-width: 576px) {
            #myTable {
                font-size: 12px;
            }
            .breadcrumb {
                font-size: 14px;
            }
            .btn {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div>
    <nav aria-label="breadcrumb" class="no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>Reports</b></li>
        </ol>
    </nav>

    <!-- Google Chart -->
    <div class="row mt-4 no-print" style="background-color: #D9AFD9; background-image: linear-gradient(0deg, #D9AFD9 0%, #97D9E1 100%);">
        <div class="col-12">
            <div id="googleChart"></div>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="text-right mb-3 no-print">
        <button class="btn btn-primary" onclick="printContent()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

    <div class="table-responsive">
        <table id="myTable" class="table table-striped" style="background-color: #D9AFD9; background-image: linear-gradient(0deg, #D9AFD9 0%, #97D9E1 100%);">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Driver's Name</th>
                    <th scope="col">Conductor's Name</th>
                    <th scope="col">Bus Number</th>
                    <th scope="col">Bus Code</th>
                    <th scope="col">From</th>
                    <th scope="col">To</th>
                    <th scope="col">Total Fare</th>
                    <th scope="col">Schedule Date</th>
                    <th scope="col" class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr id='row-{$row["schedule_id"]}'>
                                <th scope='row'>{$i}</th>
                                <td>" . htmlspecialchars($row["driver_name"]) . "</td>
                                <td>" . htmlspecialchars($row["conductor_name"]) . "</td>
                                <td>" . htmlspecialchars($row["bus_num"]) . "</td>
                                <td>" . htmlspecialchars($row["bus_code"]) . "</td>
                                <td>" . htmlspecialchars($row["route_from"]) . "</td>
                                <td>" . htmlspecialchars($row["route_to"]) . "</td>
                                <td>₱" . number_format($row["total_fare_per_day"], 2) . "</td>
                                <td>" . date('M d, Y', strtotime($row["schedule_date"])) . "</td>
                                <td class='no-print'>
                                    <button class='btn btn-sm btn-secondary' onclick='printRow(\"row-{$row["schedule_id"]}\")'>
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
            // Reset the result pointer
            mysqli_data_seek($result, 0);
            
            // Calculate total fare and count
            $totalFare = 0;
            $bookingCount = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $totalFare += $row['total_fare_per_day'];
                if ($row['total_fare_per_day'] > 0) {
                    $bookingCount++;
                }
            }
        ?>

        var data = google.visualization.arrayToDataTable([
            ['Category', 'Amount', { role: 'style' }],
            ['Bookings', <?php echo $bookingCount; ?>, '#FA8072'],
            ['Total Fare (₱)', <?php echo $totalFare; ?>, '#6CB4EE']
        ]);

        var options = {
            title: 'Booking and Revenue Summary',
            legend: { position: 'none' },
            colors: ['#FA8072', '#6CB4EE'],
            bar: { groupWidth: '75%' },
            chartArea: { width: '70%', height: '70%' },
            hAxis: {
                title: 'Amount',
                minValue: 0,
                format: '#,###'
            },
            vAxis: {
                title: 'Category'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('googleChart'));
        chart.draw(data, options);
    }

    function printContent() {
        window.print();
    }

    function printRow(rowId) {
        var row = document.getElementById(rowId);
        var printWindow = window.open('', '', 'height=600,width=800');
        
        var headerContent = `
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <img src="../assets/images/bobrs.png" alt="Logo" style="width: 100px; height: 100px; margin-right: 20px;">
                    <div>
                        <h1 style="margin: 0; font-size: 24px;">Bantayan Staff Report</h1>
                        <p style="margin: 0; font-size: 18px;">Bantayan Online Bus Reservation System</p>
                        <p style="margin: 0; font-size: 14px;">Rl Fitness & Sports Hub, Bantayan, Cebu</p>
                        <p style="margin: 0; font-size: 14px;">09153312395 / pastorillo.james25@gmail.com</p>
                    </div>
                </div>
            </div>
        `;

        var table = document.getElementById('myTable').cloneNode(true);
        var rows = table.getElementsByTagName('tr');
        var targetRow;
        
        // Find and keep only the header and the selected row
        for (var i = rows.length - 1; i >= 0; i--) {
            if (rows[i].id !== rowId && i !== 0) {
                table.deleteRow(i);
            }
        }

        var printContent = `
            <html>
                <head>
                    <title>Print Record</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
                        th { background-color: #f4f4f4; }
                        .no-print { display: none; }
                    </style>
                </head>
                <body>
                    ${headerContent}
                    ${table.outerHTML}
                </body>
            </html>
        `;

        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>

<?php include('includes/scripts.php')?>
</body>
</html>

<?php mysqli_close($conn); ?>