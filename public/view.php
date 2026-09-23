<?php
require __DIR__ . '/../firebase_config.php';

// Ambil data dari "foods" di Firebase Realtime Database
$id = $_GET['id'] ?? null;
$food = $database->getReference("foods/$id")->getValue();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Directory</title>
</head>
<body>
    <?php if ($food): ?>
        <div class="food-item">
            <p>
                <strong>
                <?= htmlspecialchars($food['foodName']) ?>
                </strong><br>
            </p>

            <p>
                ⭐ <?= htmlspecialchars($food['rating']) ?> / 10
            </p>

            <p>
               𖡡 <?= htmlspecialchars($food['location']) ?>
            </p>

            <p>
                Rp <?= number_format($food['price'], 0, ',', '.') ?>
            </p>

            <p>
                <strong>Notes:</strong><br>
                <?= htmlspecialchars($food['notes']) ?>
            </p>

            <p>
                <a href="update.php?id=<?= urlencode($id) ?>">Edit</a>
                |
                <a href="delete.php?id=<?= urlencode($id) ?>"
                   onclick="return confirm('Are you sure you want to delete this food?')">
                    Delete
                </a>
            </p>
        </div>

        <hr>
<?php else: ?>
    <p>No Data Found.</p>
<?php endif; ?>

    <br>
    <a href="index.php">Back</a>
</body>
</html>
