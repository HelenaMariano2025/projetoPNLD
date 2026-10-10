<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/EmprestimoRepository.php';

class EmprestimoRegistroTest extends TestCase
{
    private mysqli $conn;
    private int $codigoTurma;
    private int $matriculaAluno;
    private int $codigoLivro;
    private int $matriculaAdministrador = 1234567890;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );

        $this->codigoTurma = random_int(90000, 99999);
        $this->matriculaAluno = random_int(20000000, 29999999);

        $stmt = $this->conn->prepare(
            "INSERT INTO turma (codigo, curso, periodo, serie)
             VALUES (?, 'Turma de Teste', 1, 1)"
        );
        $stmt->bind_param('i', $this->codigoTurma);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare(
            "INSERT INTO aluno
             (matricula, nome, email, codigoTurma)
             VALUES (?, 'Aluno de Teste', ?, ?)"
        );

        $email = $this->matriculaAluno . '@teste.com';
        $stmt->bind_param(
            'isi',
            $this->matriculaAluno,
            $email,
            $this->codigoTurma
        );
        $stmt->execute();
        $stmt->close();

        $isbn = random_int(7000000000000, 7999999999999);

        $stmt = $this->conn->prepare(
            "INSERT INTO livro
             (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
             VALUES (?, 'Livro de Teste', 'Autor de Teste', 1, 2026, 'ativo', 1, 1)"
        );
        $stmt->bind_param('i', $isbn);
        $stmt->execute();

        $this->codigoLivro = $this->conn->insert_id;
        $stmt->close();
    }

    protected function tearDown(): void
    {
        if (isset($this->codigoLivro)) {
            $stmt = $this->conn->prepare(
                'DELETE FROM emprestimo WHERE codigo_livro = ?'
            );
            $stmt->bind_param('i', $this->codigoLivro);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->conn->prepare(
                'DELETE FROM livro WHERE codigo = ?'
            );
            $stmt->bind_param('i', $this->codigoLivro);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($this->matriculaAluno)) {
            $stmt = $this->conn->prepare(
                'DELETE FROM aluno WHERE matricula = ?'
            );
            $stmt->bind_param('i', $this->matriculaAluno);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($this->codigoTurma)) {
            $stmt = $this->conn->prepare(
                'DELETE FROM turma WHERE codigo = ?'
            );
            $stmt->bind_param('i', $this->codigoTurma);
            $stmt->execute();
            $stmt->close();
        }

        $this->conn->close();
    }

    public function testRegistraEmprestimoEAtualizaDisponibilidade(): void
    {
        $repository = new EmprestimoRepository($this->conn);

        $resultado = $repository->registrarEmprestimo(
            $this->codigoLivro,
            '2026-10-09',
            '2026-10-29',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );

        $this->assertTrue($resultado);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total
             FROM emprestimo
             WHERE codigo_livro = ? AND matricula_aluno = ?'
        );
        $stmt->bind_param(
            'ii',
            $this->codigoLivro,
            $this->matriculaAluno
        );
        $stmt->execute();

        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(1, (int) $total);

        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $quantidade = $stmt->get_result()->fetch_assoc()['qtde_disponivel'];
        $stmt->close();

        $this->assertSame(0, (int) $quantidade);

        $stmt = $this->conn->prepare(
            'SELECT dataEmprestimo, dataDevolucao, adm_responsavel
             FROM emprestimo
             WHERE codigo_livro = ? AND matricula_aluno = ?'
        );

        $stmt->bind_param(
            'ii',
            $this->codigoLivro,
            $this->matriculaAluno
        );
        $stmt->execute();

        $emprestimo = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $this->assertSame('2026-10-09', $emprestimo['dataEmprestimo']);
        $this->assertSame('2026-10-29', $emprestimo['dataDevolucao']);
        $this->assertSame(
            $this->matriculaAdministrador,
            (int) $emprestimo['adm_responsavel']
        );
    }

    public function testNaoRegistraEmprestimoQuandoLivroEstaIndisponivel(): void
    {
        $stmt = $this->conn->prepare(
            'UPDATE livro SET qtde_disponivel = 0 WHERE codigo = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();
        $stmt->close();

        $repository = new EmprestimoRepository($this->conn);

        $resultado = $repository->registrarEmprestimo(
            $this->codigoLivro,
            '2026-10-09',
            '2026-10-29',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );

        $this->assertFalse($resultado);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total
             FROM emprestimo
             WHERE codigo_livro = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(0, (int) $total);

        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $quantidade = $stmt->get_result()->fetch_assoc()['qtde_disponivel'];
        $stmt->close();

        $this->assertSame(0, (int) $quantidade);
    }

    public function testNaoRegistraEmprestimoParaAlunoInexistente(): void
    {
        $matriculaInexistente = random_int(80000000, 89999999);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total FROM aluno WHERE matricula = ?'
        );
        $stmt->bind_param('i', $matriculaInexistente);
        $stmt->execute();

        $totalAlunos = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(0, (int) $totalAlunos);

        $repository = new EmprestimoRepository($this->conn);

        $resultado = $repository->registrarEmprestimo(
            $this->codigoLivro,
            '2026-10-09',
            '2026-10-29',
            $matriculaInexistente,
            $this->matriculaAdministrador
        );

        $this->assertFalse($resultado);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total
             FROM emprestimo
             WHERE codigo_livro = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $totalEmprestimos = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(0, (int) $totalEmprestimos);

        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $quantidade = $stmt->get_result()->fetch_assoc()['qtde_disponivel'];
        $stmt->close();

        $this->assertSame(1, (int) $quantidade);
    }

    public function testNaoRegistraEmprestimoParaLivroInexistente(): void
    {
        $codigoLivroInexistente = random_int(90000000, 99999999);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $codigoLivroInexistente);
        $stmt->execute();

        $totalLivros = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(0, (int) $totalLivros);

        $repository = new EmprestimoRepository($this->conn);

        $resultado = $repository->registrarEmprestimo(
            $codigoLivroInexistente,
            '2026-10-09',
            '2026-10-29',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );

        $this->assertFalse($resultado);

        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $quantidade = $stmt->get_result()->fetch_assoc()['qtde_disponivel'];
        $stmt->close();

        $this->assertSame(1, (int) $quantidade);
    }

    public function testReverteEstoqueQuandoFalhaAoInserirEmprestimo(): void
    {
        $matriculaInexistente = random_int(1800000000, 1900000000);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total
            FROM administrador
            WHERE matricula = ?'
        );
        $stmt->bind_param('i', $matriculaInexistente);
        $stmt->execute();

        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(0, (int) $total);

        $repository = new EmprestimoRepository($this->conn);

        $resultado = $repository->registrarEmprestimo(
            $this->codigoLivro,
            '2026-10-09',
            '2026-10-29',
            $this->matriculaAluno,
            $matriculaInexistente
        );

        $this->assertFalse($resultado);

        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel
            FROM livro
            WHERE codigo = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $quantidade = $stmt->get_result()->fetch_assoc()['qtde_disponivel'];
        $stmt->close();

        $this->assertSame(1, (int) $quantidade);

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total
            FROM emprestimo
            WHERE codigo_livro = ?'
        );
        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $totalEmprestimos = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        $this->assertSame(0, (int) $totalEmprestimos);
    }
}