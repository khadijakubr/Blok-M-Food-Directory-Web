<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Ambil credentials from environment variable, local file as fallback
$firebaseCredentials = getenv('FIREBASE_CREDENTIALS_JSON');
$databaseUrl = getenv('FIREBASE_DATABASE_URL') ?: 'https://new-cc-project-e26d2-default-rtdb.asia-southeast1.firebasedatabase.app/'; 

if (!$firebaseCredentials) {
     die("Firebase credentials not set in environment variables.");
}

// Decode JSON credentials
$serviceAccount = $firebaseCredentials ? json_decode($firebaseCredentials, true) : __DIR__ . '/../firebase_credentials.json';

// Firebase Configuration (service account from env/file)
$factory = (new Factory)
    ->withServiceAccount($serviceAccount) 
    ->withDatabaseUri($databaseUrl);

$database = $factory->createDatabase();
?>
