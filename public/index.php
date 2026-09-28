<?php
#session use so that the user needs to login first before accessing the index page
session_start();

if (empty($_SESSION['firebase_uid'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/firebase_config.php';

// Ambil semua data foods
$foods = $database->getReference('foods')->getValue();
if ($foods) {
    uasort($foods, function ($foodA, $foodB) {
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
