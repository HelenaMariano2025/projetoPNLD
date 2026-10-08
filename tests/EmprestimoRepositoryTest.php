<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/EmprestimoRepository.php';

class EmprestimoRepositoryTest extends TestCase
{
    private mysqli $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );

        if ($this->conn->connect_error) {
            $this->fail(
                'Falha na conexão com o banco: ' . $this->conn->connect_error
            );
        }
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testNaoPermiteRegistrarMesmaDevolucaoDuasVezes(): void
    {
        $isbn = 9999999999999;
        $matriculaAluno = 20212021;
        $matriculaAdministrador = 20250101;

        // Cria livro temporário para o teste.
        $sqlLivro = "INSERT INTO livro
            (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
            VALUES
            ($isbn, 'Livro de Teste', 'Autor de Teste', 1, 2026, 'ativo', 1, 1)";

        $this->assertTrue($this->conn->query($sqlLivro));

        $codigoLivro = $this->conn->insert_id;

        // Cria empréstimo temporário.
        $sqlEmprestimo = "INSERT INTO emprestimo
            (codigo_livro, dataEmprestimo, dataDevolucao, matricula_aluno, adm_responsavel)
            VALUES
            ($codigoLivro, '2026-10-01', '2026-10-21', $matriculaAluno, $matriculaAdministrador)";

        $this->assertTrue($this->conn->query($sqlEmprestimo));

        $codigoEmprestimo = $this->conn->insert_id;

        $repository = new EmprestimoRepository($this->conn);

        $primeiraDevolucao = $repository->registrarDevolucao(
            $codigoEmprestimo,
            '2026-10-01'
        );

        $segundaDevolucao = $repository->registrarDevolucao(
            $codigoEmprestimo,
            '2026-10-01'
        );

        $this->assertTrue($primeiraDevolucao);
        $this->assertFalse($segundaDevolucao);

        // Limpeza dos dados criados pelo teste.
        $this->conn->query(
            "DELETE FROM devolucao WHERE codigo_emprestimo = $codigoEmprestimo"
        );

        $this->conn->query(
            "DELETE FROM emprestimo WHERE codigo_emprestimo = $codigoEmprestimo"
        );

        $this->conn->query(
            "DELETE FROM livro WHERE codigo = $codigoLivro"
        );
    }
}
