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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .pulse-icon {
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>

    <title>Bantayan Online Bus Reservation</title>
</head>
<body>
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Total Bookings -->
<div class="col-6 col-sm-4 col-md-3 mb-3">
    <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF;background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);">
        <div class="d-flex align-items-center">
            <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="50px" viewBox="0 0 24 24" width="50px" fill="#000000">
                <rect fill="none" height="50" width="50" />
                <path d="M17,4H7V3h10V4z M17,21H7v-1h10V21z M17,1H7C5.9,1,5,1.9,5,3v18c0,1.1,0.9,2,2,2h10c1.1,0,2-0.9,2-2V3C19,1.9,18.1,1,17,1 L17,1z M7,6h10v12H7V6z M16,11V9.14C16,8.51,15.55,8,15,8H9C8.45,8,8,8.51,8,9.14l0,1.96c0.55,0,1,0.45,1,1c0,0.55-0.45,1-1,1 l0,1.76C8,15.49,8.45,16,9,16h6c0.55,0,1-0.51,1-1.14V13c-0.55,0-1-0.45-1-1C15,11.45,15.45,11,16,11z M12.5,14.5h-1v-1h1V14.5z M12.5,12.5h-1v-1h1V12.5z M12.5,10.5h-1v-1h1V10.5z" />
            </svg>
            <div class="text-center w-100">
                <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL BOOKINGS</b></p>
                <h1>
                    <?php
                    $q = mysqli_query($conn,"SELECT * from tblbook");
                    $num_rows = mysqli_num_rows($q);
                    echo $num_rows;
                    ?>
                </h1>
            </div>
        </div>
    </div>
</div>

<!-- Total Passengers -->
<div class="col-6 col-sm-4 col-md-3 mb-3">
        <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF; background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);">
            <div class="d-flex align-items-center">
                <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#000000">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                </svg>
                <div class="text-center w-100">
                    <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL PASSENGERS</b></p>
                    <h1>
                        <?php
                        $q = mysqli_query($conn,"SELECT * from tblpassenger");
                        $num_rows = mysqli_num_rows($q);
                        echo $num_rows;
                        ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>

<!-- Total Schedules -->
<div class="col-6 col-sm-4 col-md-3 mb-3">
    <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF;background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);">
        <div class="d-flex align-items-center">
            <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#000000">
                <path d="M0 0h24v24H0V0z" fill="none" />
                <path d="M7 11h2v2H7v-2zm14-5v14c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2l.01-14c0-1.1.88-2 1.99-2h1V2h2v2h8V2h2v2h1c1.1 0 2 .9 2 2zM5 8h14V6H5v2zm14 12V10H5v10h14zm-4-7h2v-2h-2v2zm-4 0h2v-2h-2v2z" />
            </svg>
            <div class="text-center w-100">
                <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL SCHEDULES</b></p>
                <h1>
                    <?php
                    $q = mysqli_query($conn,"SELECT * from tblschedule");
                    $num_rows = mysqli_num_rows($q);
                    echo $num_rows;
                    ?>
                </h1>
            </div>
        </div>
    </div>
</div>

<!-- Total Routes -->
<div class="col-6 col-sm-4 col-md-3 mb-3">
    <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF;
background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);
">
        <div class="d-flex align-items-center">
            <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#000000">
                <path d="M0 0h24v24H0V0z" fill="none" />
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z" />
                <circle cx="12" cy="9" r="2.5" />
            </svg>
            <div class="text-center w-100">
                <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL ROUTES</b></p>
                <h1>
                    <?php
                    $q = mysqli_query($conn,"SELECT * from tblroute");
                    $num_rows = mysqli_num_rows($q);
                    echo $num_rows;
                    ?>
                </h1>
            </div>
        </div>
    </div>
</div>

<!-- Total Locations -->
<div class="col-6 col-sm-4 col-md-3 mb-3">
    <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF;
background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);
">
        <div class="d-flex align-items-center">
            <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#000000">
                <path d="M0 0h24v24H0V0z" fill="none" />
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z" />
                <circle cx="12" cy="9" r="2.5" />
            </svg>
            <div class="text-center w-100">
                <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL LOCATIONS</b></p>
                <h1>
                    <?php
                    $q = mysqli_query($conn,"SELECT * from tbllocation");
                    $num_rows = mysqli_num_rows($q);
                    echo $num_rows;
                    ?>
                </h1>
            </div>
        </div>
    </div>
</div>

    <!-- Total Bus -->
    <div class="col-6 col-sm-4 col-md-3 mb-3">
        <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF;
