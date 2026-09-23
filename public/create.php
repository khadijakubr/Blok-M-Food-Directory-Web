<?php
require __DIR__ . '/../firebase_config.php';

// Set timezone agar konsisten
date_default_timezone_set('Asia/Jakarta');

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

    echo "Data saved!";
}
?>

<h2>Add New Food</h2>
<form method="POST">
    <input type="text" name="food-name" placeholder="Food Name" required><br/>
    <input type="text" name="location" placeholder="Location" required><br/>
    <input type="number" name="price" placeholder="Price" required><br/>
    <input type="number" min="0" max="10" step="0.1" name="rating" placeholder="Rating" required><br/>
    <input type="text" name="notes" placeholder="Notes" required><br/>
    <button type="submit">Add</button>
</form>
<h2>View Food</h2>
<a href="index.php">View All Food</a>