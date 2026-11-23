<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../php/http.php';

class HistoricoPaginaTest extends TestCase
{
    private mysqli $conn;
    private int $codigoTurma;
    private int $matriculaAluno;
    private int $codigoLivro;
    private int $codigoEmprestimo;
    private int $matriculaAdmin = 1234567890;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->codigoTurma = random_int(300000, 399999);
        $this->matriculaAluno = random_int(40000000, 49999999);

        // Cria turma
        $stmt = $this->conn->prepare(
            "INSERT INTO turma (codigo, curso, periodo, serie, situacao)
             VALUES (?, 'Curso Histórico', 1, 1, 'ativo')"
        );
        $stmt->bind_param('i', $this->codigoTurma);
        $stmt->execute();
        $stmt->close();

        // Cria aluno
        $stmt = $this->conn->prepare(
            "INSERT INTO aluno (matricula, nome, email, codigoTurma, situacao)
             VALUES (?, 'Aluno Histórico', 'hist@teste.com', ?, 'ativo')"
        );
        $stmt->bind_param('ii', $this->matriculaAluno, $this->codigoTurma);
        $stmt->execute();
        $stmt->close();

        // Cria livro
        $isbn = random_int(7000000000000, 7999999999999);
        $stmt = $this->conn->prepare(
            "INSERT INTO livro (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
             VALUES (?, 'Livro de Histórico', 'Autor H', 1, 2026, 'ativo', 1, 5)"
        );
        $stmt->bind_param('i', $isbn);
        $stmt->execute();
        $this->codigoLivro = (int) $this->conn->insert_id;
        $stmt->close();

        // Cria empréstimo em andamento (data futura)
        $stmt = $this->conn->prepare(
            "INSERT INTO emprestimo (codigo_livro, dataEmprestimo, dataDevolucao, matricula_aluno, adm_responsavel)
             VALUES (?, '2026-11-20', '2026-12-10', ?, ?)"
        );
        $stmt->bind_param('iii', $this->codigoLivro, $this->matriculaAluno, $this->matriculaAdmin);
        $stmt->execute();
        $this->codigoEmprestimo = (int) $this->conn->insert_id;
        $stmt->close();
    }

    protected function tearDown(): void
    {
        $stmt = $this->conn->prepare('DELETE FROM devolucao WHERE codigo_emprestimo = ?');
        $stmt->bind_param('i', $this->codigoEmprestimo);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare('DELETE FROM emprestimo WHERE codigo_emprestimo = ?');
        $stmt->bind_param('i', $this->codigoEmprestimo);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare('DELETE FROM livro WHERE codigo = ?');
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare('DELETE FROM aluno WHERE matricula = ?');
        $stmt->bind_param('i', $this->matriculaAluno);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare('DELETE FROM turma WHERE codigo = ?');
        $stmt->bind_param('i', $this->codigoTurma);
        $stmt->execute();
        $stmt->close();

        $this->conn->close();

        $_SESSION = [];
        $_GET = [];
    }

    private function executarPagina(array $sessao = ['matricula' => 1234567890, 'nome' => 'Admin']): array
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION = $sessao;

        $conn = $this->conn;
        
        $destino = null;
        ob_start();

        try {
            include __DIR__ . '/../historico.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
        } finally {
            $saida = ob_get_clean();
        }

        return ['saida' => $saida, 'destino' => $destino];
    }

    public function testExigeAutenticacaoParaAcessarHistorico(): void
    {
        $resultado = $this->executarPagina([]);

        $this->assertSame('login.php', $resultado['destino'], 'Acesso sem login deve redirecionar para login.php');
    }

    public function testExibeTabelaEColunasDoHistoricoComEmprestimo(): void
    {
        $resultado = $this->executarPagina();

        $this->assertNull($resultado['destino']);
        $this->assertStringContainsString('Histórico de empréstimos', $resultado['saida']);
        $this->assertStringContainsString('Relatório de empréstimos', $resultado['saida']);
        $this->assertStringContainsString('Livro de Histórico', $resultado['saida']);
        $this->assertStringContainsString('Aluno Histórico', $resultado['saida']);
        $this->assertStringContainsString((string) $this->matriculaAluno, $resultado['saida']);
        $this->assertStringContainsString('Em andamento', $resultado['saida']);
        $this->assertStringContainsString('badge-warning', $resultado['saida']);
    }

    public function testExibeStatusDevolvidoQuandoDevolucaoFoiRegistrada(): void
    {
        // Insere devolução para o empréstimo
        $stmt = $this->conn->prepare(
            "INSERT INTO devolucao (codigo_emprestimo, dataDevolucao)
             VALUES (?, '2026-11-25')"
        );
        $stmt->bind_param('i', $this->codigoEmprestimo);
        $stmt->execute();
        $stmt->close();

        $resultado = $this->executarPagina();

        $this->assertStringContainsString('Devolvido', $resultado['saida']);
        $this->assertStringContainsString('badge-success', $resultado['saida']);
        $this->assertStringContainsString('25/11/2026', $resultado['saida']);
    }

    public function testExibeStatusAtrasadoQuandoVencidoESemDevolucao(): void
    {
        // Atualiza a data de devolução prevista para uma data no passado
        $stmt = $this->conn->prepare(
            "UPDATE emprestimo SET dataDevolucao = '2020-01-01' WHERE codigo_emprestimo = ?"
        );
        $stmt->bind_param('i', $this->codigoEmprestimo);
        $stmt->execute();
        $stmt->close();

        $resultado = $this->executarPagina();

        $this->assertStringContainsString('Atrasado', $resultado['saida']);
        $this->assertStringContainsString('badge-danger', $resultado['saida']);
    }
}
