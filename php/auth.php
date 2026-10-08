<?php

function exigirAutenticacao()
{
    if (!isset($_SESSION["matricula"])) {
        header("Location: login.php");
        exit();
    }
}