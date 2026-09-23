<?php
require __DIR__ . '/../firebase_config.php';

$id = $_GET['id'];
$database->getReference("foods/$id")->remove();
header("Location: index.php");
?>
