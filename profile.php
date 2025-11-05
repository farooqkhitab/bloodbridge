<?php

session_start();
// edit mood enable component
// if (isset($_SESSION['edit_mode_enabled']) && time() > $_SESSION['edit_mode_expires']) {
//     unset($_SESSION['edit_mode_enabled'], $_SESSION['edit_mode_expires']);
// }
require_once 'db_config.php'; // Include your database connection file
include_once 'refresh_profile_pic_once.php';



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

// Handle deactivation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deactivate_account'])) {
    $passwordInput = $_POST['password'] ?? '';

    // Check if password is entered
    if (empty($passwordInput)) {
        header("Location: profile.php?deactivate_error=Please enter your password");
        exit();
    }
    

    // Get stored password hash
    $stmt = $pdo->prepare("SELECT password_hash FROM donors WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !password_verify($passwordInput, $row['password_hash'])) {
        header("Location: profile.php?deactivate_error=Incorrect password");
        exit();
    }

    // Delete the user (or set status = 0 for soft delete)
    $stmt = $pdo->prepare("DELETE FROM donors WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    // End session
    session_destroy();
    header("Location: login.php?message=Account deactivated");
    exit();
}
// profile change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_img'])) {
    $uploadDir = 'profile_pictures/';
    $userId = $_SESSION['user_id'];

    if (isset($_FILES['profile_img_file']) && $_FILES['profile_img_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['profile_img_file']['tmp_name'];
        $fileName = basename($_FILES['profile_img_file']['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $newFileName = time() . "_" . uniqid() . "." . $ext;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $targetPath)) {
                $stmt = $pdo->prepare("SELECT profile_picture FROM donors WHERE id = ?");
                $stmt->execute([$userId]);
                $oldPic = $stmt->fetchColumn();



                $stmt = $pdo->prepare("UPDATE donors SET profile_picture = ? WHERE id = ?");
                $stmt->execute([$targetPath, $userId]);

                // ✅ Add this line to tell other pages to refresh session once
                $_SESSION['refresh_profile_pic'] = true;

                if ($oldPic && $oldPic !== 'img.png' && file_exists($oldPic)) {
                    unlink($oldPic);
                }

                header("Location: profile.php");
                exit();
            }
            
        } else {
            echo "<script>alert('Only JPG, JPEG, PNG allowed');</script>";
        }
    }
}
// Handle ineligibility form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reason']) && isset($_POST['days'])) {
    $donor_id = $_SESSION['user_id'];
    $reason = $_POST['reason'];
    $days = $_POST['days'];

    try {
        $pdo->beginTransaction();

        // Calculate new until date
        $current_date = new DateTime('2025-06-15 04:05 PM PKT'); // Current date and time
        $new_until_date = clone $current_date;
        $new_until_date->modify("+$days days");
        $new_until_date_str = $new_until_date->format('Y-m-d');

        // Get current until date
        $stmt = $pdo->prepare("SELECT until FROM donors WHERE id = ?");
        $stmt->execute([$donor_id]);
        $current_until = $stmt->fetchColumn();

        if ($current_until) {
            $current_until_date = new DateTime($current_until);
            if ($new_until_date > $current_until_date) {
                $stmt = $pdo->prepare("UPDATE donors SET until = ? WHERE id = ?");
                $stmt->execute([$new_until_date_str, $donor_id]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE donors SET until = ? WHERE id = ?");
            $stmt->execute([$new_until_date_str, $donor_id]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Ineligibility updated successfully']);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
}
// Change full name 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_full_name'])) {
    $newName = trim($_POST['new_name']);
    $userId = $_SESSION['user_id'];

    if (!empty($newName)) {
        $stmt = $pdo->prepare("UPDATE donors SET full_name = ? WHERE id = ?");
        $stmt->execute([$newName, $userId]);

        // Refresh session data if needed
        $_SESSION['refresh_profile_name'] = true;

        // Optional: redirect back to profile
        header("Location: profile.php");
        exit();
    }
}

// change username
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_username'])) {
    $newUsername = trim($_POST['new_username']);
    $userId = $_SESSION['user_id'];

    if (!empty($newUsername)) {
        // Check if username is taken by someone else
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM donors WHERE username = ? AND id != ?");
        $stmt->execute([$newUsername, $userId]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            header("Location: profile.php?username_error=Username already taken");
            exit();
        }

        // Update if valid and available
        $stmt = $pdo->prepare("UPDATE donors SET username = ? WHERE id = ?");
        $stmt->execute([$newUsername, $userId]);

        header("Location: profile.php?message=Username updated");
        exit();
    }
}

// Change email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email'])) {
    $newEmail = trim($_POST['new_email']);
    $userId = $_SESSION['user_id'];

    if (!empty($newEmail) && filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        // Check availability
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM donors WHERE email = ? AND id != ?");
        $stmt->execute([$newEmail, $userId]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            header("Location: profile.php?email_error=Email already in use");
            exit();
        }

        // Update email
        $stmt = $pdo->prepare("UPDATE donors SET email = ? WHERE id = ?");
        $stmt->execute([$newEmail, $userId]);

        // Optionally update session if needed
        $_SESSION['refresh_email'] = true;

        header("Location: profile.php?email_success=Email updated");
        exit();
    }
}

// Address change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $userId = $_SESSION['user_id'];
    $district = $_POST['district'];
    $tehsil = $_POST['tehsil'];
    $vc = $_POST['vc'];

    if (!empty($district) && !empty($tehsil) && !empty($vc)) {
        $stmt = $pdo->prepare("UPDATE donors SET district = ?, tehsil = ?, vc = ? WHERE id = ?");
        $stmt->execute([$district, $tehsil, $vc, $userId]);

        header("Location: profile.php?address_success=Address updated");
        exit();
    } else {
        header("Location: profile.php?address_error=Please select all address fields.");
        exit();
    }
}

