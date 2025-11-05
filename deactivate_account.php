<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? null;
    $password = $_POST['password'] ?? '';

    if (!$userId || !$password) {
        echo json_encode(['success' => false, 'error' => 'Password required']);
        exit;
    }

    // Fetch stored hash
    $stmt = $pdo->prepare("SELECT password_hash FROM donors WHERE id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !password_verify($password, $row['password_hash'])) {
        echo json_encode(['success' => false, 'error' => 'Incorrect password']);
        exit;
    }

    // Delete user (you can soft-delete with status = 0 if preferred)
    $stmt = $pdo->prepare("DELETE FROM donors WHERE id = ?");
    $stmt->execute([$userId]);

    // Destroy session
    $_SESSION = [];
    session_destroy();

    echo json_encode(['success' => true, 'redirect' => 'login.php?message=Account deactivated']);
    exit;
}
