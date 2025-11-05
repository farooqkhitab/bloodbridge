<?php
require 'db_config.php';

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$blood_group = $_POST['blood_group'] ?? '';
$district = $_POST['district'] ?? '';
$tehsil = $_POST['tehsil'] ?? '';
$vc = $_POST['vc'] ?? '';

$where = "WHERE status = 1";
$params = [];

if ($blood_group) {
    $where .= " AND blood_group = ?";
    $params[] = $blood_group;
}
if ($district) {
    $where .= " AND district = ?";
    $params[] = $district;
}
if ($tehsil) {
    $where .= " AND tehsil = ?";
    $params[] = $tehsil;
}
if ($vc) {
    $where .= " AND vc = ?";
    $params[] = $vc;
}

// Count total
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM donors $where");
$totalQuery->execute($params);
$totalDonors = $totalQuery->fetchColumn();
$totalPages = ceil($totalDonors / $limit);

// Fetch paginated results
$sql = "SELECT * FROM donors $where LIMIT $limit OFFSET $offset";
// $sql = "SELECT * FROM donors $where ORDER BY full_name ASC LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Start output
$output = "";
foreach ($donors as $row) {
    $id = (int)$row['id'];
    $profile = htmlspecialchars($row['profile_picture'] ?? 'images/default-profile.png');
    $name = htmlspecialchars($row['full_name']);
    $contact = htmlspecialchars($row['contact']);
    $wa = htmlspecialchars($row['whatsapp']);
    $blood_group = htmlspecialchars($row['blood_group']); // <- add this line

    $output .= "
    <div class='donor-card' style='display: flex;
    
        gap: 15px;
        align-items: center;
        border: 2px solid #A22424;
        padding: 10px;
        margin: 8px 0;
        background: #fff;
        border-radius: 10px;'>

        <input type='checkbox' class='donor-checkbox' value='$id'>
        <img src='$profile' class='profile-img' style='width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;'>
    
        <span class='donor-name'><strong>$name</strong></span>
        <span style='color:#A22424;'>$blood_group</span> <!-- show blood group -->
        <a href='https://wa.me/$wa' target='_blank' style='color:green;'>$wa</a>
        <span class='contact' style='color:blue;'>$contact</span>
    </div>
    ";
}


// Pagination controls
$output .= "<div class='pagination' style='text-align: center; margin-top: 20px;'>";

for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i == $page) ? "background-color: #A22424; color: white; font-weight: bold;" : "";
    
    $output .= "<button class='page-btn' data-page='$i' style='padding: 5px 10px;
    margin: 3px;
    border: 1px solid #A22424;
    cursor: pointer; $activeClass'>$i</button>";
}

echo $output;
?>