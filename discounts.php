<?php 
    include('includes/layout-header.php'); // Include header file

    // Redirect from .php URLs to remove the extension
    $request = $_SERVER['REQUEST_URI'];
    if (substr($request, -4) == '.php') {
        $new_url = substr($request, 0, -4);
        header("Location: $new_url", true, 301);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Page</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .discount-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }

        .discount-box {
            width: 32%; /* Adjust width to fit 3 items in a row */
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .discount-box h3 {
            color: #0277bd;
        }

        .discount-box p {
            font-size: 18px;
            color: #555;
        }

        .discount-box .discount {
            font-size: 24px;
            font-weight: bold;
            color: #0288d1;
        }

        .discount-box img {
            width: 100px; /* Adjust the image size */
            margin: 10px 0;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 1024px) {
            .discount-box {
                width: 48%; /* On tablets, use 48% width for 2 items per row */
            }
        }

        @media (max-width: 768px) {
            .discount-info {
                flex-direction: column; /* Stack items vertically on smaller screens */
                align-items: center; /* Center the stacked items */
            }

            .discount-box {
                width: 90%; /* 90% width for each box in mobile view */
                margin-bottom: 20px; /* Space between each stacked box */
            }
        }

        @media (max-width: 480px) {
            .discount-box {
                width: 100%; /* Full width for each box in very small mobile screens */
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Discount Information</h1>

        <div class="discount-info">
            <!-- Student Discount Section -->
            <div class="discount-box">
                <img src="assets/img/mcc.jpg" alt="Student Discount Image">
                <h3>Student Discount</h3>
                <p>Eligible students get a discount of:</p>
                <p class="discount">15%</p>
            </div>

            <!-- PWD Discount Section -->
            <div class="discount-box">
                <img src="assets/img/pwd.jpg" alt="PWD Discount Image">
                <h3>PWD Discount</h3>
                <p>Persons with Disabilities are entitled to a discount of:</p>
                <p class="discount">20%</p>
            </div>

            <!-- Senior Citizen Discount Section -->
            <div class="discount-box">
                <img src="assets/img/senior.jpg" alt="Senior Citizen Discount Image">
                <h3>Senior Citizen Discount</h3>
                <p>Senior citizens enjoy a discount of:</p>
                <p class="discount">30%</p>
            </div>
        </div>
    </div>

</body>
</html>
