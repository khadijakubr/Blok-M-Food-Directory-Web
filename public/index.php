<?php
require __DIR__ . '/../firebase_config.php';

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
</head>
<body>
    <h1>Blok M Food Directory</h1>
    <a href="create.php">+ New Food</a>

    <?php if (empty($foods)): ?>
        <p>No food data available.</p>
    <?php else: ?>
        <div class="card-container">
            <?php foreach ($foods as $id => $food): ?>
                <?php include 'card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>