<?php 
require_once 'auth_check.php';
include 'header.php';

// Database connection (adjust these credentials to match your setup)
require_once 'db_config.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'verify_donation') {
        $verification_id = $_POST['verification_id'];
        
        try {
            $pdo->beginTransaction();
            
            // Get verification details
            $stmt = $pdo->prepare("SELECT * FROM donation_verification WHERE id = ?");
            $stmt->execute([$verification_id]);
            $verification = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($verification) {
                // Update donation_summary
                $stmt = $pdo->prepare("
                    INSERT INTO donation_summary (donor_id, total_donations, last_donation) 
                    VALUES (?, 1, ?) 
                    ON DUPLICATE KEY UPDATE 
                    total_donations = total_donations + 1, 
                    last_donation = ?
                ");
                $stmt->execute([
                    $verification['donor_id'], 
                    $verification['date'], 
                    $verification['date']
                ]);
                
                // Update donor's until field (add 90 days)
                $new_until_date = date('Y-m-d', strtotime($verification['date'] . ' + 90 days'));
                $stmt = $pdo->prepare("UPDATE donors SET until = ? WHERE id = ?");
                $stmt->execute([$new_until_date, $verification['donor_id']]);
                
                // Remove from verification table
                $stmt = $pdo->prepare("DELETE FROM donation_verification WHERE id = ?");
                $stmt->execute([$verification_id]);
                
                $pdo->commit();
                ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Donation verified successfully']);
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    if ($action === 'reject_donation') {
        $verification_id = $_POST['verification_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM donation_verification WHERE id = ?");
            $stmt->execute([$verification_id]);
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Donation rejected']);
        } catch (Exception $e) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    if ($action === 'get_verification_by_id') {
        $verification_id = $_POST['verification_id'] ?? null;
        
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    dv.*,
                    d.full_name as donor_name,
                    d.username as donor_username,
                    d.profile_picture as donor_image,
                    r.full_name as receiver_name,
                    r.username as receiver_username,
                    r.contact as receiver_contact,
                    r.whatsapp as receiver_whatsapp,
                    ds.last_donation
                FROM donation_verification dv
                JOIN donors d ON dv.donor_id = d.id
                JOIN donors r ON dv.receiver_username = r.username
                LEFT JOIN donation_summary ds ON d.id = ds.donor_id
                WHERE dv.id = ?
            ");
            $stmt->execute([$verification_id]);
            $verification = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stmt = $pdo->prepare("SELECT id FROM donation_verification ORDER BY id ASC");
            $stmt->execute();
            $all_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $current_index = array_search($verification_id, $all_ids);
            
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => (bool)$verification,
                'verification' => $verification,
                'all_ids' => $all_ids,
                'current_index' => $current_index !== false ? $current_index : 0
            ]);
        } catch (Exception $e) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
}

include 'header.php';
?>

<?php
// Get initial verification data
$stmt = $pdo->prepare("
    SELECT 
        dv.*,
        d.full_name as donor_name,
        d.username as donor_username,
        d.profile_picture as donor_image,
        r.full_name as receiver_name,
        r.username as receiver_username,
        r.contact as receiver_contact,
        r.whatsapp as receiver_whatsapp,
        ds.last_donation
    FROM donation_verification dv
    JOIN donors d ON dv.donor_id = d.id
    JOIN donors r ON dv.receiver_username = r.username
    LEFT JOIN donation_summary ds ON d.id = ds.donor_id
    ORDER BY dv.id ASC
    LIMIT 1
");
$stmt->execute();
$current_verification = $stmt->fetch(PDO::FETCH_ASSOC);

// Get total count and IDs
$stmt = $pdo->prepare("SELECT COUNT(*) FROM donation_verification");
$stmt->execute();
$total_verifications = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT id FROM donation_verification ORDER BY id ASC");
$stmt->execute();
$all_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
$current_id = $current_verification ? $current_verification['id'] : null;

// Calculate days since last donation
$days_ago = 0;
if ($current_verification && $current_verification['last_donation']) {
    $last_donation_date = new DateTime($current_verification['last_donation']);
    $current_date = new DateTime();
    $days_ago = $last_donation_date->diff($current_date)->days;
}
?>

<head>
    <!-- Existing head content -->
    <style>
        .last-donation-info {
            margin-top: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #28a745;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }
        .last-donation-info .label {
            font-weight: bold;
            margin-right: 10px;
        }
        .last-donation-info .value {
            color: #555;
        }
        .last-donation-info .days-ago {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Include Sidebar -->
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <div class="user-info">
                    <div class="profile">
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Admin Profile">
                        <span>Admin User</span>
                    </div>
                </div>
            </div>

            <div class="donation-verification" id="donationVerificationSection">
                <h2>Donation Verification</h2>

                <?php if ($current_verification): ?>
                <div class="verification-container">
                    <div class="verification-card-container">
                        <div class="verification-navigation">
                            <button class="nav-btn prev-btn" id="prevBtn" <?php echo ($total_verifications <= 1) ? 'disabled' : ''; ?>>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <span class="verification-count" id="verificationCount">
                                Processing request 1 out of <?php echo $total_verifications; ?>
                            </span>
                            <button class="nav-btn next-btn" id="nextBtn" <?php echo ($total_verifications <= 1) ? 'disabled' : ''; ?>>
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        <div class="verification-card" id="verificationCard">
                            <div class="verification-header">
                                <div class="donor-profile">
                                    <img src="../<?php echo htmlspecialchars($current_verification['donor_image'] ?: 'images/default-profile.png'); ?>"
                                         alt="<?php echo htmlspecialchars($current_verification['donor_name']); ?>"
                                         class="verification-profile-pic">
                                </div>
                                <div class="verification-timestamp">
                                    <span>Submitted on: <?php echo date('F j, Y, g:i A', strtotime($current_verification['created_at'])); ?></span>
                                    <span class="verification-id">#VER-<?php echo str_pad($current_verification['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                </div>
                            </div>
                            <div class="verification-statement">
                                <p>I <strong><?php echo htmlspecialchars($current_verification['donor_name']); ?></strong>
                                   (<span class="username">@<?php echo htmlspecialchars($current_verification['donor_username']); ?></span>)
                                   give blood donation to
                                   <strong><?php echo htmlspecialchars($current_verification['receiver_name']); ?></strong>
                                   (<span class="username">@<?php echo htmlspecialchars($current_verification['receiver_username']); ?></span>) on
                                   <span class="donation-verification-date"><?php echo date('d/m/Y', strtotime($current_verification['date'])); ?></span>
                                </p>
                            </div>
                            <div class="verification-details">
                                <div class="detail-item">
                                    <div class="detail-label">Recipient Contact</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($current_verification['receiver_contact']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Recipient WhatsApp</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($current_verification['receiver_whatsapp']); ?></div>
                                </div>
                            </div>
                            <div class="verification-evidence">
                                <h4>Evidence Provided</h4>
                                <div class="evidence-images">
                                    <div class="evidence-item">
                                        <img src="../<?php echo htmlspecialchars($current_verification['file'] ?: 'placeholder.jpg'); ?>"
                                             alt="Evidence Image" id="evidenceImage">
                                        <span class="evidence-label">Hospital Receipt</span>
                                        <button class="evidence-view-btn" onclick="viewFullSize()">
                                            <i class="fas fa-search-plus"></i> View Full Size
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="last-donation-info">
                                <span class="label">Last Donation:</span>
                                <span class="value"><?php echo $current_verification['last_donation'] ? date('F j, Y', strtotime($current_verification['last_donation'])) : 'Never'; ?></span>
                                <span class="label">Days Ago:</span>
                                <span class="days-ago"><?php echo $current_verification['last_donation'] ? $days_ago : 'N/A'; ?></span>
                            </div>
                            <div class="verification-actions">
                                <div class="action-buttons">
                                    <button class="verify-btn" onclick="verifyDonation(<?php echo $current_verification['id']; ?>)">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </button>
                                    <button class="reject-btn" onclick="rejectDonation(<?php echo $current_verification['id']; ?>)">
                                        <i class="fas fa-times-circle"></i> Not Verified
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="no-verifications">
                    <h3>No pending verification requests</h3>
                    <p>All donation verifications have been processed.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal for full-size image view -->
    <div id="imageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9);">
        <div style="position: relative; 
       margin: 0 auto; 
       width: 80%; 
       text-align: center;
       top: 50%;
       transform: translateY(-50%);">
            <span onclick="closeModal()" style="position: absolute; top: -30px; right: 0; color: white; font-size: 28px; font-weight: bold; cursor: pointer;">×</span>
            <img id="modalImage" style="max-width: 100%; max-height: 70vh;">
        </div>
    </div>

    <script>
        let currentId = <?php echo json_encode($current_id); ?>;
        let allIds = <?php echo json_encode($all_ids); ?>;
        let totalCount = <?php echo $total_verifications; ?>;
        let currentIndex = allIds.indexOf(currentId);

        function updateNavigation() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            prevBtn.disabled = currentIndex <= 0;
            nextBtn.disabled = currentIndex >= totalCount - 1;
            document.getElementById('verificationCount').textContent = `Processing request ${currentIndex + 1} out of ${totalCount}`;
        }

        function loadVerificationById(verificationId) {
            fetch('verify_donations.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=get_verification_by_id&verification_id=${verificationId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.verification) {
                    updateVerificationCard(data.verification);
                    currentId = data.verification.id;
                    currentIndex = data.current_index;
                    allIds = data.all_ids;
                    totalCount = allIds.length;
                    updateNavigation();
                } else {
                    console.warn("No verification found for ID", verificationId);
                    alert('No more records available.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading verification data');
            });
        }

        function updateVerificationCard(verification) {
            document.querySelector('.verification-profile-pic').src = verification.donor_image ?
                '../' + verification.donor_image : 'https://randomuser.me/api/portraits/men/32.jpg';
            document.querySelector('.verification-profile-pic').alt = verification.donor_name;

            const createdDate = new Date(verification.created_at);
            document.querySelector('.verification-timestamp span:first-child').textContent =
                `Submitted on: ${createdDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}`;
            document.querySelector('.verification-id').textContent = `#VER-${verification.id.toString().padStart(5, '0')}`;

            const statement = document.querySelector('.verification-statement p');
            const donationDate = new Date(verification.date);
            statement.innerHTML = `I <strong>${verification.donor_name}</strong> (<span class="username">@${verification.donor_username}</span>) give blood donation to <strong>${verification.receiver_name}</strong> (<span class="username">@${verification.receiver_username}</span>) on <span class="donation-verification-date">${donationDate.toLocaleDateString('en-GB')}</span>`;

            document.querySelectorAll('.detail-value')[0].textContent = verification.receiver_contact;
            document.querySelectorAll('.detail-value')[1].textContent = verification.receiver_whatsapp;

            document.getElementById('evidenceImage').src = verification.file ? '../' + verification.file : 'https://via.placeholder.com/120x80';

            // Update last donation info
            const lastDonationDate = verification.last_donation ? new Date(verification.last_donation) : null;
            const daysAgo = lastDonationDate ? Math.floor((new Date() - lastDonationDate) / (1000 * 60 * 60 * 24)) : 'N/A';
            document.querySelector('.last-donation-info .value').textContent = lastDonationDate ? lastDonationDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'Never';
            document.querySelector('.last-donation-info .days-ago').textContent = daysAgo;

            document.querySelector('.verify-btn').onclick = () => verifyDonation(verification.id);
            document.querySelector('.reject-btn').onclick = () => rejectDonation(verification.id);
        }

        document.getElementById('prevBtn').onclick = function() {
            if (currentIndex > 0) {
                loadVerificationById(allIds[currentIndex - 1]);
            }
        };

        document.getElementById('nextBtn').onclick = function() {
            if (currentIndex < totalCount - 1) {
                loadVerificationById(allIds[currentIndex + 1]);
            }
        };

        function verifyDonation(verificationId) {
            if (confirm('Are you sure you want to verify this donation?')) {
                fetch('verify_donations.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=verify_donation&verification_id=${verificationId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Donation verified successfully!');
                        currentIndex = allIds.indexOf(currentId);
                        if (currentIndex < allIds.length - 1) {
                            loadVerificationById(allIds[currentIndex + 1]);
                        } else if (allIds.length > 0) {
                            loadVerificationById(allIds[0]);
                        } else {
                            document.getElementById('donationVerificationSection').innerHTML = `
                                <div class="no-verifications">
                                    <h3>No pending verification requests</h3>
                                    <p>All donation verifications have been processed.</p>
                                </div>
                            `;
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing verification');
                });
            }
        }

        function rejectDonation(verificationId) {
            if (confirm('Are you sure you want to reject this donation?')) {
                fetch('verify_donations.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=reject_donation&verification_id=${verificationId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Donation rejected!');
                        currentIndex = allIds.indexOf(currentId);
                        if (currentIndex < allIds.length - 1) {
                            loadVerificationById(allIds[currentIndex + 1]);
                        } else if (allIds.length > 0) {
                            loadVerificationById(allIds[0]);
                        } else {
                            document.getElementById('donationVerificationSection').innerHTML = `
                                <div class="no-verifications">
                                    <h3>No pending verification requests</h3>
                                    <p>All donation verifications have been processed.</p>
                                </div>
                            `;
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing rejection');
                });
            }
        }

        function viewFullSize() {
            const evidenceImg = document.getElementById('evidenceImage');
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = evidenceImg.src;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        document.getElementById('imageModal').onclick = function(event) {
            if (event.target === this) {
                closeModal();
            }
        };

        // Initialize with the first record
        if (currentId) {
            loadVerificationById(currentId);
        }
    </script>
</body>