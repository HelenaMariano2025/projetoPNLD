<?php

require_once __DIR__ . '/http.php';

function exigirAutenticacao()
{
    if (!isset($_SESSION["matricula"])) {
        redirecionar("login.php");
    }
}
