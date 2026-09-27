<?php
require __DIR__ . '/firebase_config.php';

$id = $_GET['id'];
$foodRef = $database->getReference("foods/$id");
$food = $foodRef->getValue();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodRef->update([
        'foodName' => $_POST['food-name'],
        'location' => $_POST['location'],
        'price' => (int)$_POST['price'],
        'rating' => (float)$_POST['rating'],
        'notes' => $_POST['notes']
    ]);

    header("Location: view.php?id=" . urlencode($id));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Food Infomation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container pt-5 page-wrap">
        <h2 class="page-title">EDIT FOOD INFORMATION</h2>
        <div class="form-card">
            <form method="POST" class="retro-form">
                <label>Food Name:</label>
                <input class="form-control"type="text" name="food-name" value="<?= htmlspecialchars($food['foodName']) ?>" required>

                <label>Location:</label>
                <input class="form-control"type="text" name="location" value="<?= htmlspecialchars($food['location']) ?>" required>

                <label>Price:</label>
                <input class="form-control" type="number" name="price" value="<?= htmlspecialchars($food['price']) ?>" required>

                <label>Rating:</label>
                <input class="form-control" type="number" min="0" max="10" step="0.1" name="rating" value="<?= htmlspecialchars($food['rating']) ?>" required>

                <label>Notes:</label>
                <textarea class="form-control" rows="5" name="notes" required><?= htmlspecialchars($food['notes']) ?></textarea>

                <button type="submit" class="btn btn-primary btn-retro btn-single">Update</button>
            </form>
        </div>

        <br>
        <div class="btn-row">
            <a href="view.php?id=<?= urlencode($id) ?>" class="btn btn-secondary btn-retro">Back</a>
        </div>
    </div>
</body>
</html>
