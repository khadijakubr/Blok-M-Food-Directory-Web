<?php
require __DIR__ . '/../firebase_config.php';

// Set timezone agar konsisten
date_default_timezone_set('Asia/Jakarta');

$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $foodName = $_POST['food-name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $notes = $_POST['notes'];

    $newPostRef = $database->getReference('foods')->push([
        'foodName' => $foodName,
        'location' => $location,
        'price' => $price,
        'rating' => $rating,
        'notes' => $notes,
        'created_at' => date('Y-m-d H:i:s'),
    ]);

    $successMessage = "You've added a new food in your directory!";
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
        <h2>Add New Food</h2>
        <form method="POST">
            <input class="form-control" type="text" name="food-name" placeholder="Food Name" required><br/>
            <input class="form-control" type="text" name="location" placeholder="Location" required><br/>
            <input class="form-control" type="number" name="price" placeholder="Price" required><br/>
            <input class="form-control" type="number" min="0" max="10" step="0.1" name="rating" placeholder="Rating" required><br/>
            <textarea class="form-control" rows="5" name="notes" placeholder="Notes" required></textarea><br/>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
        <?php if ($successMessage): ?>
            <div class="alert alert-success mt-3">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
</body>
</html>