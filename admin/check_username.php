<?php

$host = "localhost"; // Change if your DB host is different
$dbname = "bloodbridge";
$username = "root"; // Change if your DB username is different
$password = ""; // Change if you have a password set

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['username'])) {
    $userName = trim($_POST['username']);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM donors WHERE username = ?");
    $stmt->execute([$userName]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<span style='color: red;'>Username is already taken</span>";
    } else {
        echo "<span style='color: green;'>Username is available</span>";
    }
}

?>