// contact change / phone change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contact'])) {
    $userId = $_SESSION['user_id'];
    $newContact = trim($_POST['new_contact']);

    if (!preg_match('/^03\d{9}$/', $newContact)) {
        header("Location: profile.php?contact_error=Invalid contact number format.");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE donors SET contact = ? WHERE id = ?");
    $stmt->execute([$newContact, $userId]);

    $_SESSION['refresh_profile_pic'] = true; // Optional to reload if needed
    header("Location: profile.php?contact_success=Contact number updated.");
    exit();
}

// change whatsapp number
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_whatsapp'])) {
    $userId = $_SESSION['user_id'];
    $newWhatsapp = trim($_POST['new_whatsapp']);

    if (!preg_match('/^03\d{9}$/', $newWhatsapp)) {
        header("Location: profile.php?wa_error=Invalid WhatsApp number format.");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE donors SET whatsapp = ? WHERE id = ?");
    $stmt->execute([$newWhatsapp, $userId]);

    header("Location: profile.php?wa_success=WhatsApp number updated.");
    exit();
}

// change blood group
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_blood_group'])) {
    $userId = $_SESSION['user_id'];
    $newBloodGroup = $_POST['new_blood_group'];

    $allowedGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    if (!in_array($newBloodGroup, $allowedGroups)) {
        header("Location: profile.php?blood_error=Invalid blood group selected.");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE donors SET blood_group = ? WHERE id = ?");
    $stmt->execute([$newBloodGroup, $userId]);

    header("Location: profile.php?blood_success=Blood group updated.");
    exit();
}
// edit moode enable
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enable_edit_mode'])) {
//     $passwordInput = $_POST['edit_password'];
//     $userId = $_SESSION['user_id'];

//     $stmt = $pdo->prepare("SELECT password_hash FROM donors WHERE id = ?");
//     $stmt->execute([$userId]);
//     $row = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$row || !password_verify($passwordInput, $row['password_hash'])) {
//         header("Location: profile.php?edit_error=Incorrect password.");
//         exit();
//     }

//     $_SESSION['edit_mode_enabled'] = true;
//     $_SESSION['edit_mode_expires'] = time() + 60; // 1 minute

