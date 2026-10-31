<?php

namespace App;

class EmprestimoRepository
{
    public function __construct(private \mysqli $conn)
    {
    }

    public function registrarEmprestimo(
        int $codigoLivro,
        string $dataEmprestimo,
        string $dataDevolucao,
        int $matriculaAluno,
        int $matriculaAdministrador
    ): bool {
        $sucesso = false;
        $transacaoIniciada = false;

        try {
            $this->conn->begin_transaction();
            $transacaoIniciada = true;

            $sqlLivro = 'UPDATE livro
                         SET qtde_disponivel = qtde_disponivel - 1
                         WHERE codigo = ? AND qtde_disponivel > 0';

            $stmtLivro = $this->conn->prepare($sqlLivro);

            if ($stmtLivro !== false) {
                $stmtLivro->bind_param('i', $codigoLivro);
                $stmtLivro->execute();

                $livroAtualizado = $stmtLivro->affected_rows === 1;
                $stmtLivro->close();

                if ($livroAtualizado) {
                    $sqlEmprestimo = 'INSERT INTO emprestimo
                        (codigo_livro, dataEmprestimo, dataDevolucao, matricula_aluno, adm_responsavel)
                        VALUES (?, ?, ?, ?, ?)';

                    $stmtEmprestimo = $this->conn->prepare($sqlEmprestimo);

                    if ($stmtEmprestimo !== false) {
                        $stmtEmprestimo->bind_param(
                            'issii',
                            $codigoLivro,
                            $dataEmprestimo,
                            $dataDevolucao,
                            $matriculaAluno,
                            $matriculaAdministrador
                        );

                        $sucesso = $stmtEmprestimo->execute();
                        $stmtEmprestimo->close();
                    }
                }
            }

            if ($sucesso) {
                $this->conn->commit();
            } else {
                $this->conn->rollback();
            }

            $transacaoIniciada = false;
        } catch (\mysqli_sql_exception $e) {
            if ($transacaoIniciada) {
                $this->conn->rollback();
            }

            error_log($e->getMessage());
        }

        return $sucesso;
    }

    public function registrarDevolucao(
        int $codigoEmprestimo,
        string $dataDevolucao
    ): bool {
        $sucesso = false;
        $transacaoIniciada = false;

        try {
            $sqlVerificar = 'SELECT codigo_devolucao
                             FROM devolucao
                             WHERE codigo_emprestimo = ?
                             LIMIT 1';

            $stmtVerificar = $this->conn->prepare($sqlVerificar);

            if ($stmtVerificar !== false) {
                $stmtVerificar->bind_param('i', $codigoEmprestimo);
                $stmtVerificar->execute();

                $resultado = $stmtVerificar->get_result();
                $jaDevolvido = $resultado->num_rows > 0;

                $stmtVerificar->close();

                if (!$jaDevolvido) {
                    $sqlEmprestimo = 'SELECT codigo_livro
                                      FROM emprestimo
                                      WHERE codigo_emprestimo = ?
                                      LIMIT 1';

                    $stmtEmprestimo = $this->conn->prepare($sqlEmprestimo);

                    if ($stmtEmprestimo !== false) {
                        $stmtEmprestimo->bind_param('i', $codigoEmprestimo);
                        $stmtEmprestimo->execute();

                        $resultadoEmprestimo = $stmtEmprestimo->get_result();

                        if ($resultadoEmprestimo->num_rows > 0) {
                            $emprestimo = $resultadoEmprestimo->fetch_assoc();
                            $codigoLivro = (int) $emprestimo['codigo_livro'];

                            $stmtEmprestimo->close();

                            $this->conn->begin_transaction();
                            $transacaoIniciada = true;

                            $sqlInserir = 'INSERT INTO devolucao
                                           (codigo_emprestimo, dataDevolucao)
                                           VALUES (?, ?)';

                            $stmtInserir = $this->conn->prepare($sqlInserir);

                            if ($stmtInserir !== false) {
                                $stmtInserir->bind_param(
                                    'is',
                                    $codigoEmprestimo,
                                    $dataDevolucao
                                );

                                $resultadoInsercao = $stmtInserir->execute();
                                $stmtInserir->close();

                                if ($resultadoInsercao) {
                                    $sqlLivro = 'UPDATE livro
                                                 SET qtde_disponivel = qtde_disponivel + 1
                                                 WHERE codigo = ?';

                                    $stmtLivro = $this->conn->prepare($sqlLivro);

                                    if ($stmtLivro !== false) {
                                        $stmtLivro->bind_param('i', $codigoLivro);
                                        $sucesso = $stmtLivro->execute();
                                        $stmtLivro->close();
                                    }
                                }
                            }
                        } else {
                            $stmtEmprestimo->close();
                        }
                    }
                }
            }

            if ($transacaoIniciada) {
                if ($sucesso) {
                    $this->conn->commit();
                } else {
                    $this->conn->rollback();
                }

                $transacaoIniciada = false;
            }
        } catch (\mysqli_sql_exception $e) {
            if ($transacaoIniciada) {
                $this->conn->rollback();
            }

            error_log($e->getMessage());
        }

        return $sucesso;
    }
}
