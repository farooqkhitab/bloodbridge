<?php
require_once 'db_config.php'; // make sure your DB connection is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donor_id'])) {
    $donor_id = intval($_POST['donor_id']); // Always sanitize

    try {
        $stmt = $pdo->prepare("DELETE FROM donors WHERE id = ?");
        $stmt->execute([$donor_id]);

        if ($stmt->rowCount() > 0) {
            echo "success";
        } else {
            echo "not_found"; // ID not found in database
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo "error: " . $e->getMessage(); // Helpful for debugging
    }
} else {
    http_response_code(400);
    echo "invalid_request";
}
?>