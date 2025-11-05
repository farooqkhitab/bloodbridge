<?php
// Start the session to check login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine the current page to highlight the active tab
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>

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
    <!-- favicon -->
    <link rel="icon" type="image/png" href="images/favicon.ico">

    <link rel="stylesheet" href="css/navbar.css">
</head>

<body>
    <nav id="navbar">
        <a href="index.php">
            <img id="logo" src="images/Logo.png" alt="BloodBridge Logo">
        </a>

        <div class="tabs">
            <a href="index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="blood-request.php"
                class="<?php echo $current_page === 'blood-request.php' ? 'active' : ''; ?>">Blood Request</a>
            <a href="quiz.php" class="<?php echo $current_page === 'quiz.php' ? 'active' : ''; ?>">Quiz</a>
            <a href="about-us.php" class="<?php echo $current_page === 'about-us.php' ? 'active' : ''; ?>">About Us</a>
        </div>

        <div class="sign_login_btn">
            <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Show profile picture if logged in -->
            <a href="profile.php" class="profile-link">
                <img src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?: 'images/default-profile.png'); ?>?t=<?=time()?>"
                    alt="Profile Picture" class="profile-pic">
            </a>
            <?php else: ?>
            <!-- Show Log In and Sign Up buttons if not logged in -->
            <a href="login.php"><button style="cursor:pointer;" class="btn_style_1" id="login">Log In</button></a>
            <a href="signup.php"><button style="cursor:pointer;" class="btn_style_2" id="sign_up">Sign Up</button></a>
            <?php endif; ?>
        </div>

    </nav>
</body>

</html>