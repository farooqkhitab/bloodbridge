<?php
session_start();
require_once '../db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo "unauthorized";
    exit;
}

$password = $_POST['password'] ?? '';
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT password_hash FROM donors WHERE id = ?");
$stmt->execute([$userId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && password_verify($password, $row['password_hash'])) {
    $_SESSION['edit_mode_enabled'] = true;
    $_SESSION['edit_mode_expires'] = time() + (15 * 60); // 15 minutes
    echo "success";
} else {
    echo "fail";
}
