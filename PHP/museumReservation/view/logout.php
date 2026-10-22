<?php
declare(strict_types=1);

session_start();

// Remove all session data.
$_SESSION = [];

// Delete the session cookie if cookies are enabled.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session.
session_destroy();

// Redirect to login.
header("Location: login.php");
exit;
