<?php
require_once 'auth_check.php';
require_once '../db_config.php'; // adjust path if needed

// Check admin login (you can customize this further)
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Example stats from DB (Replace with actual queries)
$totalDonors = $pdo->query("SELECT COUNT(*) FROM donors")->fetchColumn();
$eligibleDonors = $pdo->query("SELECT COUNT(*) FROM donors WHERE status = 1")->fetchColumn();
$ineligibleDonors = $pdo->query("SELECT COUNT(*) FROM donors WHERE status = 0")->fetchColumn();
$donationsLast30Days = $pdo->query("
    SELECT COUNT(*) 
    FROM donation_summary 
    WHERE last_donation >= CURDATE() - INTERVAL 30 DAY
")->fetchColumn();


// Blood group counts
$bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
$bloodCounts = [];
foreach ($bloodGroups as $group) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM donors WHERE blood_group = ?");
    $stmt->execute([$group]);
    $bloodCounts[$group] = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Create this stylesheet with your provided CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

     <!-- favicon -->
     <link rel="icon" type="image/png" href="favicon/favicon.svg">
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

            <div class="dashboard">
                <h2>Dashboard Overview</h2>
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                        <div class="stat-details">
                            <h3>Total Donors</h3>
                            <p class="stat-number"><?php echo $totalDonors; ?></p>
                            
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-details">
                            <h3>Eligible Donors</h3>
                            <p class="stat-number"><?php echo $eligibleDonors; ?></p>
                       
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                        <div class="stat-details">
                            <h3>Ineligible Donors</h3>
                            <p class="stat-number"><?php echo $ineligibleDonors; ?></p>
                            
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-tint"></i></div>
                        <div class="stat-details">
                            <h3>Donations This Month</h3>
                            <p class="stat-number"><?php echo $donationsLast30Days; ?></p>
                            
                        </div>
                    </div>
                </div>

                <div class="charts-row">
                    <div class="chart-container">
                        <h3>Blood Group Distribution</h3>
                        <canvas id="bloodGroupChart"></canvas>
                    </div>


                    <div class="blood-groups-section">
                        <h3>Blood Group Statistics</h3>
                        <div class="blood-groups-container">
                            <?php foreach ($bloodCounts as $group => $count): ?>
                            <div class="blood-group-card">
                                <h4><?php echo $group; ?></h4>
                                <p class="count"><?php echo $count; ?></p>
                                <div class="stock-level <?php 
                  echo $count > 200 ? 'full' : ($count > 100 ? 'medium' : 'low'); ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <script>
    const ctx1 = document.getElementById('bloodGroupChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($bloodCounts)); ?>,
            datasets: [{
                label: 'Donors',
                data: <?php echo json_encode(array_values($bloodCounts)); ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#AA65FF', '#F85F36', '#2ecc71', '#f1c40f',
                    '#1abc9c'
                ]
            }]
        }
    });
    </script>
</body>

</html>