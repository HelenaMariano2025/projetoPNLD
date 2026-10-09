<?php

class TurmaRepository
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function inserir($codigo, $curso, $siglaCurso, $periodo, $serie, $matrizCurricular, $situacao)
    {
        $sql = "INSERT INTO turma
                (codigo, curso, siglaCurso, periodo, serie, matrizCurricular, situacao)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "issiiss",
            $codigo,
            $curso,
            $siglaCurso,
            $periodo,
            $serie,
            $matrizCurricular,
            $situacao
        );

        return $stmt->execute();
    }

    public function consultarAtivos()
    {
        $sql = "SELECT * FROM turma WHERE situacao = 'ativo'";

        $result = $this->conn->query($sql);

        return $result;
    }

    public function atualizar($codigo, $curso, $siglaCurso, $periodo, $serie, $matrizCurricular, $situacao)
    {
        $sql = "UPDATE turma SET
                curso = ?,
                siglaCurso = ?,
                periodo = ?,
                serie = ?,
                matrizCurricular = ?,
                situacao = ?
                WHERE codigo = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssiissi",
            $curso,
            $siglaCurso,
            $periodo,
            $serie,
            $matrizCurricular,
            $situacao,
            $codigo
        );

        return $stmt->execute();
    }

    public function inativar($codigo)
    {
        $sql = "UPDATE turma
                SET situacao = 'inativo'
                WHERE codigo = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $codigo);

        return $stmt->execute();
    }


    
    
    public function pesquisarPorCurso($curso)
    {
        $sql = "SELECT * FROM turma WHERE situacao = ? AND curso LIKE ?";

        $stmt = $this->conn->prepare($sql);

        $situacao = 'ativo';
        $cursoPesquisa = '%' . $curso . '%';

        $stmt->bind_param("ss", $situacao, $cursoPesquisa);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function temAlunosVinculados($codigo)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aluno
                WHERE codigoTurma = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $codigo);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['total'] > 0;
    }

    
    public function excluir($codigo)
    {
        if ($this->temAlunosVinculados($codigo)) {
            return false;
        }

        $sql = "DELETE FROM turma WHERE codigo = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $codigo);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }
}