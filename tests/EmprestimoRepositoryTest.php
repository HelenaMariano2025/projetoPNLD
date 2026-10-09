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
        $matriculaAdministrador = 1234567890;
        $codigoTurma = 99999;

        $codigoLivro = null;
        $codigoEmprestimo = null;

        try {
            $stmtTurma = $this->conn->prepare(
                "INSERT INTO turma (codigo, curso, periodo, serie)
                 VALUES (?, 'Teste', 1, 1)"
            );

            $stmtTurma->bind_param('i', $codigoTurma);
            $this->assertTrue($stmtTurma->execute());
            $stmtTurma->close();

            $stmtAluno = $this->conn->prepare(
                "INSERT INTO aluno
                 (matricula, nome, email, codigoTurma)
                 VALUES (?, 'Aluno de Teste', 'teste@example.com', ?)"
            );

            $stmtAluno->bind_param(
                'ii',
                $matriculaAluno,
                $codigoTurma
            );

            $this->assertTrue($stmtAluno->execute());
            $stmtAluno->close();

            $stmtLivro = $this->conn->prepare(
                "INSERT INTO livro
                 (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
                 VALUES (?, 'Livro de Teste', 'Autor de Teste', 1, 2026, 'ativo', 1, 1)"
            );

            $stmtLivro->bind_param('i', $isbn);
            $this->assertTrue($stmtLivro->execute());

            $codigoLivro = $this->conn->insert_id;
            $stmtLivro->close();

            $stmtEmprestimo = $this->conn->prepare(
                "INSERT INTO emprestimo
                 (codigo_livro, dataEmprestimo, dataDevolucao, matricula_aluno, adm_responsavel)
                 VALUES (?, '2026-10-01', '2026-10-21', ?, ?)"
            );

            $stmtEmprestimo->bind_param(
                'iii',
                $codigoLivro,
                $matriculaAluno,
                $matriculaAdministrador
            );

            $this->assertTrue($stmtEmprestimo->execute());

            $codigoEmprestimo = $this->conn->insert_id;
            $stmtEmprestimo->close();

            $repository = new EmprestimoRepository($this->conn);

            $primeiraDevolucao = $repository->registrarDevolucao(
                $codigoEmprestimo,
                '2026-10-21'
            );

            $segundaDevolucao = $repository->registrarDevolucao(
                $codigoEmprestimo,
                '2026-10-21'
            );

            $this->assertTrue($primeiraDevolucao);
            $this->assertFalse($segundaDevolucao);
        } finally {
            if ($codigoEmprestimo !== null) {
                $stmt = $this->conn->prepare(
                    'DELETE FROM devolucao WHERE codigo_emprestimo = ?'
                );
                $stmt->bind_param('i', $codigoEmprestimo);
                $stmt->execute();
                $stmt->close();

                $stmt = $this->conn->prepare(
                    'DELETE FROM emprestimo WHERE codigo_emprestimo = ?'
                );
                $stmt->bind_param('i', $codigoEmprestimo);
                $stmt->execute();
                $stmt->close();
            }

            if ($codigoLivro !== null) {
                $stmt = $this->conn->prepare(
                    'DELETE FROM livro WHERE codigo = ?'
                );
                $stmt->bind_param('i', $codigoLivro);
                $stmt->execute();
                $stmt->close();
            }

            $stmt = $this->conn->prepare(
                'DELETE FROM aluno WHERE matricula = ?'
            );
            $stmt->bind_param('i', $matriculaAluno);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->conn->prepare(
                'DELETE FROM turma WHERE codigo = ?'
            );
            $stmt->bind_param('i', $codigoTurma);
            $stmt->execute();
            $stmt->close();
        }
    }
}
