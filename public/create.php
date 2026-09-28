<?php
require __DIR__ . '/firebase_config.php';

// Set timezone agar konsisten
date_default_timezone_set('Asia/Jakarta');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $foodName = $_POST['food-name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $notes = $_POST['notes'];

    try {
        $newPostRef = $database->getReference('foods')->push([
            'foodName' => $foodName,
            'location' => $location,
            'price' => $price,
            'rating' => $rating,
            'notes' => $notes,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $newFoodId = $newPostRef->getKey();

        header('Location: view.php?id=' . urlencode($newFoodId));
        exit;
    } catch (Exception $e) {
        $message = 'Error adding food: ' . $e->getMessage();
    }
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
    <div>
        <?php include 'navbar.php'; ?>
    </div>
    <div class="container pt-5 page-wrap">
        <h2 class="page-title">ADD NEW FOOD</h2>
        <hr>
        <div class="form-card">
            <form method="POST" class="retro-form">
                <input class="form-control" type="text" name="food-name" placeholder="Food Name" required><br/>
                <input class="form-control" type="text" name="location" placeholder="Location" required><br/>
                <input class="form-control" type="number" name="price" placeholder="Price" required><br/>
                <input class="form-control" type="number" min="0" max="10" step="0.1" name="rating" placeholder="Rating" required><br/>
                <textarea class="form-control" rows="5" name="notes" placeholder="Notes" required></textarea><br/>
                <button type="submit" class="btn btn-primary btn-retro btn-single">Add</button>
            </form>
        </div>
        <?php if ($message): ?>
            <div class="alert alert-danger mt-3">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <br>
        <div class="btn-row">
            <a href="index.php" class="btn btn-secondary btn-retro">Back</a>
        </div>
    </div>
</body>
</html>
