<!-- sidebar.php -->
<div class="sidebar">
    <div class="logo">
        <h2><i class="fas fa-heartbeat"></i> BloodBridge</h2>
    </div>
    <ul class="menu">
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_donors.php' ? 'active' : ''; ?>">
            <a href="manage_donors.php"><i class="fas fa-users"></i> Manage Donors</a>
        </li>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'verify_donations.php' ? 'active' : ''; ?>">
            <a href="verify_donations.php"><i class="fas fa-clipboard-check"></i> Donation Verification</a>
        </li>
        <li class="logout">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>
