<?php

require_once __DIR__ . '/php/http.php';
iniciarSessao();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// cookies gravados pelo login quando o usuário aceita cookies
setcookie('matricula', '', time() - 3600, '/');
setcookie('nome', '', time() - 3600, '/');

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

redirecionar('login.php');
