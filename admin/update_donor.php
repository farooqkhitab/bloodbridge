<?php
require_once 'db_config.php';

$id = $_POST['id'];
$fields = [
    'full_name', 'username', 'email', 'dob', 'contact', 'whatsapp',
    'blood_group', 'district', 'tehsil', 'vc'
];

$updateParts = [];
$params = [];

// Prepare regular fields
foreach ($fields as $field) {
    if (isset($_POST[$field])) {
        $updateParts[] = "$field = ?";
        $params[] = $_POST[$field];
    }
}


// Get number of days to extend 'until' date
$days = isset($_POST['days_active']) ? (int)$_POST['days_active'] : 0;

// Get current status and until for the donor
$stmt = $pdo->prepare("SELECT status, until FROM donors WHERE id = ?");
$stmt->execute([$id]);
$donor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($donor) {
    $today = new DateTime();
    $currentUntil = !empty($donor['until']) ? new DateTime($donor['until']) : null;
    $status = $donor['status'];
    
    // Use currentUntil as base if it exists, otherwise use today or if until in the past also use today
    $baseDate = ($currentUntil && $currentUntil >= $today) ? $currentUntil : $today;
    $newUntil = (clone $baseDate)->modify("$days days");
    
    if ($status == '1') {
        // For positive days, only update if newUntil is later than currentUntil
        if ($days > 0 && $currentUntil && $newUntil <= $currentUntil) {
            // Skip update if newUntil is not later than currentUntil
        } else {
            $updateParts[] = "until = ?";
            $params[] = $newUntil->format('Y-m-d');
        }
    } elseif ($status == '0') {
        // Only update if current until is different
        if (!$currentUntil || $newUntil != $currentUntil) {
            $updateParts[] = "until = ?";
            $params[] = $newUntil->format('Y-m-d');
        }
    }
}


// Handle profile picture
if (!empty($_FILES['profile_picture']['name'])) {
    $targetDir = "../profile_pictures/";
    $fileName = basename($_FILES["profile_picture"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        $updateParts[] = "profile_picture = ?";
        $params[] = "profile_pictures/" . $fileName;
       
    }
}

// Finalize update
$params[] = $id;

$sql = "UPDATE donors SET " . implode(', ', $updateParts) . " WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute($params);

echo json_encode(['success' => $success]);
