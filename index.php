<?php
require 'check_login.php'; // This ensures only logged-in users can see this page

// session_start();
require_once 'db_config.php'; // Include your database connection file

// Query for Recent Donors (top 5 based on last_donation)
$stmt_recent = $pdo->prepare("
    SELECT d.id, d.full_name, d.profile_picture, ds.last_donation, ds.total_donations
    FROM donors d
    LEFT JOIN donation_summary ds ON d.id = ds.donor_id
    ORDER BY COALESCE(ds.last_donation, '1970-01-01') DESC
    LIMIT 7
");
$stmt_recent->execute();
$recent_donors = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);

// Query for Top Donors (top 5 based on total_donations)
$stmt_top = $pdo->prepare("
    SELECT d.id, d.full_name, d.profile_picture, ds.total_donations
    FROM donors d
    LEFT JOIN donation_summary ds ON d.id = ds.donor_id
    ORDER BY COALESCE(ds.total_donations, 0) DESC, d.id ASC
    LIMIT 7
");
$stmt_top->execute();
$top_donors = $stmt_top->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- --------------------------------------------------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodBridge</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- Include navbar -->
    <?php include 'navbar.php'; ?>



    <!-- ---------------------------------------------------- -- -->
    <div class="outer_container">


        <div class="container">
            <h2>Recent Donors</h2>
            <div class="box">
                <?php
            $rank = 1;
            foreach ($recent_donors as $donor) {
                $imageSrc = $donor['profile_picture'] ? htmlspecialchars($donor['profile_picture']) : 'images/default.jpg';
                $totalDonations = $donor['total_donations'] ?? 0;
                $donationText = $totalDonations === 1 ? 'Donation' : 'Donations';
            ?>
                <div class="list">
                    <div class="imgbx">
                        <img src="<?php echo $imageSrc; ?>" alt="">
                    </div>
                    <div class="content">
                        <h4 class="rank">
                            <small>#</small><?php echo $rank; ?>
                        </h4>
                        <h4><?php echo htmlspecialchars($donor['full_name'] ?? 'Unknown'); ?></h4>
                        <p><i class="bx bxs-donate-blood bx-flip-horizontal"></i> <?php echo $totalDonations; ?>
                            <?php echo $donationText; ?></p>
                    </div>
                </div>
                <?php
                $rank++;
            }
            ?>
            </div>
        </div>

        <div class="container">
            <h2>Top Donors</h2>
            <div class="box">
                <?php
            $rank = 1;
            foreach ($top_donors as $donor) {
                $imageSrc = $donor['profile_picture'] ? htmlspecialchars($donor['profile_picture']) : 'images/default.jpg';
                $totalDonations = $donor['total_donations'] ?? 0;
                $donationText = $totalDonations === 1 ? 'Donation' : 'Donations';
            ?>
                <div class="list">
                    <div class="imgbx">
                        <img src="<?php echo $imageSrc; ?>" alt="">
                    </div>
                    <div class="content">
                        <h4 class="rank">
                            <small>#</small><?php echo $rank; ?>
                        </h4>
                        <h4><?php echo htmlspecialchars($donor['full_name'] ?? 'Unknown'); ?></h4>
                        <p><i class="bx bxs-donate-blood bx-flip-horizontal"></i> <?php echo $totalDonations; ?>
                            <?php echo $donationText; ?></p>
                    </div>
                </div>
                <?php
                $rank++;
            }
            ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

  

    <script src="js/dropdown.js"></script>
</body>

</html>