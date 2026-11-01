<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/http.php';

class LoginPaginaTest extends TestCase
{
    private mysqli $conn;
    private int $matricula;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->matricula = random_int(100000000000, 999999999999);
        $matricula = $this->matricula;
        $nome = 'Admin Teste Login';
        $senha = 'senha123';

        $stmt = $this->conn->prepare(
            'INSERT INTO administrador (matricula, nome, senha)
             VALUES (?, ?, ?)'
        );
        $stmt->bind_param('iss', $matricula, $nome, $senha);
        $stmt->execute();
        $stmt->close();
    }

    protected function tearDown(): void
    {
        $matricula = $this->matricula;

        $stmt = $this->conn->prepare(
            'DELETE FROM administrador WHERE matricula = ?'
        );
        $stmt->bind_param('i', $matricula);
        $stmt->execute();
        $stmt->close();

        $this->conn->close();

        $_POST = [];
        $_COOKIE = [];
        $_SESSION = [];
    }

    private function executarPagina(
        string $metodo,
        array $post = [],
        array $cookies = []
    ): array {
        $_SERVER['REQUEST_METHOD'] = $metodo;
        $_POST = $post;
        $_COOKIE = $cookies;
        $_SESSION = [];

        $conn = $this->conn;
        $destino = null;

        ob_start();

        try {
            include __DIR__ . '/../login.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
        } finally {
            $saida = ob_get_clean();
        }

        return ['saida' => $saida, 'destino' => $destino];
    }

    public function testExibeFormularioNoAcessoNormal(): void
    {
        $resultado = $this->executarPagina('GET');

        $this->assertStringContainsString('name="matricula"', $resultado['saida']);
        $this->assertStringContainsString('name="senha"', $resultado['saida']);
        $this->assertNull($resultado['destino']);
    }

    public function testExigeMatriculaESenha(): void
    {
        $resultado = $this->executarPagina('POST', [
            'matricula' => '',
            'senha' => '',
        ]);

        $this->assertStringContainsString(
            'Matrícula e senha são obrigatórias.',
            $resultado['saida']
        );
        $this->assertNull($resultado['destino']);
    }

    public function testRejeitaSenhaIncorreta(): void
    {
        $resultado = $this->executarPagina('POST', [
            'matricula' => (string) $this->matricula,
            'senha' => 'senhaErrada',
        ]);

        $this->assertStringContainsString(
            'Matrícula ou senha inválida.',
            $resultado['saida']
        );
        $this->assertNull($resultado['destino']);
        $this->assertSame([], $_SESSION);
    }

    public function testRejeitaMatriculaInexistente(): void
    {
        $resultado = $this->executarPagina('POST', [
            'matricula' => '1',
            'senha' => 'senha123',
        ]);

        $this->assertStringContainsString(
            'Matrícula ou senha inválida.',
            $resultado['saida']
        );
        $this->assertNull($resultado['destino']);
    }

    public function testLoginValidoRedirecionaParaFuncoes(): void
    {
        $resultado = $this->executarPagina('POST', [
            'matricula' => (string) $this->matricula,
            'senha' => 'senha123',
        ]);

        $this->assertSame('funcoes.php', $resultado['destino']);
        $this->assertEquals($this->matricula, $_SESSION['matricula']);
        $this->assertSame('Admin Teste Login', $_SESSION['nome']);
    }

    public function testLoginValidoComCookiesAceitos(): void
    {
        $resultado = $this->executarPagina(
            'POST',
            [
                'matricula' => (string) $this->matricula,
                'senha' => 'senha123',
            ],
            ['cookies_aceitos' => 'true']
        );

        $this->assertSame('funcoes.php', $resultado['destino']);
        $this->assertEquals($this->matricula, $_SESSION['matricula']);
    }
}