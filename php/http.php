<?php

class RedirecionamentoException extends RuntimeException
{
    public function __construct(
        public readonly string $destino,
        public readonly int $status = 302
    ) {
        parent::__construct('Redirecionamento para ' . $destino);
    }
}

class EncerramentoException extends RuntimeException
{
    public function __construct(
        public readonly string $mensagem,
        public readonly int $status
    ) {
        parent::__construct($mensagem);
    }
}

function iniciarSessao(): void
{
    if (!defined('PNLD_TESTES') && session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function redirecionar(string $destino, int $status = 302): never
{
    if (defined('PNLD_TESTES')) {
        throw new RedirecionamentoException($destino, $status);
    }

    header('Location: ' . $destino, true, $status);
    exit();
}

function encerrar(string $mensagem, int $status, array $cabecalhos = []): never
{
    if (defined('PNLD_TESTES')) {
        throw new EncerramentoException($mensagem, $status);
    }

    foreach ($cabecalhos as $cabecalho) {
        header($cabecalho);
    }

    http_response_code($status);
    exit($mensagem);
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
