<?php

session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $p['path'],
        'domain' => $p['domain'],
        'secure' => $p['secure'],
        'httponly' => $p['httponly'],
        'samesite' => $p['samesite'] ?? 'Lax',
    ]);
}

// cookies gravados pelo login quando o usuário aceita cookies
$opcoesCookie = [
    'expires' => time() - 3600,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
];

setcookie('matricula', '', $opcoesCookie);
setcookie('nome', '', $opcoesCookie);

session_destroy();

header('Location: login.php');
exit;