//     header("Location: profile.php");
//     exit();
// }





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

     // Fetch total donations from donation_summary
     $stmt = $pdo->prepare("SELECT total_donations FROM donation_summary WHERE donor_id = ?");
     $stmt->execute([$_SESSION['user_id']]);
     $donation_data = $stmt->fetch(PDO::FETCH_ASSOC);
     $total_donations = $donation_data ? $donation_data['total_donations'] : 0;

     
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
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Profile</title>
</head>

<body class="<?php echo isset($_SESSION['edit_mode_enabled']) ? 'edit-mode-enabled' : ''; ?>">

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <main class="main">
        <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php else: ?>
        <section class="profile_container">

            <!-- Profile Picture Handling -->
            <div class="profile_img_container">
                <div><img class="profile_img"
                        src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'img.png'); ?>"
                        alt="Profile Picture">
                </div>
                <div>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="profile_img_change" class='bx bxs-edit-alt'></i>

                </div>

            </div>




            <div class="details">
                <div class="icon">
                    <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="full_name" class='bx bxs-edit-alt'></i>


                </div>
                <div class="icon">
                    <p>@<?php echo htmlspecialchars($user['username']); ?></p>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="username_change" class='bx bxs-edit-alt'></i>


                </div>
                <div class="icon">
                    <?php echo htmlspecialchars($user['email']); ?>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="email_change" class='bx bxs-edit-alt'></i>


                </div>
                <address class="icon">
                    <p>VC|NC: <b><?php echo htmlspecialchars($user['vc_name'] ?: 'N/A'); ?></b> Tehsil:
                        <b><?php echo htmlspecialchars($user['tehsil_name'] ?: 'N/A'); ?></b> District:
                        <b><?php echo htmlspecialchars($user['district_name'] ?: 'N/A'); ?></b>
                        <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                        <i id="address_change" class='bx bxs-edit-alt'></i>



                    </p>

                </address>

                <div class="icon">
                    <p>Blood Group: <b class="contact"><?php echo htmlspecialchars($user['blood_group']); ?></b></p>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="blood_group_change" class='bx bxs-edit-alt'></i>



                </div>

                <div class="icon">
                    <i style="color:#14a4fe;" class='bx bxs-phone-call'></i>
                    <p class="contact"><?php echo htmlspecialchars($user['contact']); ?></p>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="change_phone" class='bx bxs-edit-alt'></i>



                </div>
                <div class="icon">
                    <i style="color:#13be5d;" class='bx bxl-whatsapp'></i>
                    <p class="whatsapp"><a href="https://wa.me/<?php echo htmlspecialchars($user['whatsapp']); ?>"
                            target="_blank"><?php echo htmlspecialchars($user['whatsapp']); ?></a></p>
                    <?php //if (isset($_SESSION['edit_mode_enabled']) && $_SESSION['edit_mode_enabled'] === true): ?>
                    <i id="change_wa" class='bx bxs-edit-alt'></i>


                </div>


                <div class="icon">
                    <i style="color:#C12733;" class="bx bxs-donate-blood bx-flip-horizontal"></i>
                    <p><b class="donation"><?php echo $total_donations . ' Donation' . ($total_donations == 1 ? '' : 's'); ?></b></p>
                </div>
            </div>


        </section>

        <section class="button-container">
            <!-- <button id="edit" class="btn-dark"><i class='bx bx-pencil'></i> Edit Mode</button> -->
            <button id="save-life" class=" btn-dark"><i class='bx bx-heart'></i> Save Life</button>
            <button id="ineligible" class="btn-dark"><i class='bx bx-block'></i>Inactive</button>
            <button id="logout" class="btn-lighter"><i class='bx bx-log-out'></i> Logout</button>
            <button id="deactivate" class="btn-lighter"><i class='bx bx-user-x'></i>Delete</button>

        </section>





        <!-- Modal Saved Life (Initially Hidden) -->
        <div id="savedLifeModal" class="saved_life_container">
            <form method="POST" action="save_donation.php" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="username">Recipient Username:</label>
                    <input class="hover-focus" type="text" id="username" name="username" placeholder="farooqkhitab99">
                </div>

                <div class="form-group">
                    <label for="date">Date:</label>
                    <div class="date-input-group">
                        <input class="hover-focus" type="date" id="date" name="date">
                        <button type="button" class="today-btn btn-dark">Today</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="file">File or Document:</label>
                    <input class="hover-focus" type="file" id="file" name="file">
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
                <input type="hidden" name="donor_id" id="donor_id"
                    value="<?php echo htmlspecialchars($_SESSION['user_id'] ?? ''); ?>">
                <div class="form-group">
                    <label for="reason">Reason for Ineligibility:</label>
                    <select class="hover-focus" id="reason" name="reason" required>
                        <option value="" disabled selected>Select a reason</option>
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
                        <option value="other">Other (Enter manually)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="days">Ineligibility Period (Days):</label>
                    <input class="hover-focus" placeholder="Select a reason above" type="text" id="days" name="days"
                        required readonly disabled>
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
        <!-- Deactivate -->
        <div id="deactivate-container" class="saved_life_container">


            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Are you sure you want to deactivate your account?</h3>
                </div>

                <div class="form-group">
                    <label for="password">Enter your password to confirm:</label>
                    <input type="password" name="password" id="password" required placeholder="Your password">
                </div>
                <p id="deactivate-error" style="color: red; font-size: 15px;"></p>

                <!--<?php if (isset($_GET['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>-->

                <div class="last_btn">
                    <button type="button" id="confirm-deactivate" class="btn-dark">Deactivate</button>

                    <button type="button" id="cancel-btn4" class="btn-lighter">Cancel</button>
                </div>
            </form>

        </div>

        <!-- Profile Image Change Modal -->
        <div id="profile_img_modal" class="saved_life_container">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <h3>Update Profile Picture</h3>
                </div>

                <div class="form-group">
                    <div style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; margin: auto;">
                        <img id="profile_img_preview" src="img.png" alt="Preview"
                            style="width: 100%; object-fit: cover;">
                    </div>
                </div>


                <div class="form-group">
                    <input type="file" name="profile_img_file" id="profile_img_file" accept="image/*" required>
                    <small id="fileSizeError" style="color: #A22424; display: none; font-size: 14px; margin-top: 4px;">
                        File must be less than 2MB.
                    </small>
                </div>


                <div class="last_btn">
                    <button type="submit" name="update_profile_img" class="btn-dark">Change</button>
                    <button type="button" id="cancel-profile-img" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>


        <!-- Change Full Name Modal -->
        <div id="changeNameModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Change Your Full Name</h3>
                </div>

                <div class="form-group">
                    <label for="new_name">Enter new name:</label>
                    <input type="text" name="new_name" id="new_name" placeholder="New Full Name" required>
                </div>

                <div class="last_btn">
                    <button type="submit" name="update_full_name" class="btn-dark">Change</button>
                    <button type="button" id="cancel-name-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Change Username Modal -->
        <div id="changeUsernameModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Change Your Username</h3>
                </div>

                <div class="form-group">
                    <span id="usernameWarning"></span>
                    <span style="display:block;" id="taken" style="color: red;"></span>
                    <label for="new_username">Enter new username:</label>
                    <input type="text" id="new_username" name="new_username" placeholder="New Username" required>

                </div>

                <div class="last_btn">
                    <button type="submit" name="update_username" class="btn-dark">Change</button>
                    <button type="button" id="cancel-username-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>


        <!-- Change Email Modal -->
        <div id="changeEmailModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Change Your Email Address</h3>
                </div>

                <div class="form-group">
                    <label for="new_email">Enter new email:</label>
                    <input type="email" id="new_email" name="new_email" placeholder="example@email.com" required>
                    <span id="emailWarning"></span>
                    <span id="emailWarning2" style="color:red;"></span>
                </div>

                <div class="last_btn">
                    <button type="submit" name="update_email" class="btn-dark">Change</button>
                    <button type="button" id="cancel-email-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Address change -->
        <!-- Change Address Modal -->
        <div id="changeAddressModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Update Your Address</h3>
                </div>

                <div class="form-group">
                    <label for="district_change">District:</label>
                    <select id="district_change" name="district" required>
                        <option value="">--Select District--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tehsil_change">Tehsil:</label>
                    <select id="tehsil_change" name="tehsil" required>
                        <option value="">--Select Tehsil--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="vc_change">VC | NC:</label>
                    <select id="vc_change" name="vc" required>
                        <option value="">--Select VC--</option>
                    </select>
                </div>

                <?php if (isset($_GET['address_error'])): ?>
                <p style="color: blue;"><?php echo htmlspecialchars($_GET['address_error']); ?></p>
                <?php endif; ?>

                <div class="last_btn">
                    <button type="submit" name="update_address" class="btn-dark">Change</button>
                    <button type="button" id="cancel-address-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>

        </div>

        <!-- Change Contact Modal -->
        <div id="changeContactModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Change Your Contact Number</h3>
                </div>

                <div class="form-group">
                    <label for="new_contact">Enter new contact number:</label>
                    <input type="tel" id="new_contact" name="new_contact" placeholder="03XXXXXXXXX" required>
                    <span id="contactWarningModal" style="color:red;"></span>
                </div>

                <?php if (isset($_GET['contact_error'])): ?>
                <p style="color: blue;"><?php echo htmlspecialchars($_GET['contact_error']); ?></p>
                <?php endif; ?>

                <div class="last_btn">
                    <button type="submit" name="update_contact" class="btn-dark">Change</button>
                    <button type="button" id="cancel-contact-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Change WhatsApp Number Modal -->
        <div id="changeWhatsappModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Change WhatsApp Number</h3>
                </div>

                <div class="form-group">
                    <label for="new_whatsapp">Enter new WhatsApp number:</label>
                    <input type="tel" id="new_whatsapp" name="new_whatsapp" placeholder="03XXXXXXXXX" required>
                    <span id="whatsappWarningModal" style="color:red;"></span>
                </div>

                <?php if (isset($_GET['wa_error'])): ?>
                <p style="color: blue;"><?php echo htmlspecialchars($_GET['wa_error']); ?></p>
                <?php endif; ?>

                <div class="last_btn">
                    <button type="submit" name="update_whatsapp" class="btn-dark">Change</button>
                    <button type="button" id="cancel-wa-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>


        <!-- Change Blood Group Modal -->
        <div id="changeBloodModal" class="saved_life_container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <h3>Change Your Blood Group</h3>
                </div>

                <div class="form-group">
                    <label for="new_blood_group">Select your new blood group:</label>
                    <select id="new_blood_group" name="new_blood_group" required>
                        <option value="" disabled selected>Select your blood group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>

                <?php if (isset($_GET['blood_error'])): ?>
                <p style="color: blue;"><?php echo htmlspecialchars($_GET['blood_error']); ?></p>
                <?php endif; ?>

                <div class="last_btn">
                    <button type="submit" name="update_blood_group" class="btn-dark">Change</button>
                    <button type="button" id="cancel-blood-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Edit Mode Password Confirmation -->
        <!-- <div id="editModeModal" class="saved_life_container">
            <form id="edit-mode-form">
                <div class="form-group">
                    <h3>Enter Password to Enable Edit Mode</h3>
                </div>

                <div class="form-group">
                    <label for="edit_password">Password:</label>
                    <input type="password" name="edit_password" id="edit_password" required placeholder="Your password">
                    <small id="edit-error-msg" style="color: red;"></small>
                </div>

                <div class="last_btn">
                    <button type="submit" id="enable-edit-btn" class="btn-dark">On Edit Mode</button>
                    <button type="button" id="cancel-edit-btn" class="btn-lighter">Cancel</button>
                </div>
            </form>
        </div> -->








        <?php endif; ?>
    </main>

    <script src="js/profile_button.js"></script>
    <script>
    document.querySelector(".today-btn").addEventListener("click", function() {
        const today = new Date().toISOString().split('T')[0]; // Get YYYY-MM-DD
        document.getElementById("date").value = today;
    });
    </script>

</body>

</html>