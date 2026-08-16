<?php
require_once __DIR__ . '/session.php';

$_SESSION = [];

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

$params = session_get_cookie_params();
setcookie(
    session_name(),
    '',
    [
        'expires' => time() - 3600,
        'path' => $params['path'] ?: '/',
        'secure' => (bool) $params['secure'],
        'httponly' => (bool) $params['httponly'],
        'samesite' => $params['samesite'] ?: 'Lax',
    ]
);

header('Location: login.php');
exit;
