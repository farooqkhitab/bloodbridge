<?php

// Database configuration
$host = "localhost";
$dbname = "bloodbridge";
$username = "root"; // Update if needed
$password = "";     // Update if needed

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>