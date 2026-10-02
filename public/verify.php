<?php

require __DIR__ . '/firebase_config.php';

$message = ''; 
$verified = false; // Track verification status

// Firebase's action handler verifies the signed code before redirecting here with the user's UID.
if (!empty($_GET['uid'])) {
    try {
        // Confirm Firebase Auth's state by UID; do not trust an email or UID URL parameter to mark verified.
        $firebaseUser = $auth->getUser($_GET['uid']);

        if ($firebaseUser->emailVerified) {
            // Store a UID-keyed mirror only after Firebase Auth confirms verification.
            $database->getReference('users/' . $firebaseUser->uid)->update([
                'email' => $firebaseUser->email ?? '',
                'uid' => $firebaseUser->uid,
                'verified' => true,
            ]);
            $verified = true;
            $message = 'Your account has been verified successfully! You can now log in.';
        } else {
            $message = 'Firebase has not confirmed this email yet. Open the verification link from your inbox.';
        }
    } catch (Exception $e) {
        $message = 'Verification could not be confirmed. Please request a new verification email.';
    }
} else {
    $message = 'Invalid verification link. The user ID is missing.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container pt-5 page-wrap view-page">
        <div class="form-card">
            <h1 class="page-title">
                <strong>Email Verification</strong>
            </h1>
            <hr>
            <!-- CHANGED: Unified alert block to match login.php/register.php | WHY: alert-success/alert-danger are Bootstrap variants (alert-danger undefined in style.css) that override retro cream style | FUNCTION: shows message in same retro .notice box as other auth pages -->
            <div class="alert notice mt-3"> <!-- CHANGED: class simplified from "alert notice mt-3 alert-success/alert-danger" to "alert notice mt-3" | WHY: match login.php/register.php exactly | FUNCTION: consistent retro message box -->
                <?= htmlspecialchars($message) ?>
            </div>
            <?php if ($verified): ?>
                <script>
                    setTimeout(function() {
                        window.location.href = 'login.php?verified=1';
                    }, 3000);
                </script>
                <!-- CHANGED: Redirect hint re-styled to auth footer style | WHY: text-muted is Bootstrap grey never used in auth pages | FUNCTION: centered hint text consistent with other pages -->
                <p class="text-center"> <!-- CHANGED: removed text-muted, kept text-center (.text-center is defined in style.css) | WHY: match other pages centered text without Bootstrap grey | FUNCTION: centers redirect hint -->
                    <span class="auth-text">Redirecting to login in 3 seconds...</span> <!-- CHANGED: wrapped hint in auth-text span | WHY: auth-text is the standard helper-text style in auth footer | FUNCTION: styles hint like other auth helper text --> <a href="login.php?verified=1" class="auth-link">Click here if not redirected</a> <!-- CHANGED: added auth-link class to redirect link | WHY: auth-link is the yellow retro pill link used in login/register footers | FUNCTION: makes fallback link look identical to other auth links -->
                </p>
            <?php else: ?>
                <!-- CHANGED: Failure buttons container switched to btn-row | WHY: btn-row is the standard 2-button retro row (settings/view pages) with gap handling | FUNCTION: lays Register/Login side-by-side and wraps on mobile like other pages -->
                <div class="btn-row"> <!-- CHANGED: class from "text-center mt-3" to "btn-row" | WHY: reuse existing responsive button-row layout instead of ad-hoc centering | FUNCTION: consistent button layout -->
                    <a href="register.php" class="btn btn-primary btn-retro">Register</a> <!-- CHANGED: kept classes, now inside btn-row for equal flex sizing | WHY: btn-row children get flex:1 for even widths | FUNCTION: Register button matching other pages -->
                    <a href="login.php" class="btn btn-secondary btn-retro">Login</a> <!-- CHANGED: removed ms-2 Bootstrap margin | WHY: btn-row already provides gap, ms-2 caused uneven spacing | FUNCTION: Login button matching other pages -->
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