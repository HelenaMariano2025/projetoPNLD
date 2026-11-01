<?php

class RedirecionamentoException extends RuntimeException
{
    public function __construct(public readonly string $destino)
    {
        parent::__construct('Redirecionamento para ' . $destino);
    }
}

function iniciarSessao(): void
{
    if (!defined('PNLD_TESTES') && session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function redirecionar(string $destino): never
{
    if (defined('PNLD_TESTES')) {
        throw new RedirecionamentoException($destino);
    }

    header('Location: ' . $destino);
    exit();
}

function definirCookie(string $nome, string $valor): void
{
    if (defined('PNLD_TESTES')) {
        return;
    }

    setcookie($nome, $valor, [
        'expires'  => time() + 604800,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
