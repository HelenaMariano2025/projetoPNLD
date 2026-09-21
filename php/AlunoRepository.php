<?php

class AlunoRepository
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function inserir($matricula, $nome, $datnasc, $endereco, $sexo, $email, $situacao, $codigoTurma)
    {
        $sql = "INSERT INTO aluno 
                (matricula, nome, datnasc, endereco, sexo, email, situacao, codigoTurma)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "issssssi",
            $matricula,
            $nome,
            $datnasc,
            $endereco,
            $sexo,
            $email,
            $situacao,
            $codigoTurma
        );

        return $stmt->execute();
    }

    public function consultarAtivos()
    {
        $sql = "SELECT * FROM aluno WHERE situacao = 'ativo'";

        $result = $this->conn->query($sql);

        return $result;
    }

    public function atualizar($matricula, $nome, $datnasc, $endereco, $sexo, $email, $situacao, $codigoTurma)
    {
        $sql = "UPDATE aluno SET
                nome = ?,
                datnasc = ?,
                endereco = ?,
                sexo = ?,
                email = ?,
                situacao = ?,
                codigoTurma = ?
                WHERE matricula = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssssii",
            $nome,
            $datnasc,
            $endereco,
            $sexo,
            $email,
            $situacao,
            $codigoTurma,
            $matricula
        );

        return $stmt->execute();
    }

    public function inativar($matricula)
    {
        $sql = "UPDATE aluno
                SET situacao = 'inativo'
                WHERE matricula = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $matricula);

        return $stmt->execute();
    }
}