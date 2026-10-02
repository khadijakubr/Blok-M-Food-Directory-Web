<?php
session_start(); // Start session to manage user state
if (empty($_SESSION['firebase_uid'])) { // Check if user is logged in
    header('Location: login.php'); // Redirect to login if not logged in
    exit; 
}

require __DIR__ . '/firebase_config.php'; // Load Firebase Auth for the authoritative verification status.

// Check verification by session UID, matching the index page and avoiding email-hash lookups.
try {
    $firebaseUser = $auth->getUser($_SESSION['firebase_uid']);
} catch (Throwable $e) {
    $firebaseUser = null;
}

if (!$firebaseUser || !$firebaseUser->emailVerified) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    header('Location: login.php?error=unverified');
    exit;
}

// only for display
$email = $_SESSION['firebase_email'] ?? '-'; // Get email from session for display
$uid = $_SESSION['firebase_uid'] ?? '-'; // Get UID from session for display
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
