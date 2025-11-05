<?php
session_start();
require_once 'db_config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch the logged-in user's data with district, tehsil, and vc names
try {
    $stmt = $pdo->prepare("
        SELECT 
            donors.*,
            districts.name AS district_name,
            tehsils.name AS tehsil_name,
            vcs_ncs.name AS vc_name
        FROM donors
        LEFT JOIN districts ON donors.district = districts.id
        LEFT JOIN tehsils ON donors.tehsil = tehsils.id
        LEFT JOIN vcs_ncs ON donors.vc = vcs_ncs.id
        WHERE donors.id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Capitalize each word in the full_name
    $user['full_name'] = ucwords(strtolower($user['full_name']));
} catch (PDOException $e) {
    error_log($e->getMessage());
    $error_message = "An error occurred while fetching your profile. Please try again later.";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/popup_test.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Profile</title>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <main class="main">
        <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php else: ?>
        <section class="profile_container">
            <div class="profile_img_container">
                <img class="profile_img" src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'img.png'); ?>"
                    alt="Profile Picture">
            </div>
            <div class="details">
                <div class="icon">
                    <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <i class='bx bxs-edit-alt'></i>
                </div>
                <div class="icon">
                    <p>@<?php echo htmlspecialchars($user['username']); ?></p>
                    <i class='bx bxs-edit-alt'></i>
                </div>
                <div class="icon">
                    <?php echo htmlspecialchars($user['email']); ?>
                    <i class='bx bxs-edit-alt'></i>
                </div>
                <address class="icon">
                    <p>VC|NC: <b><?php echo htmlspecialchars($user['vc_name'] ?: 'N/A'); ?></b> Tehsil:
                        <b><?php echo htmlspecialchars($user['tehsil_name'] ?: 'N/A'); ?></b> District:
                        <b><?php echo htmlspecialchars($user['district_name'] ?: 'N/A'); ?></b>
                        <i class='bx bxs-edit-alt'></i>

                    </p>

                </address>

                <div class="icon">
                    <i style="color:#C12733;" class="bx bxs-donate-blood bx-flip-horizontal"></i>
                    <p><b class="donation"> 7 Donation</b></p> <!-- Replace with dynamic data if available -->
                </div>
                <div class="icon">
                    <i style="color:#14a4fe;" class='bx bxs-phone-call'></i>
                    <p class="contact"><?php echo htmlspecialchars($user['contact']); ?></p>
                    <i class='bx bxs-edit-alt'></i>
                </div>
                <div class="icon">
                    <i style="color:#13be5d;" class='bx bxl-whatsapp'></i>
                    <p class="whatsapp"><a href="https://wa.me/<?php echo htmlspecialchars($user['whatsapp']); ?>"
                            target="_blank"><?php echo htmlspecialchars($user['whatsapp']); ?></a></p>
                    <i class='bx bxs-edit-alt'></i>
                </div>
            </div>


        </section>

        <section class="button-container">
            <button id="save-life" class=" btn-dark"><i class='bx bx-heart'></i> Save Life</button>
            <!-- <button id="edit" class="btn-dark"><i class='bx bx-pencil'></i> Edit</button> -->
            <button id="ineligible" class="btn-dark"><i class='bx bx-block'></i> Ineligible</button>
            <button id="logout" class="btn-lighter"><i class='bx bx-log-out'></i> Logout</button>

        </section>





        <!-- Modal Saved Life (Initially Hidden) -->
        <div id="savedLifeModal" class="saved_life_container">
            <form>
                <div class="form-group">
                    <label for="username">Recipient Username:</label>
                    <input class="hover-focus" type="text" id="username" placeholder="farooqkhitab99">
                </div>

                <div class="form-group">
                    <label for="date">Date:</label>
                    <div class="date-input-group">
                        <input class="hover-focus" type="date" id="date">
                        <button type="button" class="today-btn btn-dark">Today</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="file">File or Document:</label>
                    <input class="hover-focus" type="file" id="file">
                </div>

                <div class="last_btn">
                    <button type="submit" class="btn-dark">Saved Life</button>
                    <button type="button" id="cancel-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Ineligible button  -->
        <div id="ineligible-container">
            <form id="ineligibilityForm">
                <div class="form-group">
                    <label for="reason">Reason for Ineligibility:</label>

                    <select class="hover-focus" id="reason" required>
                        <option value="" disabled selected>Select a reason</option>

                        <!-- Temporary Ineligibility (Sorted A-Z) -->
                        <option value="7">Antibiotic Treatment - after the last dose and full recovery</option>
                        <option value="28">Dengue/Chikungunya/Zika</option>
                        <option value="7">Dental Work - depending on procedure</option>
                        <option value="3">Flu, COVID, Hepatitis Vaccine</option>
                        <option value="14">Fever or Cold/Flu - after full recovery</option>
                        <option value="90">Malaria (Recovered)</option>
                        <option value="30">Minor Surgery</option>
                        <option value="180">Major Surgery</option>
                        <option value="180">Pregnancy</option>
                        <option value="180">Breastfeeding</option>
                        <option value="365">Recent Blood Transfusion</option>
                        <option value="365">Rabies Vaccine (After Bite)</option>
                        <option value="28">Live Vaccines (MMR, Chickenpox)</option>
                        <option value="90">COVID-19 (Severe) - after recovery</option>
                        <option value="14">COVID-19 (Mild/Moderate) - after symptoms clear</option>
                        <option value="365">Typhoid Fever</option>
                        <option value="180">Tattoo/Piercing</option>

                        <!-- Permanent Ineligibility (Sorted A-Z) -->
                        <option value="permanent">Blood Disorders (Hemophilia, Thalassemia)</option>
                        <option value="permanent">Cancer (except localized skin cancer)</option>
                        <option value="permanent">Chronic Kidney Disease</option>
                        <option value="permanent">Heart Disease</option>
                        <option value="permanent">Hepatitis B or C</option>
                        <option value="permanent">HIV/AIDS</option>
                        <option value="permanent">Neurological Disorders (Epilepsy, MS)</option>
                        <option value="permanent">Severe Diabetes with Complications</option>

                        <!-- Custom Option -->
                        <option value="other">Other (Enter manually)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="days">Ineligibility Period (Days):</label>
                    <input class="hover-focus" placeholder="Select a reason above" type="text" id="days" required
                        readonly disabled>
                </div>

                <div class="form-group">
                    <label for="evidence">Upload Evidence (Optional):</label>
                    <input class="hover-focus" type="file" id="evidence" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="last_btn">
                    <button class="btn-dark" type="submit">Submit</button>
                    <button class="btn-lighter" type="button" id="cancel-btn2">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Logout  -->
        <!-- Modal Saved Life (Initially Hidden) -->
        <div id="logout-container" class="saved_life_container">


            <div class="form-group">
                <h3>Are you sure to Logout?</h3>
            </div>

            <div class="last_btn">
                <a href="profile.php?action=logout" class="btn logout-btn"><button type="submit"
                        class="btn-dark">Logout</button>
                </a>
                <button type="button" id="cancel-btn3" class="btn-lighter">Cancel</button>

            </div>
        </div>







        <?php endif; ?>
    </main>

    <script src="js/profile_button.js"></script>

</body>

</html>