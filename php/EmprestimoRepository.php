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
            if (
                $this->podeRegistrarDevolucao($codigoEmprestimo)
            ) {
                $codigoLivro = $this->buscarCodigoLivro($codigoEmprestimo);

                if ($codigoLivro !== null) {
                    $this->conn->begin_transaction();
                    $transacaoIniciada = true;

                    $sucesso = $this->inserirDevolucao(
                        $codigoEmprestimo,
                        $dataDevolucao
                    );

                    if ($sucesso) {
                        $sucesso = $this->atualizarEstoque($codigoLivro);
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

    private function podeRegistrarDevolucao(int $codigoEmprestimo): bool
    {
        $sql = 'SELECT codigo_devolucao
                FROM devolucao
                WHERE codigo_emprestimo = ?
                LIMIT 1';

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param('i', $codigoEmprestimo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $podeRegistrar = $resultado->num_rows === 0;

        $stmt->close();

        return $podeRegistrar;
    }

    private function buscarCodigoLivro(int $codigoEmprestimo): ?int
    {
        $sql = 'SELECT codigo_livro
                FROM emprestimo
                WHERE codigo_emprestimo = ?
                LIMIT 1';

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            return null;
        }

        $stmt->bind_param('i', $codigoEmprestimo);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            $stmt->close();
            return null;
        }

        $emprestimo = $resultado->fetch_assoc();
        $stmt->close();

        return (int) $emprestimo['codigo_livro'];
    }

    private function inserirDevolucao(
        int $codigoEmprestimo,
        string $dataDevolucao
    ): bool {
        $sql = 'INSERT INTO devolucao
                (codigo_emprestimo, dataDevolucao)
                VALUES (?, ?)';

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param(
            'is',
            $codigoEmprestimo,
            $dataDevolucao
        );

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    private function atualizarEstoque(int $codigoLivro): bool
    {
        $sql = 'UPDATE livro
                SET qtde_disponivel = qtde_disponivel + 1
                WHERE codigo = ?';

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param('i', $codigoLivro);

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}
