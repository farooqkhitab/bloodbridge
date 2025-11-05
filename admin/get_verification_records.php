<?php
require_once '../db_config.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT dv.id, dv.donor_id, dv.receiver_username, dv.created_at, dv.donation_date, dv.file,
               d1.full_name AS donor_full_name, d1.username AS donor_username, d1.profile_picture,
               d2.full_name AS recipient_full_name, d2.username AS recipient_username,
               d2.contact AS recipient_contact, d2.whatsapp AS recipient_whatsapp
        FROM donation_verification dv
        JOIN donors d1 ON dv.donor_id = d1.id
        JOIN donors d2 ON dv.receiver_username = d2.username
        ORDER BY dv.created_at ASC
    ";

    $result = $conn->query($query);
    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    echo json_encode(['success' => true, 'records' => $records]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>