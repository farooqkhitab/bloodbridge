<?php
if (isset($_SESSION['user_id'], $_SESSION['refresh_profile_pic']) && $_SESSION['refresh_profile_pic'] === true) {
    $stmt = $pdo->prepare("SELECT profile_picture FROM donors WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $newPic = $stmt->fetchColumn();

    if ($newPic) {
        $_SESSION['profile_picture'] = $newPic;
    }

    unset($_SESSION['refresh_profile_pic']); // ✅ remove the flag
}
