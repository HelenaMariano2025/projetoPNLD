<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/LivroRepository.php';

class LivroExclusaoIntegrationTest extends TestCase
{
    public function testBloquearExclusaoComEmprestimoEDevolucao(): void
    {
        $conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $conn->begin_transaction();

        try {
            $codigoTurma = random_int(100000000, 999999999);
            $matriculaAluno = random_int(100000000000, 999999999999);
            $matriculaAdministrador = random_int(100000000000, 999999999999);
            $isbn = (string) random_int(1000000000000, 9999999999999);

            $stmt = $conn->prepare(
                "INSERT INTO turma
                 (codigo, curso, periodo, serie, situacao)
                 VALUES (?, 'Curso teste', 1, 1, 'ativo')"
            );
            $stmt->bind_param('i', $codigoTurma);
            $this->assertTrue($stmt->execute());
            $stmt->close();

            $stmt = $conn->prepare(
                "INSERT INTO aluno
                 (matricula, nome, email, situacao, codigoTurma)
                 VALUES (?, 'Aluno teste', 'teste@example.com', 'ativo', ?)"
            );
            $stmt->bind_param('ii', $matriculaAluno, $codigoTurma);
            $this->assertTrue($stmt->execute());
            $stmt->close();

            $stmt = $conn->prepare(
                "INSERT INTO administrador (matricula, nome, senha)
                 VALUES (?, 'Administrador teste', 'senhaTeste123')"
            );
            $stmt->bind_param('i', $matriculaAdministrador);
            $this->assertTrue($stmt->execute());
            $stmt->close();

            $repository = new LivroRepository($conn);

            $this->assertTrue($repository->inserir(
                $isbn,
                'Livro vinculado ' . $isbn,
                'Autor teste',
                1,
                2026,
                'ativo',
                1,
                0
            ));

            $codigoLivro = (int) $conn->insert_id;

            $stmt = $conn->prepare(
                'INSERT INTO emprestimo
                 (codigo_livro, dataEmprestimo, dataDevolucao,
                  matricula_aluno, adm_responsavel)
                 VALUES (?, ?, ?, ?, ?)'
            );

            $dataEmprestimo = '2026-10-01';
            $dataPrevista = '2026-10-21';

            $stmt->bind_param(
                'issii',
                $codigoLivro,
                $dataEmprestimo,
                $dataPrevista,
                $matriculaAluno,
                $matriculaAdministrador
            );
            $this->assertTrue($stmt->execute());
            $codigoEmprestimo = (int) $conn->insert_id;
            $stmt->close();

            // Um empréstimo sem devolução já deve bloquear a exclusão.
            $this->assertFalse($repository->excluir($codigoLivro));

            $stmt = $conn->prepare(
                'INSERT INTO devolucao
                 (codigo_emprestimo, dataDevolucao)
                 VALUES (?, ?)'
            );
            $dataDevolucao = '2026-10-02';
            $stmt->bind_param('is', $codigoEmprestimo, $dataDevolucao);
            $this->assertTrue($stmt->execute());
            $codigoDevolucao = (int) $conn->insert_id;
            $stmt->close();

            // Após a devolução, o histórico também deve ser preservado.
            $this->assertFalse($repository->excluir($codigoLivro));

            $stmt = $conn->prepare(
                'SELECT l.situacao, e.codigo_emprestimo, d.codigo_devolucao
                 FROM livro l
                 JOIN emprestimo e ON e.codigo_livro = l.codigo
                 JOIN devolucao d
                   ON d.codigo_emprestimo = e.codigo_emprestimo
                 WHERE l.codigo = ?'
            );
            $stmt->bind_param('i', $codigoLivro);
            $stmt->execute();

            $registro = $stmt->get_result()->fetch_assoc();
            $this->assertNotNull($registro);
            $this->assertSame('ativo', $registro['situacao']);
            $this->assertSame(
                $codigoEmprestimo,
                (int) $registro['codigo_emprestimo']
            );
            $this->assertSame(
                $codigoDevolucao,
                (int) $registro['codigo_devolucao']
            );
            $stmt->close();
        } finally {
            $conn->rollback();
            $conn->close();
        }
    }
}