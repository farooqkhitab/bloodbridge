<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        .page-wrapper {
            display: flex;
            flex-direction: column;
            /* min-height: 100vh; */
        }
        .content {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
            background-color: #FFF5F5;
            color: #333;
            padding: 20px 0;
            text-align: center;
            font-family: Arial, sans-serif;
            margin-top: 20px;
        }
        .footer-wrapper {
            max-width: 1120px;
            margin: 0 auto;
            border-top: 2px solid #C12733;
        }
        .footer-container {
            max-width: 1120px;
            margin: 20px auto;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-section {
            flex: 1;
            min-width: 200px;
        }
        .footer-section h3 {
            color: #C12733;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .footer-section p, .footer-section a {
            color: #333;
            text-decoration: none;
            font-size: 0.9em;
            line-height: 1.5;
        }
        .footer-section a:hover {
            color: #C12733;
            text-decoration: underline;
        }
        .social-icons a {
            margin: 0 10px;
            color: #C12733;
            font-size: 1.5em;
        }
        .social-icons a:hover {
            color: #A22424;
        }
        .footer-bottom {
            margin-top: 20px;
            font-size: 0.8em;
            color: #666;
        }
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
            }
            .footer-section {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="content">
            <!-- Main content will go here, included by other pages -->
        </div>
        <footer>
            <div class="footer-wrapper">
                <div class="footer-container">
                    <div class="footer-section">
                        <h3>Contact Us</h3>
                        <p>Email: support@bloodbridge.com</p>
                        <p>Phone: +923039403651</p>
                        <p>Address: Lal Qilla, Lower Dir, Pakistan</p>
                    </div>
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <p><a href="index.php">Home</a></p>
                        <p><a href="blood-request.php">Blood Request</a></p>
                        <p><a href="quiz.php">Quiz</a></p>
                        <p><a href="about-us.php">About Us</a></p>
                    </div>
                    <div class="footer-section">
                        <h3>Follow Us</h3>
                        <div class="social-icons">
                            <a href="https://facebook.com/farooqkhitabkhan" target="_blank"><i class='bx bxl-facebook'></i></a>
                            <a href="https://twitter.com/farooqkhitab" target="_blank"><i class='bx bxl-twitter'></i></a>
                            <a href="https://instagram.com/farooqkhitab" target="_blank"><i class='bx bxl-instagram'></i></a>
                            <a href="https://wa.me/923039403652" target="_blank"><i class='bx bxl-whatsapp'></i></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>© 2025 BloodBridge. All rights reserved. | Designed by Farooq Khitab Khan, Shaheed Ullah, Anees Badshah</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>