background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);
">
            <div class="d-flex align-items-center">
                <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="50px" viewBox="0 0 20 20" width="50px" fill="#000000">
                    <g>
                        <rect fill="none" height="20" width="20" x="0" />
                    </g>
                    <g>
                        <g />
                        <g>
                            <polygon points="8,5 11,5 11,8 12,8 12,4 7,4 7,7 4,7 4,16 5,16 5,8 8,8" />
                            <rect height="1" width="1" x="6" y="9" />
                            <rect height="1" width="1" x="9" y="6" />
                            <rect height="1" width="1" x="6" y="11" />
                            <rect height="1" width="1" x="6" y="13" />
                            <path d="M15.11,9.34C15.05,9.14,14.85,9,14.64,9H9.36C9.15,9,8.95,9.14,8.89,9.34L8,12v2v0.5V15v0.5C8,15.78,8.22,16,8.5,16 S9,15.78,9,15.5V15h6v0.5c0,0.28,0.22,0.5,0.5,0.5s0.5-0.22,0.5-0.5V15v-0.5V14v-2L15.11,9.34z M9.72,10h4.56l0.67,2H9.05L9.72,10z M9.5,14C9.22,14,9,13.78,9,13.5S9.22,13,9.5,13s0.5,0.22,0.5,0.5S9.78,14,9.5,14z M14.5,14c-0.28,0-0.5-0.22-0.5-0.5s0.22-0.5,0.5-0.5s0.5,0.22,0.5,0.5S14.78,14,14.5,14z" />
                        </g>
                    </g>
                </svg>
                <div class="text-center w-100">
                    <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL BUS</b></p>
                    <h1>
                        <?php
                        $q = mysqli_query($conn,"SELECT * from tblbus");
                        $num_rows = mysqli_num_rows($q);
                        echo $num_rows;
                        ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Staff (Drivers + Conductors) -->
<div class="col-6 col-sm-4 col-md-3 mb-3">
    <div class="bg-white shadow border-top p-3 border-primary rounded h-100" style="background-color: #FFFFFF;
background-image: linear-gradient(180deg, #FFFFFF 0%, #6284FF 50%, #FF0000 100%);
">
        <div class="d-flex align-items-center">
            <svg class="pulse-icon" xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#000000">
                <path d="M0 0h24v24H0V0z" fill="none" />
                <path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
            </svg>
            <div class="text-center w-100">
                <p class="mb-0" style="font-family: 'Times New Roman', serif;"><b>TOTAL STAFF</b></p>
                <h1>
                    <?php
                    // Query to get total drivers
                    $drivers_query = mysqli_query($conn,"SELECT * from tbldriver");
                    $total_drivers = mysqli_num_rows($drivers_query);
                    
                    // Query to get total conductors
                    $conductors_query = mysqli_query($conn,"SELECT * from tblconductor");
                    $total_conductors = mysqli_num_rows($conductors_query);
                    
                    // Calculate total staff
                    $total_staff = $total_drivers + $total_conductors;
                    echo $total_staff;
                    ?>
                </h1>
            </div>
        </div>
    </div>
</div>

</div>



<div class="row mt-4" style="background-color: #4158D0;
background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
 width: 1200px; margin-left: 2px; height: 600px">
    <div class="col-md-6">
        <canvas id="passengerChart"></canvas>
        <script>
            var ctx = document.getElementById('passengerChart').getContext('2d');

            // Fetch passenger type data from PHP
            var passengerTypes = <?php
                $types = ['Regular', 'Student', 'PWD', 'Senior'];
                $counts = [];

                foreach ($types as $type) {
                    $query = "SELECT COUNT(*) AS count FROM tblbook WHERE passenger_type = '$type'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    $counts[$type] = $row['count'];
                }

                echo json_encode(array_values($counts)); // Output counts as JSON
            ?>;

            // Prepare labels for the pie chart
            var labels = <?php echo json_encode($types); ?>;

            // Create a new pie chart
            var passengerChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Bookings by Passenger Type',
                        data: passengerTypes,
                        backgroundColor: [
                            'rgba(243, 167, 214, 1)',
                            'rgba(255, 166, 185, 1)',
                            'rgba(255, 204, 102, 1)',
                            'rgba(251, 255, 153, 1)'
                        ],
                        borderColor: [
                            'black',
                            'black',
                            'black',
                            'black'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                        color: 'white' // Change this color to whatever you prefer
                    }
                        },
                        title: {
                            display: true,
                            text: 'Booking Distribution by Passenger Type',
                            color: 'black'
                        }
                    }
                }
            });
        </script>
    </div>
    <div class="col-md-6">
        <canvas id="staffChart"></canvas>
        <script>
            var ctx = document.getElementById('staffChart').getContext('2d');

            // Fetch staff data from PHP
            var staffCounts = <?php
                $driverQuery = "SELECT COUNT(*) AS count FROM tbldriver";
                $driverResult = mysqli_query($conn, $driverQuery);
                $driverRow = mysqli_fetch_assoc($driverResult);
                $driverCount = $driverRow['count'];

                $conductorQuery = "SELECT COUNT(*) AS count FROM tblconductor";
                $conductorResult = mysqli_query($conn, $conductorQuery);
                $conductorRow = mysqli_fetch_assoc($conductorResult);
                $conductorCount = $conductorRow['count'];

                echo json_encode([$driverCount, $conductorCount]); // Output counts as JSON
            ?>;

            // Prepare labels for the staff pie chart
            var staffLabels = ['Drivers', 'Conductors'];

            // Create a new pie chart
            var staffChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: staffLabels, // Only staff labels
                    datasets: [{
                        label: 'Total Staff',
                        data: staffCounts, // Only staff data
                        backgroundColor: [
                            'rgba(221, 180, 245, 1)', // Drivers
                            'rgba(179, 255, 210, 1)'   // Conductors
                        ],
                        borderColor: [
                            'black', // Drivers
                            'black'   // Conductors
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                        color: 'black' // Change this color to whatever you prefer
                    }
                        },
                        title: {
                            display: true,
                            text: 'Total Staff',
                            color: 'black'
                        }
                    }
                }
            });
        </script>
    </div>
