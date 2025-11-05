<?php
session_start();

require 'db_config.php';

$donor_id = $_SESSION['user_id'] ?? null;
$receiver = trim($_POST['username'] ?? '');
$date = $_POST['date'] ?? '';

if (!$donor_id || empty($receiver) || empty($date)) {
    echo "<script>alert('All fields are required.');history.back();</script>";
    exit();
}

$filePath = null;
if (!empty($_FILES['file']['name'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = time() . '_' . basename($_FILES['file']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $filePath = $targetFile;
    }
}

$stmt = $pdo->prepare("INSERT INTO donation_verification (donor_id, receiver_username, date, file) VALUES (?, ?, ?, ?)");
$stmt->execute([$donor_id, $receiver, $date, $filePath]);

echo "<script>alert('Donation saved successfully.'); window.location.href='profile.php';</script>";
exit;
