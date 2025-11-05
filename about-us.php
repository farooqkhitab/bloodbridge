<?php
require 'check_login.php'; // This ensures only logged-in users can see this page
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us | BloodBridge</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
    :root {
        --primary: #C12733;
        --light: #fceaea;
        --gray: #444;
        --card: #f9d2d2;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Nunito', sans-serif;
    }

    body {
        background-color: #fdf5f5;
        color: var(--gray);
    }

    .container {
        max-width: 1120px;
        margin: auto;
        padding: 30px 20px;
    }

    h1,
    h2 {
        color: var(--primary);
        text-align: center;
        margin-bottom: 20px;
    }

    p {
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .section {
        background-color: var(--light);
        border-radius: 10px;
        padding: 30px;
        margin-top: 30px;
    }

    .team {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    .member {
        flex: 1 1 280px;
        background-color: var(--card);
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .member img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 10px;
        border: 2px solid var(--primary);
    }

    .member h3 {
        margin-bottom: 5px;
        color: var(--primary);
    }

    .member p {
        font-size: 0.95rem;
    }

    .contact {
        text-align: center;
        margin-top: 40px;
        font-size: 1rem;
    }

    .contact p {
        margin-bottom: 8px;
    }

    footer {
        margin-top: 60px;
        text-align: center;
        font-size: 0.9rem;
        color: #777;
    }

    @media (max-width: 768px) {
        .team {
            flex-direction: column;
        }
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->
    <div class="container">
        <h1>About BloodBridge</h1>

        <div class="section">
            <h2>Who We Are</h2>
            <p>
                BloodBridge is a digital blood donation platform developed with one purpose:
                to connect verified and eligible donors with patients in urgent need. Our system ensures that only those
                who pass a health-based eligibility quiz can register. This helps build a reliable, committed community
                of blood donors who can be easily reached through district, tehsil, and VC-based searching.
            </p>
            <p>
                Members can access donors during emergencies and are also expected to contribute by staying available
                for others. We believe in one principle: <strong>“Help Others, Get Help.”</strong>
            </p>
        </div>

        <div class="section">
            <h2>Meet the Developers</h2>
            <div class="team">
                <div class="member">
                    <img src="img.png" alt="Farooq Khitab Khan" />
                    <h3>Farooq Khitab Khan</h3>
                    <p>Frontend Developer & UI Designer<br>Reg No: 2137583</p>
                </div>
                <div class="member">
                    <img src="img.png" alt="Shaheed Ullah" />
                    <h3>Shaheed Ullah</h3>
                    <p>Backend Developer & Database Designer<br>Reg No: 2137588</p>
                </div>
                <div class="member">
                    <img src="img.png" alt="Anees Badshah" />
                    <h3>Anees Badshah</h3>
                    <p>Integration & Logic Developer<br>Reg No: 2137587</p>
                </div>
            </div>
        </div>

        <div class="section contact">
            <h2>Contact Us</h2>
            <p><strong>Email:</strong> support@bloodbridge.pk</p>
            <p><strong>Phone:</strong> +92-XXXXXXXXXX</p>
            <p><strong>College:</strong> Govt. Degree College Lal Qila, Lower Dir</p>
            <p><strong>University:</strong> Affiliated with University of Malakand</p>
        </div>

        <footer>
            &copy; 2025 BloodBridge — A Final Year Project | All rights reserved.
        </footer>
    </div>

</body>

</html>