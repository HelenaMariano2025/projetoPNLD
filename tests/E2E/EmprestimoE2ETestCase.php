<?php

use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/E2ETestCase.php';

abstract class EmprestimoE2ETestCase extends E2ETestCase
{
    protected mysqli $conn;

    protected int $codigoTurma;

    protected int $matriculaAluno;

    protected int $codigoLivro;

    protected string $isbn;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'db',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: 'pnld_local',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->conn->set_charset('utf8mb4');

        $this->codigoTurma = random_int(10000000, 99999999);
        $this->matriculaAluno = random_int(2000000000, 8999999999);
        $this->isbn = '978' . time() . random_int(100, 999);
        $this->codigoLivro = 0;
    }

    protected function criarDadosDeTeste(
        bool $criarAluno,
        int $quantidadeDisponivel
    ): void {
        $stmt = $this->conn->prepare(
            'INSERT INTO turma
             (codigo, curso, siglaCurso, periodo, serie, matrizCurricular, situacao)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $curso = 'Bacharelado';
        $siglaCurso = 'BSI';
        $periodo = 1;
        $serie = 1;
        $matriz = 'Teste E2E';
        $situacao = 'ativo';

        $stmt->bind_param(
            'ississs',
            $this->codigoTurma,
            $curso,
            $siglaCurso,
            $periodo,
            $serie,
            $matriz,
            $situacao
        );

        $stmt->execute();
        $stmt->close();

        if ($criarAluno) {
            $stmt = $this->conn->prepare(
                'INSERT INTO aluno
                 (matricula, nome, datnasc, endereco, sexo, email, situacao, codigoTurma)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $nome = 'Aluno Teste E2E';
            $dataNascimento = '2000-01-01';
            $endereco = 'Endereço de teste';
            $sexo = 'F';
            $email = 'e2e' . $this->matriculaAluno . '@teste.com';
            $situacaoAluno = 'ativo';

            $stmt->bind_param(
                'issssssi',
                $this->matriculaAluno,
                $nome,
                $dataNascimento,
                $endereco,
                $sexo,
                $email,
                $situacaoAluno,
                $this->codigoTurma
            );

            $stmt->execute();
            $stmt->close();
        }

        $stmt = $this->conn->prepare(
            'INSERT INTO livro
             (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $titulo = 'Livro Teste E2E ' . $this->isbn;
        $autor = 'Autor Teste';
        $codigoEditora = 1;
        $ano = 2026;
        $situacaoLivro = 'ativo';
        $edicao = 1;

        $stmt->bind_param(
            'sssiisii',
            $this->isbn,
            $titulo,
            $autor,
            $codigoEditora,
            $ano,
            $situacaoLivro,
            $edicao,
            $quantidadeDisponivel
        );

        $stmt->execute();

        $this->codigoLivro = (int) $stmt->insert_id;

        $stmt->close();
    }

    protected function loginAdministrador(): void
    {
        $this->driver->get($this->baseUrl . '/login.php');

        $this->driver->findElement(
            WebDriverBy::name('matricula')
        )->sendKeys('1234567890');

        $this->driver->findElement(
            WebDriverBy::name('senha')
        )->sendKeys('1234567890');

        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();
    }

    protected function abrirFormularioEmprestimo(): void
    {
        $this->driver->get($this->baseUrl . '/addEmprestimo.php');
    }

    protected function consultarQuantidadeDisponivel(): int
    {
        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
        );

        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return (int) $resultado['qtde_disponivel'];
    }

    protected function contarEmprestimos(): int
    {
        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total
             FROM emprestimo
             WHERE codigo_livro = ?'
        );

        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return (int) $resultado['total'];
    }

    protected function tearDown(): void
    {
        if (isset($this->conn) && $this->conn instanceof mysqli) {
            if (!empty($this->codigoLivro)) {
                $stmt = $this->conn->prepare(
                    'DELETE d FROM devolucao d
                     INNER JOIN emprestimo e
                     ON d.codigo_emprestimo = e.codigo_emprestimo
                     WHERE e.codigo_livro = ?'
                );

                $stmt->bind_param('i', $this->codigoLivro);
                $stmt->execute();
                $stmt->close();

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

            if (!empty($this->matriculaAluno)) {
                $stmt = $this->conn->prepare(
                    'DELETE FROM aluno WHERE matricula = ?'
                );

                $stmt->bind_param('i', $this->matriculaAluno);
                $stmt->execute();
                $stmt->close();
            }

            if (!empty($this->codigoTurma)) {
                $stmt = $this->conn->prepare(
                    'DELETE FROM turma WHERE codigo = ?'
                );

                $stmt->bind_param('i', $this->codigoTurma);
                $stmt->execute();
                $stmt->close();
            }

            $this->conn->close();
        }

        parent::tearDown();
    }
}