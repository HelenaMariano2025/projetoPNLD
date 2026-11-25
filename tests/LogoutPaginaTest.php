<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/http.php';

class LogoutPaginaTest extends TestCase
{
    protected function tearDown(): void
    {
        $_SESSION = [];
        $_COOKIE = [];
    }

    public function testLogoutLimpaSessaoERedirecionaParaLogin(): void
    {
        $_SESSION['matricula'] = 20260101;
        $_SESSION['nome'] = 'Administrador Teste';

        $destino = null;

        try {
            include __DIR__ . '/../logout.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
        }

        $this->assertSame('login.php', $destino, 'O logout deve redirecionar para login.php');
        $this->assertSame([], $_SESSION, 'A variável $_SESSION deve ser esvaziada no logout');
    }

    public function testLogoutMesmoSemSessaoPreviaRedirecionaParaLogin(): void
    {
        $_SESSION = [];
        $destino = null;

        try {
            include __DIR__ . '/../logout.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
        }

        $this->assertSame('login.php', $destino);
        $this->assertSame([], $_SESSION);
    }
}
