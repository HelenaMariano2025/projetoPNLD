<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/EmprestimoRepository.php';
require_once __DIR__ . '/../php/LivroRepository.php';

class EmprestimoRepositoryTest extends TestCase
{
    public function testNaoPermiteRegistrarMesmaDevolucaoDuasVezes(): void
    {
        $conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $codigoTurma = random_int(100000000, 999999999);
        $matriculaAluno = random_int(100000000000, 999999999999);
        $matriculaAdministrador = random_int(100000000000, 999999999999);
        $isbn = (string) random_int(1000000000000, 9999999999999);

        $turmaCriada = false;
        $alunoCriado = false;
        $administradorCriado = false;
        $codigoLivro = null;
        $codigoEmprestimo = null;

        try {
            $stmt = $conn->prepare(
                "INSERT INTO turma
                 (codigo, curso, periodo, serie, situacao)
                 VALUES (?, 'Curso teste', 1, 1, 'ativo')"
            );
            $stmt->bind_param('i', $codigoTurma);
            $turmaCriada = $stmt->execute();
            $this->assertTrue($turmaCriada);
            $stmt->close();

            $stmt = $conn->prepare(
                "INSERT INTO aluno
                 (matricula, nome, email, situacao, codigoTurma)
                 VALUES (?, 'Aluno teste', 'teste@example.com', 'ativo', ?)"
            );
            $stmt->bind_param('ii', $matriculaAluno, $codigoTurma);
            $alunoCriado = $stmt->execute();
            $this->assertTrue($alunoCriado);
            $stmt->close();

            $stmt = $conn->prepare(
                "INSERT INTO administrador (matricula, nome, senha)
                 VALUES (?, 'Administrador teste', 'senhaTeste123')"
            );
            $stmt->bind_param('i', $matriculaAdministrador);
            $administradorCriado = $stmt->execute();
            $this->assertTrue($administradorCriado);
            $stmt->close();

            $livros = new LivroRepository($conn);
            $livroCriado = $livros->inserir(
                $isbn,
                'Livro teste de devolução',
                'Autor teste',
                1,
                2026,
                'ativo',
                1,
                0
            );

            if ($livroCriado) {
                $codigoLivro = (int) $conn->insert_id;
            }

            $this->assertTrue($livroCriado);

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

            $emprestimoCriado = $stmt->execute();

            if ($emprestimoCriado) {
                $codigoEmprestimo = (int) $conn->insert_id;
            }

            $this->assertTrue($emprestimoCriado);
            $stmt->close();

            $repository = new EmprestimoRepository($conn);

            $this->assertTrue(
                $repository->registrarDevolucao(
                    $codigoEmprestimo,
                    '2026-10-02'
                )
            );

            $this->assertFalse(
                $repository->registrarDevolucao(
                    $codigoEmprestimo,
                    '2026-10-02'
                )
            );

            $stmt = $conn->prepare(
                'SELECT COUNT(*) AS total FROM devolucao
                 WHERE codigo_emprestimo = ?'
            );
            $stmt->bind_param('i', $codigoEmprestimo);
            $stmt->execute();

            $devolucoes = $stmt->get_result()->fetch_assoc();
            $this->assertSame(1, (int) $devolucoes['total']);
            $stmt->close();

            $stmt = $conn->prepare(
                'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
            );
            $stmt->bind_param('i', $codigoLivro);
            $stmt->execute();

            $livro = $stmt->get_result()->fetch_assoc();
            $this->assertSame(1, (int) $livro['qtde_disponivel']);
            $stmt->close();
        } finally {
            try {
                $conn->rollback();

                if ($codigoEmprestimo !== null) {
                    $this->remover(
                        $conn,
                        'DELETE FROM devolucao WHERE codigo_emprestimo = ?',
                        $codigoEmprestimo
                    );
                    $this->remover(
                        $conn,
                        'DELETE FROM emprestimo WHERE codigo_emprestimo = ?',
                        $codigoEmprestimo
                    );
                }

                if ($codigoLivro !== null) {
                    $this->remover(
                        $conn,
                        'DELETE FROM livro WHERE codigo = ?',
                        $codigoLivro
                    );
                }

                if ($alunoCriado) {
                    $this->remover(
                        $conn,
                        'DELETE FROM aluno WHERE matricula = ?',
                        $matriculaAluno
                    );
                }

                if ($administradorCriado) {
                    $this->remover(
                        $conn,
                        'DELETE FROM administrador WHERE matricula = ?',
                        $matriculaAdministrador
                    );
                }

                if ($turmaCriada) {
                    $this->remover(
                        $conn,
                        'DELETE FROM turma WHERE codigo = ?',
                        $codigoTurma
                    );
                }
            } finally {
                $conn->close();
            }
        }
    }

    private function remover(mysqli $conn, string $sql, int $codigo): void
    {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $codigo);
        $stmt->execute();
        $stmt->close();
    }
}