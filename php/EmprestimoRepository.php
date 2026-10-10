<?php

class EmprestimoRepository
{
    public function __construct(private mysqli $conn)
    {
    }

    public function registrarEmprestimo(
        int $codigoLivro,
        string $dataEmprestimo,
        string $dataDevolucao,
        int $matriculaAluno,
        int $matriculaAdministrador
    ): bool {
        $this->conn->begin_transaction();

        try {
            $sqlLivro = 'UPDATE livro
                         SET qtde_disponivel = qtde_disponivel - 1
                         WHERE codigo = ? AND qtde_disponivel > 0';

            $stmtLivro = $this->conn->prepare($sqlLivro);

            if ($stmtLivro === false) {
                $this->conn->rollback();
                return false;
            }

            $stmtLivro->bind_param('i', $codigoLivro);
            $stmtLivro->execute();

            if ($stmtLivro->affected_rows !== 1) {
                $stmtLivro->close();
                $this->conn->rollback();
                return false;
            }

            $stmtLivro->close();

            $sqlEmprestimo = 'INSERT INTO emprestimo
                    (codigo_livro, dataEmprestimo, dataDevolucao, matricula_aluno, adm_responsavel)
                    VALUES (?, ?, ?, ?, ?)';

            $stmtEmprestimo = $this->conn->prepare($sqlEmprestimo);

            if ($stmtEmprestimo === false) {
                $this->conn->rollback();
                return false;
            }

            $stmtEmprestimo->bind_param(
                'issii',
                $codigoLivro,
                $dataEmprestimo,
                $dataDevolucao,
                $matriculaAluno,
                $matriculaAdministrador
            );

            $resultado = $stmtEmprestimo->execute();

            $stmtEmprestimo->close();

            if (!$resultado) {
                $this->conn->rollback();
                return false;
            }

            $this->conn->commit();

            return true;
        } catch (\mysqli_sql_exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function registrarDevolucao(
        int $codigoEmprestimo,
        string $dataDevolucao
    ): bool {
        $sqlVerificar = 'SELECT codigo_devolucao
                         FROM devolucao
                         WHERE codigo_emprestimo = ?
                         LIMIT 1';

        $stmtVerificar = $this->conn->prepare($sqlVerificar);

        if ($stmtVerificar === false) {
            return false;
        }

        $stmtVerificar->bind_param('i', $codigoEmprestimo);
        $stmtVerificar->execute();

        $resultado = $stmtVerificar->get_result();

        if ($resultado->num_rows > 0) {
            $stmtVerificar->close();
            return false;
        }

        $stmtVerificar->close();

        $sqlEmprestimo = 'SELECT codigo_livro
                          FROM emprestimo
                          WHERE codigo_emprestimo = ?
                          LIMIT 1';

        $stmtEmprestimo = $this->conn->prepare($sqlEmprestimo);

        if ($stmtEmprestimo === false) {
            return false;
        }

        $stmtEmprestimo->bind_param('i', $codigoEmprestimo);
        $stmtEmprestimo->execute();

        $resultadoEmprestimo = $stmtEmprestimo->get_result();

        if ($resultadoEmprestimo->num_rows === 0) {
            $stmtEmprestimo->close();
            return false;
        }

        $emprestimo = $resultadoEmprestimo->fetch_assoc();
        $codigoLivro = (int) $emprestimo['codigo_livro'];

        $stmtEmprestimo->close();

        $this->conn->begin_transaction();

        $sqlInserir = 'INSERT INTO devolucao
                       (codigo_emprestimo, dataDevolucao)
                       VALUES (?, ?)';

        $stmtInserir = $this->conn->prepare($sqlInserir);

        if ($stmtInserir === false) {
            $this->conn->rollback();
            return false;
        }

        $stmtInserir->bind_param(
            'is',
            $codigoEmprestimo,
            $dataDevolucao
        );

        $resultadoInsercao = $stmtInserir->execute();

        $stmtInserir->close();

        if (!$resultadoInsercao) {
            $this->conn->rollback();
            return false;
        }

        $sqlLivro = 'UPDATE livro
                     SET qtde_disponivel = qtde_disponivel + 1
                     WHERE codigo = ?';

        $stmtLivro = $this->conn->prepare($sqlLivro);

        if ($stmtLivro === false) {
            $this->conn->rollback();
            return false;
        }

        $stmtLivro->bind_param('i', $codigoLivro);
        $resultadoLivro = $stmtLivro->execute();

        $stmtLivro->close();

        if (!$resultadoLivro) {
            $this->conn->rollback();
            return false;
        }

        $this->conn->commit();

        return true;
    }
}
