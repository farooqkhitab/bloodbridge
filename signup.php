<!-- 
 Add a bulit point or some thing for asking the user
 Are you donate blood in the past 90 days
  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/signup.css?v=1.0">
    <!-- Font Awesome connectivity -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Boxicons for consistency with your other components -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="images/favicon.ico">

</head>

<body>

    <main>
        <section class="registration-form">
            <h2>Sign Up</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Full Name -->
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>

                <!-- Username -->
                <label for="username">Username:</label> <span id="usernameWarning"></span> <span id="taken"
                    style="color: red;"></span>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>

                <!-- Email -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <span id="emailWarning"></span>
                <span id="emailWarning2"></span>

                <!-- Date of Birth with Validation -->
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
                <span id="ageWarning" style="color: red;"></span>

                <!-- Password -->
                <label for="password">Password:</label>

                <div class="pass_group" id="pass_group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i id="eyeicon" class="fa-solid fa-eye-slash"></i>
                </div>


                <!-- Verify Password -->
                <label for="confirmPassword">Verify Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter your password"
                    required>
                <span id="passwordWarning" style="color: red;"></span>


                <!-- Contact Number -->
                <label for="contact">Contact Number:</label>
                <input type="tel" id="contact" name="contact" placeholder="Enter your contact number" required>
                <span id="contactWarning"></span>

                <!-- Checkbox for WhatsApp Same as Contact -->
                <label for="same-whatsapp">WhatsApp No. Same as Contact No.</label>
                <input type="checkbox" id="same-whatsapp" name="same_whatsapp">

                <!-- WhatsApp Number -->
                <label for="whatsapp">WhatsApp Number:</label>
                <input type="tel" id="whatsapp" name="whatsapp" placeholder="Enter your WhatsApp number" required>
                <span id="whatsappWarning"></span>


                <!-- Ask: Did you donate in the past X days -->
                <label for="donated_recently">Did you donate blood recently?</label>
                <select name="donated_recently" id="donated_recently" required onchange="toggleCustomDays()">
                    <option value="">--Select--</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>

                <label for="custom_days" id="custom_days_label" style="display:none;">If yes, how many days ago?</label>
                <input type="number" name="custom_days" id="custom_days" min="1" placeholder="e.g., 60"
                    style="display:none;">

                <script>
                function toggleCustomDays() {
                    var donatedRecently = document.getElementById("donated_recently").value;
                    var customDaysInput = document.getElementById("custom_days");
                    var customDaysLabel = document.getElementById("custom_days_label");

                    if (donatedRecently === "yes") {
                        customDaysInput.style.display = "inline-block";
                        customDaysLabel.style.display = "inline-block";
                    } else {
                        customDaysInput.style.display = "none";
                        customDaysLabel.style.display = "none";
                    }
                }
                </script>




                <!-- Blood Group -->
                <label for="blood-group">Blood Group:</label>
                <select id="blood-group" name="blood_group" required>
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

                <!-- Address Details -->
                <label for="district">District:</label>
                <select id="district" name="district" required>
                    <option value="">--Select District--</option>
                </select>

                <label for="tehsil">Tehsil:</label>
                <select id="tehsil" name="tehsil" required>
                    <option value="">--Select Tehsil--</option>
                </select>

                <label for="vc">VC | NC:</label>
                <select id="vc" name="vc" required>
                    <option value="">--Select VC--</option>
                </select>

                <!-- Profile Picture -->
                <label for="profilePic">Profile Picture:</label>
                <input type="file" id="profilePic" name="profilePic" accept="image/*" required>

                <!-- Terms and Services -->
                <label for="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    I accept the <a href="#">Terms and Services</a>.
                </label>


                <!-- Submit Button -->
                <button type="submit" class="btn">Sign Up</button>
                <p style="margin-top: 20px; text-align: center; font-family: Arial, sans-serif; color: #333;">Already
                    have
                    Account: <a href="login.php" style="color: #0066cc; text-decoration: none;">Click Here</a></p>
            </form>

            <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo $errorMessage; ?>
            </div>
            <?php endif; ?>

        </section>
    </main>

    <script src="js/password_toggle.js"></script>
    <script src="js/reg_validation.js"></script>
    <script src="js/dropdown.js"></script>


    <?php
// Database configuration
require_once 'db_config.php'; // Include your database connection file


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $userName = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $contact = $_POST['contact'];
    $whatsapp = $_POST['whatsapp'];
    $bloodGroup = $_POST['blood_group'];
    $district = $_POST['district'];
    $tehsil = $_POST['tehsil'];
    $vc = $_POST['vc'];
    // Get form data
    $donatedRecently = $_POST['donated_recently'];
    $customDays = isset($_POST['custom_days']) ? intval($_POST['custom_days']) : null;

    $status = '1';
    $until = null;

    if ($donatedRecently === 'yes' && $customDays) {
        $remainingDays = 90 - $customDays;
        if ($remainingDays <= 0) {
            $status = '1';
            $until = date('Y-m-d');
        } else {
            $until = date('Y-m-d', strtotime("+{$customDays} days"));
            $status = '0';
        }
    } else {
        // If the user didn't donate recently
        $until = date('Y-m-d'); // Today's date or null
        $status = '1';
    }






    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "profile_pictures/";
        $fileName = basename($_FILES['profilePic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Allow only specific image formats
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedExtensions)) {
            die("Invalid file type! Please upload a JPG, JPEG, PNG, or GIF image.");
        }

        $targetPath = $uploadDir . time() . "_" . $fileName;
        if (!move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetPath)) {
            die("Error uploading profile picture.");
        }
        $profilePicture = $targetPath;
    } else {
        die("Please upload a valid profile picture.");
    }

    try {
        // Check if the username or email already exists
        $checkQuery = "SELECT * FROM donors WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([$userName, $email]);
        
        if ($stmt->rowCount() > 0) {
            echo '<script>alert("Error: Username or Email already exists. Please use a different one.");</script>';
            exit;
        }

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO donors (full_name, username, email, dob, password_hash, contact, whatsapp, blood_group, district, tehsil, vc, profile_picture, status, until) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullName, $userName, $email, $dob, $password, $contact, $whatsapp, $bloodGroup, $district, $tehsil, $vc, $profilePicture, $status, $until ]);

        // ✅ Get new donor ID
$donorId = $pdo->lastInsertId();

// ✅ Insert into donation_summary
$pdo->prepare("INSERT INTO donation_summary (donor_id) VALUES (?)")->execute([$donorId]);

        // Success Message & Redirect
        echo '<div id="successMessage" style="display: none; text-align: center; font-size: 20px; color: green;">
        Registration Successful! Redirecting...
        </div>';
        echo '<script>
            document.querySelector(".registration-form").style.display = "none";
            document.querySelector("form").style.display = "none";
            document.getElementById("successMessage").style.display = "block";
            setTimeout(() => { window.location.href = "login.php"; }, 3000);
        </script>';

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>



</body>

</html>