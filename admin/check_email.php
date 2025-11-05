<?php

$host = "localhost"; // Update if needed
$dbname = "bloodbridge";
$username = "root"; // Update if needed
$password = ""; // Update if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM donors WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<span style='color: red;'>Email is already taken</span>";
    } else {
        echo "<span style='color: green;'>Email is available</span>";
    }
}

?>