</div>

<!-- The booking chart -->
<div class="row mt-4" style="background-image: linear-gradient( 90.5deg,  rgba(152,45,255,1) 0.7%, rgba(90,241,255,1) 51.5%, rgba(65,239,164,1) 100.6% );; width: 1200px; margin-left: 2px">
    <div class="col-12">
        <canvas id="bookingChart"></canvas>
    </div>
</div>

<script>
    var ctx = document.getElementById('bookingChart').getContext('2d');

    <?php
    // Fetch the total number of bookings per month
    $query = "
        SELECT 
            MONTH(book_date) as month_number, 
            MONTHNAME(book_date) as month, 
            COUNT(*) as total 
        FROM tblbook
        GROUP BY MONTH(book_date) 
        ORDER BY MONTH(book_date)";

    $result = mysqli_query($conn, $query);

    // Initialize an array with all 12 months
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June', 
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    // Initialize an array with 0 totals for each month
    $totals = array_fill(0, 12, 0);

    // Populate the totals array with data from the database
    while ($row = mysqli_fetch_assoc($result)) {
        // Subtract 1 from month_number to match array index (0-11)
        if ($row['month_number'] >= 1 && $row['month_number'] <= 12) {
            $totals[$row['month_number'] - 1] = max(0, (int)$row['total']); // Ensure total is not negative
        }
    }
    ?>

var bookingChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($months); ?>,  // X-axis labels (all 12 months)
        datasets: [{
            label: 'Total Bookings',
            data: <?php echo json_encode($totals); ?>,  // Y-axis data corresponding to each month
            backgroundColor: [
                            '#f43b47', // Drivers
                        ],
                        borderColor: [
                            'black', // Drivers
                        ],
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
                },
                title: {
                display: true,
                text: 'Total Bookings / Months',
                color: 'black',
                font: {
                    size: 20 // Change font size as needed
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: 'black' // Set the color of the x-axis labels (months) to white
                },
                grid: {
                    color: 'gray' // Set the color of the x-axis grid lines to white
                }
            },
            y: {
                ticks: {
                    color: 'white' // Set the color of the y-axis labels to white (optional)
                },
                grid: {
                    color: 'gray' // Set the color of the y-axis grid lines to white (optional)
                },
                beginAtZero: true
            }
        }
    }
});

</script>

<div class="row mt-4" style="background-image: linear-gradient( 73.2deg,  rgba(248,205,205,1) 23.2%, rgba(149,170,211,1) 77% ); width: 1200px; margin-left: 2px">
    <div class="col-12 mt-4">
        <!-- Bus Type Horizontal Bar Chart -->
        <canvas id="busTypeChart"></canvas>
    </div>
</div>

<!-- Chart.js script -->
<script>
    var ctxBusType = document.getElementById('busTypeChart').getContext('2d');

    // Fetch bus type data from PHP for the Bar Chart
    var busTypeCounts = <?php
        // Query to count buses by type (Regular and Air-Conditioned)
        $busTypes = ['Regular', 'Air conditioned'];
        $busCounts = [];

        foreach ($busTypes as $busType) {
            $query = "SELECT COUNT(*) AS count FROM tblbus WHERE bus_type = '$busType'";
            $result = mysqli_query($conn, $query);
            if ($result) { // Check if the query executed successfully
                $row = mysqli_fetch_assoc($result);
                $busCounts[$busType] = $row['count'];
            } else {
                $busCounts[$busType] = 0; // Set count to 0 if query fails
            }
        }

        echo json_encode(array_values($busCounts)); // Output counts as JSON
    ?>;

    var busLabels = <?php echo json_encode($busTypes); ?>;

    // Log bus labels and counts to the console for debugging
    console.log('Bus Labels:', busLabels);
    console.log('Bus Counts:', busTypeCounts);

    // Create a new horizontal bar chart for bus types
    var busTypeChart = new Chart(ctxBusType, {
        type: 'bar',
        data: {
            labels: busLabels,
            datasets: [{
                label: 'Buses',
                data: busTypeCounts,
                backgroundColor: [
                    '#0d4d77',
                    '#e8d425'
                ],
                borderColor: [
                    'black',
                    'black'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // This makes the bar chart horizontal
            scales: {
                x: {
                    ticks:{
                        color: 'black'
                    },
                    beginAtZero: true
                },
                y: {
                    ticks:{
                        color: 'black'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels:{
                        color: 'black'
                    }
                },
                title: {
                    display: true,
                    text: 'Type of Buses',
                    color: 'black'
                }
            }
        }
    });
</script>


<?php include('includes/scripts.php')?>

</body>
</html>
