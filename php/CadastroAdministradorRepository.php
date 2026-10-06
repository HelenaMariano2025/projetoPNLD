<?php

class CadastroAdministradorRepository
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function inserir($matricula, $nome, $senha)
    {
        $sql = "INSERT INTO administrador
                (matricula, nome, senha)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "iss",
            $matricula,
            $nome,
            $senha
        );

        return $stmt->execute();
    }
}
