<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discounts</title>
    <?php 
        include('includes/layout-header.php');
    ?>
    <style>
        /* Container for the flip box effect */
        .card-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
            padding: 20px;
            background-image: linear-gradient(109.6deg, rgba(254, 253, 205, 1) 11.2%, rgba(163, 230, 255, 1) 91.1%);
        }

        .flip-box {
            width: 350px;
            height: 450px;
            perspective: 1000px;
            position: relative;
        }

        .flip-box-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-box:hover .flip-box-inner {
            transform: rotateY(180deg);
            box-shadow: 0 0 30px rgba(142, 14, 0, 1), 0 0 60px rgba(142, 14, 0, 0.8);
        }

        .flip-box-front,
        .flip-box-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: white;
            padding: 20px;
        }

        .flip-box-front {
            text-align: center; /* Center text horizontally */
        }

        .flip-box-front h2 {
            margin: 0; /* Remove default margins */
        }

        .flip-box-back {
            transform: rotateY(180deg);
            background: linear-gradient(to right, #1F1C18, #8E0E00);
            color: white; /* Adjust text color for visibility */
            display: flex; /* Add this line */
            justify-content: center; /* Add this line */
            align-items: center; /* Add this line */
            text-align: center; /* Center text horizontally */
            padding: 20px; /* Optional: adjust or remove if necessary */
        }

        .discount-summary {
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="card-container">
        <div class="flip-box">
            <div class="flip-box-inner">
                <div class="flip-box-front">
                    <img src="assets/img/mcc.jpg" alt="Discount 1" style="width: 100%; height: auto;">
                    <h2>Students - 20% Discount</h2>
                </div>
                <div class="flip-box-back">
                    <p>Enjoy a 20% discount year-round, including during summer breaks and legal holidays. To qualify, present a valid student ID or school registration card with your name, photo, and school details. Remember, no ID means no discount. Please note that students in medicine proper, law, graduate courses, and short-term programs are not eligible for this discount.</p>
                </div>
            </div>
        </div>

        <div class="flip-box">
            <div class="flip-box-inner">
                <div class="flip-box-front">
                    <img src="assets/img/senior.jpg" alt="Discount 2" style="width: 100%; height: auto;">
                    <h2>Senior Citizens - 20% Discount</h2>
                </div>
                <div class="flip-box-back">
                    <p>To avail of the 20% discount, please present your Senior Citizen ID, passport, or any other valid document that proves you are at least sixty (60) years old. This discount applies to both local and foreign senior citizens.</p>
                </div>
            </div>
        </div>

        <div class="flip-box">
            <div class="flip-box-inner">
                <div class="flip-box-front">
                    <img src="assets/img/pwd.jpg" alt="Discount 3" style="width: 100%; height: auto;">
                    <h2>PWD (Person with Disability) - 20% Discount</h2>
                </div>
                <div class="flip-box-back">
                    <p>To avail of the 20% discount, please present your PWD ID upon ticket purchase. Remember, no ID means no discount. This discount applies to both local and foreign PWDs. However, PWD ID is not required when the disability is apparent, such as when the passenger is an amputee.</p>
                </div>
            </div>
        </div>
    </main>

    <?php include('includes/layout-footer.php')?>
</body>
</html>
