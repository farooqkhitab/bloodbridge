<?php
require 'check_login.php'; // This ensures only logged-in users can see this page
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Blood Donor - BloodBridge</title>
    <!-- <link rel="stylesheet" href="css/blood-request.css?v=1.1"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/blood-request.css">

</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="request-wrapper">
        <div class="find_donor_container">
            <h2>Find Blood Donor</h2>
            <form class="find_donor_form" action="search-donors.php" method="POST">
                <div class="input_group">
                    <label for="district">District</label>
                    <select id="district" name="district" required>
                        <option value="">--Select District--</option>
                    </select>
                </div>

                <div class="input_group">
                    <label for="tehsil">Tehsil</label>
                    <select id="tehsil" name="tehsil" required>
                        <option value="">--Select Tehsil--</option>
                    </select>
                </div>

                <div class="input_group">
                    <label for="vc">VC | NC</label>
                    <select id="vc" name="vc" required>
                        <option value="">--Select VC--</option>
                    </select>
                </div>

                <div class="input_group">
                    <label for="blood_group">Blood Group</label>
                    <select name="blood_group" id="blood_group" required>
                        <option value="">--Select Blood Group--</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

            </form>
        </div>
        <div class="select-btns">
            <div>
                <button class="btn_style_2" id="select-all">Select All</button>
                <button class="btn_style_2" id="unselect-all">Unselect All</button>
            </div>

            <div id="selected-count" style="font-weight: bold; color: #A22424; margin: 10px 0;">
                Selected: 0 / 20
            </div>

            <div >
                <button class="btn_style_2" id="send-alerts">Send Alerts</button>
            </div>
        </div>
        <p id="sms-response" style="margin-top: 10px; color: green; font-weight: bold;"></p>


        <div id="donor_results"></div>






    </main>




    <script src="js/dropdown.js"></script>
    <script src="js/blood-request.js"></script>

</body>

</html>