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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container pt-5">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                <h1>Blok M Food Directory</h1>
                <a href="create.php" class="btn btn-primary">+ New Food</a>
            </div>
        </div> 

        <?php if (empty($foods)): ?>
            <p>No food data available.</p>
        <?php else: ?>
            <div class="container py-4">
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