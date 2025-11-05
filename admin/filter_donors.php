<?php
// File: admin/ajax/filter_donors.php
require_once 'db_config.php';

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$filters = [
    'district' => $_POST['district'] ?? '',
    'tehsil' => $_POST['tehsil'] ?? '',
    'vc' => $_POST['vc'] ?? '',
    'blood_group' => $_POST['blood_group'] ?? '',
    'full_name' => $_POST['name'] ?? '',
    'username' => $_POST['username'] ?? ''
];

$where = "WHERE 1";
$params = [];

foreach ($filters as $key => $val) {
    if (!empty($val)) {
        if ($key === 'full_name' || $key === 'username') {
            $where .= " AND $key LIKE ?";
            $params[] = "%$val%";
        } else {
            $where .= " AND $key = ?";
            $params[] = $val;
        }
    }
}

// Get total count for pagination
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM donors $where");
$totalStmt->execute($params);
$totalDonors = $totalStmt->fetchColumn();
$totalPages = ceil($totalDonors / $limit);

// Fetch paginated donors with joined location names
$sql = "
    SELECT 
        donors.*,
        districts.name AS district_name,
        tehsils.name AS tehsil_name,
        vcs_ncs.name AS vc_name
    FROM donors
    LEFT JOIN districts ON donors.district = districts.id
    LEFT JOIN tehsils ON donors.tehsil = tehsils.id
    LEFT JOIN vcs_ncs ON donors.vc = vcs_ncs.id
    $where
    ORDER BY donors.created_at DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Display table rows
ob_start();
foreach ($donors as $donor): 
    $today = date('Y-m-d');
    $statusActive = ($donor['status'] === '1') || ($donor['until'] && $donor['until'] <= $today);
?>
<tr>

    <td><img src="../<?= htmlspecialchars($donor['profile_picture']) ?>" alt="Donor" class="donor-pic"></td>
    <td><?= htmlspecialchars($donor['full_name']) ?></td>
    <td><?= htmlspecialchars($donor['username']) ?></td>
    <td><?= htmlspecialchars($donor['email']) ?></td>
    <td><span class="blood-group"><?= htmlspecialchars($donor['blood_group']) ?></span></td>
    <td><?= htmlspecialchars($donor['contact']) ?><br>
    WA: <?= htmlspecialchars($donor['whatsapp']) ?></td>
    <td>
        <?= htmlspecialchars($donor['vc_name']) ?>,
        <?= htmlspecialchars($donor['tehsil_name']) ?>,
        <?= htmlspecialchars($donor['district_name']) ?>
    </td>

    <td>
        <span class="status <?= $statusActive ? 'active' : 'inactive' ?>">
            <?= $statusActive ? 'Active' : 'Inactive until ' . htmlspecialchars($donor['until']) ?>
        </span>
    </td>
    <td><?= htmlspecialchars($donor['total_donations'] ?? 0) ?></td>
    <td><?= htmlspecialchars(date('Y-m-d', strtotime($donor['dob']))) ?></td>
    <td class="action-buttons">
        <button class="edit-btn" data-id="<?= $donor['id'] ?>"
            data-full_name="<?= htmlspecialchars($donor['full_name']) ?>"
            data-username="<?= htmlspecialchars($donor['username']) ?>"
            data-email="<?= htmlspecialchars($donor['email']) ?>" data-dob="<?= htmlspecialchars($donor['dob']) ?>"
            data-contact="<?= htmlspecialchars($donor['contact']) ?>"
            data-whatsapp="<?= htmlspecialchars($donor['whatsapp']) ?>"
            data-blood_group="<?= htmlspecialchars($donor['blood_group']) ?>"
            data-district="<?= htmlspecialchars($donor['district']) ?>"
            data-tehsil="<?= htmlspecialchars($donor['tehsil']) ?>" data-vc="<?= htmlspecialchars($donor['vc']) ?>"
            
            title="Edit"><i class="fas fa-edit"></i></button>

            <button class="delete-btn" data-donor-id="<?= htmlspecialchars($donor['id']) ?>" title="Delete">
    <i class="fas fa-trash"></i>
</button>


    </td>
</tr>
<?php endforeach;
$tableRows = ob_get_clean();

// Build pagination HTML


$pagination = '<div class="pagination">';
$pagination .= '<button class="pagination-btn prev-page"' . ($page <= 1 ? ' disabled' : '') . '><i class="fas fa-chevron-left"></i></button>';
$pagination .= '<div class="page-numbers">';
$range = 1;
$ellipsisShown = false;

for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)) {
        $active = $i == $page ? 'active' : '';
        $pagination .= "<button class='page-number $active' data-page='$i'>$i</button>";
        $ellipsisShown = false;
    } else {
        if (!$ellipsisShown) {
            $pagination .= "<span class='page-ellipsis'>...</span>";
            $ellipsisShown = true;
        }
    }
}

$pagination .= '</div>';
$pagination .= '<button class="pagination-btn next-page"' . ($page >= $totalPages ? ' disabled' : '') . '><i class="fas fa-chevron-right"></i></button>';
$pagination .= '</div>';

// Return response as JSON
header('Content-Type: application/json');
echo json_encode([
    'tableRows' => $tableRows,
    'pagination' => $pagination
]);