<?php
require_once '../db_config.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $action = $input['action'];

    if ($action === 'verify') {
        // Begin transaction
        $conn->begin_transaction();

        // Get verification record
        $stmt = $conn->prepare("SELECT donor_id, receiver_username, donation_date FROM donation_verification WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();

        if ($record) {
            $donor_id = $record['donor_id'];
            $receiver_username = $record['receiver_username'];
            $donation_date = $record['donation_date'];

            // Update donation_summary
            $stmt = $conn->prepare("UPDATE donation_summary SET total_donations = total_donations + 1, last_donation = ? WHERE donor_id = ?");
            $stmt->bind_param("si", $donation_date, $donor_id);
            $stmt->execute();

            // Calculate new eligibility date (90 days from donation)
            $eligibility_date = date('Y-m-d', strtotime($donation_date . ' +90 days'));
            $stmt = $conn->prepare("UPDATE donors SET until = ? WHERE id = ?");
            $stmt->bind_param("si", $eligibility_date, $donor_id);
            $stmt->execute();

            // Delete verification record
            $stmt = $conn->prepare("DELETE FROM donation_verification WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $conn->commit();
            echo json_encode(['success' => true]);
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => 'Record not found']);
        }
    } elseif ($action === 'reject') {
        // Simply delete the verification record
        $stmt = $conn->prepare("DELETE FROM donation_verification WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>