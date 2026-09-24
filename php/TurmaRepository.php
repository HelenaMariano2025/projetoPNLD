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
}