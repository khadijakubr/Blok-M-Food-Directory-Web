<?php
require __DIR__ . '/../firebase_config.php';

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

    header("Location: view.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Food</title>
</head>
<body>
    <h2>Edit</h2>
    <form method="POST">
        <label>Food Name:</label>
        <input type="text" name="food-name" value="<?= htmlspecialchars($food['foodName']) ?>" required><br><br>

        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($food['location']) ?>" required><br><br>

        <label>Price:</label>
        <input type="number" name="price" value="<?= htmlspecialchars($food['price']) ?>" required><br><br>

        <label>Rating:</label>
        <input type="number" name="rating" value="<?= htmlspecialchars($food['rating']) ?>" required><br><br>

        <label>Notes:</label>
        <input type="text" name="notes" value="<?= htmlspecialchars($food['notes']) ?>" required><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="index.php">Back</a>
</body>
</html>
