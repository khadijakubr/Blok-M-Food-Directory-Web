<?php
$firebaseWebConfig = []; 
$webConfigJson = getenv('FIREBASE_WEB_CONFIG_JSON'); 
$decoded = json_decode($webConfigJson, true); 
if (is_array($decoded) && !empty($decoded)) { 
    $firebaseWebConfig = $decoded; 
    }
?>