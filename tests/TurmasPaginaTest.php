<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../php/http.php';

class TurmasPaginaTest extends TestCase
{
    private mysqli $conn;
    private int $codigoTurma;
    private string $nomeCurso;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->codigoTurma = random_int(200000, 299999);
        $this->nomeCurso = 'Curso ' . $this->codigoTurma;

        $codigo = $this->codigoTurma;
        $curso = $this->nomeCurso;

        $stmt = $this->conn->prepare(
            "INSERT INTO turma
             (codigo, curso, siglaCurso, periodo, serie,
              matrizCurricular, situacao)
             VALUES (?, ?, 'SIG', 1, 2, 'Matriz de teste', 'ativo')"
        );
        $stmt->bind_param('is', $codigo, $curso);
        $stmt->execute();
        $stmt->close();
    }

    protected function tearDown(): void
    {
        $codigo = $this->codigoTurma;

        $stmt = $this->conn->prepare('DELETE FROM turma WHERE codigo = ?');
        $stmt->bind_param('i', $codigo);
        $stmt->execute();
        $stmt->close();

        $this->conn->close();

        $_GET = [];
        $_SESSION = [];
    }

    private function executarPagina(
        array $get = [],
        array $sessao = ['matricula' => 1234567890]
    ): array {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = $get;
        $_SESSION = $sessao;

        $conn = $this->conn;
        $destino = null;

        ob_start();

        try {
            include __DIR__ . '/../turmas.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
        } finally {
            $saida = ob_get_clean();
        }

        return ['saida' => $saida, 'destino' => $destino];
    }

    public function testRedirecionaParaLoginSemAutenticacao(): void
    {
        $resultado = $this->executarPagina([], []);

        $this->assertSame('login.php', $resultado['destino']);
    }

    public function testListaTurmasAtivasComLinksDeEdicaoEExclusao(): void
    {
        $resultado = $this->executarPagina();

        $this->assertNull($resultado['destino']);
        $this->assertStringContainsString($this->nomeCurso, $resultado['saida']);
        $this->assertStringContainsString(
            'editar_turma.php?codigo=' . $this->codigoTurma,
            $resultado['saida']
        );
        $this->assertStringContainsString(
            'excluir_turma.php?codigo=' . $this->codigoTurma,
            $resultado['saida']
        );
        $this->assertStringContainsString(
            'carousel-item active',
            $resultado['saida']
        );
    }

    public function testPesquisaTurmaPeloNomeDoCurso(): void
    {
        $resultado = $this->executarPagina(['curso' => $this->nomeCurso]);

        $this->assertStringContainsString($this->nomeCurso, $resultado['saida']);
    }

    public function testInformaQuandoNaoEncontraTurmaNaPesquisa(): void
    {
        $resultado = $this->executarPagina(
            ['curso' => 'curso-que-nao-existe-xyz']
        );

        $this->assertStringContainsString(
            'Nenhuma turma encontrada para o curso pesquisado.',
            $resultado['saida']
        );
    }

    public function testExibeMensagemDaSessaoUmaUnicaVez(): void
    {
        $resultado = $this->executarPagina([], [
            'matricula' => 1234567890,
            'mensagem' => 'Turma atualizada com sucesso.',
        ]);

        $this->assertStringContainsString(
            'Turma atualizada com sucesso.',
            $resultado['saida']
        );
        $this->assertArrayNotHasKey('mensagem', $_SESSION);
    }

    public function testEscapaCaracteresEspeciaisDoCurso(): void
    {
        $codigo = $this->codigoTurma;

        $stmt = $this->conn->prepare(
            "UPDATE turma SET curso = '<b>x</b>' WHERE codigo = ?"
        );
        $stmt->bind_param('i', $codigo);
        $stmt->execute();
        $stmt->close();

        $resultado = $this->executarPagina(['curso' => '<b>']);

        $this->assertStringContainsString(
            '&lt;b&gt;x&lt;/b&gt;',
            $resultado['saida']
        );
        $this->assertStringNotContainsString(
            '<h6><b>x</b>',
            $resultado['saida']
        );
    }
}