<?php
session_start(); 

if (!empty($_SESSION['firebase_uid'])) { 
    header('Location: index.php');
    exit;
}

require __DIR__ . '/../vendor/autoload.php'; 
require __DIR__ . '/../firebase_web_config.php'; 
require __DIR__ . '/firebase_config.php';

$googleConfigured = !empty($firebaseWebConfig['apiKey']) && !empty($firebaseWebConfig['projectId']); // Check if Google auth is configured

$message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (empty($_POST['idToken'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Google ID token was not provided.']);
        exit;
    }

    try {
        $verifiedToken = $auth->verifyIdToken($_POST['idToken']); // Verify the browser token on the server.
        $uid = $verifiedToken->claims()->get('sub');
        $firebaseUser = $auth->getUser($uid);

        // Use Firebase Auth's verified status; 
        if (!$firebaseUser->emailVerified) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'This Google account email is not verified.']);
            exit;
        }

        $email = $firebaseUser->email ?? '';
        $userRef = $database->getReference('users/' . $uid);
        $userData = $userRef->getValue();

        // Mirror the verified status under UID after confirming it against Firebase Auth above.
        if (!$userData) {
            $userRef->set([
                'email' => $email,
                'uid' => $uid,
                'verified' => true,
                'created_at' => date('c'),
                'provider' => 'google',
            ]);
        } else {
            $userRef->update(['email' => $email, 'uid' => $uid, 'verified' => true]);
        }

        session_regenerate_id(true);
        $_SESSION['firebase_uid'] = $uid;
        $_SESSION['firebase_email'] = $email;
        echo json_encode(['success' => true]);
        exit;
    } catch (Throwable $e) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Google sign-in could not be verified. Please try again.']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Continue with Google</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container pt-5 page-wrap view-page">
        <div class="form-card">
            <h1 class="page-title">
                <strong>Continue with Google</strong>
            </h1>
            <hr>
            <p class="text-center">Sign in with your Google account to open the food directory.</p>
            <button id="googleBtn" type="button" class="btn btn-primary btn-retro btn-single"><span class="g-badge">G</span> Continue with Google</button>
            <?php if ($message): ?>
            <div class="alert notice mt-3">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>
            <p id="googleStatus" class="text-center" style="display:none;"></p>
        </div>
        <hr>
        <div class="page-footer auth-page retro-card">
            <div class="auth-row">
                <span class="auth-text"> Prefer email instead? </span>
                <a href="login.php" class="auth-link">Back to login</a>
            </div>
        </div>
    </div>

    <!-- Firebase Web SDK popup flow -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.19.0/firebase-app.js";
        import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/12.19.0/firebase-auth.js";

        const firebaseConfig = <?php echo json_encode($firebaseWebConfig, JSON_UNESCAPED_SLASHES); ?>;
        const googleConfigured = <?php echo $googleConfigured ? 'true' : 'false'; ?>; 
        if (!googleConfigured) { 
            document.getElementById('googleStatus').style.display = 'block'; 
            document.getElementById('googleStatus').textContent = 'Google sign-in is not configured (FIREBASE_WEB_CONFIG_JSON missing).'; 
            document.getElementById('googleBtn').disabled = true; 
            throw new Error('Missing FIREBASE_WEB_CONFIG_JSON'); 
        }
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        const btn = document.getElementById('googleBtn');
        const status = document.getElementById('googleStatus');

        btn.addEventListener('click', async () => {
            status.style.display = 'block'; 
            status.textContent = 'Opening Google…';
            btn.disabled = true; 
            try {
                const result = await signInWithPopup(auth, provider); 
                status.textContent = 'Verifying…';
                const idToken = await result.user.getIdToken(true); 
                const form = new FormData(); 
                form.append('idToken', idToken);

                const res = await fetch('google_login.php', { method: 'POST', body: form });
                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Verification failed.');
                }
                window.location.href = 'index.php';
            } catch (err) {
                status.textContent = 'Google sign-in failed: ' + (err?.message || err); 
                btn.disabled = false; 
            }
        });
    </script>
</body>
</html>
