<?php
session_start();

if (!empty($_SESSION['firebase_uid'])) {
    header('Location: index.php');
    exit;
}

require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$factory = (new Factory)->withServiceAccount(__DIR__ . '/../firebase_credentials.json');
$auth = $factory->createAuth();

$message = '';

//Get user input from form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $user = $auth->createUserWithEmailAndPassword($email, $password);
        $message = "You have registered successfully!";

        session_regenerate_id(true);
        $_SESSION['firebase_uid'] = $user->uid;
        $_SESSION['firebase_email'] = $email;

        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container pt-5 page-wrap view-page">
        <div class="form-card">
            <h1 class="page-title">
                <strong>Register</strong>
            </h1>
            <hr>
            <form method="POST" class="retro-form">
                <input class="form-control" type="email" name="email" placeholder="Email" required><br/>
                <input class="form-control" type="password" name="password" placeholder="Password" required><br/>
                <button type="submit" class="btn btn-primary btn-retro btn-single">Register</button>
            </form>
            <?php if ($message): ?>
            <div class="alert notice mt-3">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>
        </div>
        <hr>
        <div class="page-footer auth-page retro-card">
            <div class="auth-row">
                <span class="auth-text"> Already have an account? </span>
                <a href="login.php" class="auth-link">Login here</a>
            </div>
    
            <div class="auth-divider">Or</div>
    
            <div class="auth-row">
                <span class="auth-text"> Or continue with </span>
                <a href="google_login.php" class="auth-link auth-link--google"><span class="g-badge">G</span>Google</a>
            </div>
        </div>
    </div>
</body>
</html>
