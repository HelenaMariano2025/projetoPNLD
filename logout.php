<?php

session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// cookies gravados pelo login quando o usuário aceita cookies
setcookie('matricula', '', time() - 3600, '/');
setcookie('nome', '', time() - 3600, '/');

session_destroy();

header('Location: login.php');
exit;