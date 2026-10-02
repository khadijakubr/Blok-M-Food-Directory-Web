<?php
#session use so that the user needs to login first before accessing the index page
session_start();

if (empty($_SESSION['firebase_uid'])) { // Check if user is logged in
    header('Location: login.php'); 
    exit;
}

require __DIR__ . '/firebase_config.php'; // Load Firebase configuration

// Re-check Firebase Auth by UID so a stale PHP session cannot bypass verification.
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

// Ambil semua data foods
$foods = $database->getReference('foods')->getValue(); // Fetch all food data from database
if ($foods) {
    uasort($foods, function ($foodA, $foodB) { // Sort foods by creation date (newest first)
        return strcmp(
            $foodB['created_at'] ?? '',
            $foodA['created_at'] ?? ''
        );
    });
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blok M Food Directory</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container page-wrap">
        <div class="row page-head">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <img
                    src="assets/images/food-directory-title.png"
                    alt="Blok M Food Directory"
                    class="page-title-image"
                >
                <div>
                    <a href="create.php" class="btn btn-primary btn-retro">+ New Food</a>
                    <a href="settings.php" class="btn btn-secondary btn-retro">⚙</a>
                </div>
            </div>
        </div>

        <?php if (empty($foods)): ?>
            <p class="empty-state">No food data available.</p>
        <?php else: ?>
            <div class="container py-4 food-grid">
                <div class="row g-4">
                    <?php foreach ($foods as $id => $food): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <?php include 'card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
