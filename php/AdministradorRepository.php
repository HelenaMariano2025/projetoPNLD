<?php

class AdministradorRepository
{
    public function __construct(private mysqli $conn)
    {
    }

    public function buscarPorMatricula(int $matricula): ?array
    {
        $sql = 'SELECT matricula, nome, senha
                FROM administrador
                WHERE matricula = ?';

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param('i', $matricula);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        return $resultado->fetch_assoc();
    }
}