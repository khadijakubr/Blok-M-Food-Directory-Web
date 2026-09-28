<?php
session_start(); 
if (empty($_SESSION['firebase_uid'])) { 
    header('Location: login.php'); 
    exit; 
}

// only for display
$email = $_SESSION['firebase_email'] ?? '-'; 
$uid = $_SESSION['firebase_uid'] ?? '-'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div><?php include 'navbar.php'; ?></div>
    <div class="container pt-5 page-wrap settings-page">
        <div class="form-card">
            <h2 class="page-title">USER SETTINGS</h2>
            <hr>
            <div class="settings-list">
                <div class="settings-row"><strong>Email:</strong> <?= htmlspecialchars($email) ?></div>
                <div class="settings-row"><strong>User ID:</strong> <?= htmlspecialchars($uid) ?></div>
            </div>
            <div class="btn-row">
                <a href="logout.php" class="btn btn-primary btn-retro btn-single" onclick="return confirm('Log out?')">Logout</a>
            </div>
        </div>
        <br>
        <div class="btn-row">
            <a href="index.php" class="btn btn-secondary btn-retro">Back</a>
        </div>
    </div>
</body>
</html>
