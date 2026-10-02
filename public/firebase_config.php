<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Ambil credentials from environment variable, local file as fallback
$firebaseCredentials = getenv('FIREBASE_CREDENTIALS_JSON');
$databaseUrl = getenv('FIREBASE_DATABASE_URL') ?: 'https://new-cc-project-e26d2-default-rtdb.asia-southeast1.firebasedatabase.app/'; 

if ($firebaseCredentials) {
    $serviceAccount = json_decode($firebaseCredentials, true);

    if(!is_array($serviceAccount)) {
        die("Invalid Firebase credentials JSON in environment variable.");
    } 
} else {
    $serviceAccount = __DIR__ . '/../firebase_credentials.json';

    if (!is_file($serviceAccount)) {
        die('Firebase credentials file was not found.');
    }
}

// Firebase Configuration (service account from env/file)
$factory = (new Factory)
    ->withServiceAccount($serviceAccount) 
    ->withDatabaseUri($databaseUrl);

$database = $factory->createDatabase();
$auth = $factory->createAuth();
?>
