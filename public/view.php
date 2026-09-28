<?php
require __DIR__ . '/firebase_config.php';

// Ambil data dari "foods" di Firebase Realtime Database
$id = $_GET['id'] ?? null;
$food = $database->getReference("foods/$id")->getValue();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div>
        <?php include 'navbar.php'; ?>
    </div>
    <div class="container pt-5 page-wrap view-page">
        <?php if ($food): ?>
            <div class="food-item retro-card">
                <h2 class="card-title">
                    <strong>
                    <?= htmlspecialchars($food['foodName']) ?>
                    </strong>
                    <br>
                    <br>
                </h2>

                <p>
                    <span class="rating">⭐ <?= htmlspecialchars($food['rating']) ?> / 10</span>
                </p>

                <p>
                𖡡 <?= htmlspecialchars($food['location']) ?>
                </p>

                <p class="price">
                    Rp <?= number_format($food['price'], 0, ',', '.') ?>
                </p>

                <p>
                    <strong>Notes:</strong><br>
                    <?= htmlspecialchars($food['notes']) ?>
                </p>

                <p class="btn-row">
                    <a href="update.php?id=<?= urlencode($id) ?>" class="btn-retro">Edit</a>
                    <a href="delete.php?id=<?= urlencode($id) ?>"
                    onclick="return confirm('Are you sure you want to delete this food?')" class="btn-retro btn-delete">
                        Delete
                    </a>
                </p>
            </div>

    <?php else: ?>
        <p class="empty-state">No Data Found.</p>
    <?php endif; ?>

        <div class="btn-row">
            <a href="index.php" class="btn-retro">Back</a>
        </div>
    </div>
</body>
</html>
