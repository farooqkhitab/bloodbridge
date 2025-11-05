<?php
session_start();
require_once 'db_config.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to check if the username or email exists
    $stmt = $pdo->prepare("SELECT * FROM donors WHERE username = ? OR email = ?");
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and password is correct
    if ($user && password_verify($password, $user['password_hash'])) {
        // Password is correct, create session for the user
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        $_SESSION['username'] = $user['username']; // Store username in session
        $_SESSION['profile_picture'] = $user['profile_picture']; // Store profile picture in session

        // Redirect to the profile page after successful login
        header("Location: profile.php");
        exit();
    } else {
        // If login failed, show an error message
        echo "<script>alert('Invalid username/email or password');</script>";
    }
}
?>

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
            <h2>Log In</h2>
            <form action="login.php" method="POST" enctype="multipart/form-data">
                <!-- Username or Email -->
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" placeholder="Enter your username or email" required>

                <!-- Password -->
                <label for="password">Password:</label>
                <div class="pass_group" id="pass_group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i id="eyeicon" class="fa-solid fa-eye-slash"></i>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn">Log In</button>
                <p style="margin-top: 20px; text-align: center; font-family: Arial, sans-serif; color: #333;">Don't have
                    Account: <a href="signup.php" style="color: #0066cc; text-decoration: none;">Click Here</a></p>
            </form>
        </section>
    </main>

    <!-- Include the password toggle script -->
    <script src="js/password_toggle.js"></script>
</body>

</html>