<?php

class LivroRepository
{
    public function __construct(private mysqli $conn)
    {
    }

    public function inserir(
        string $isbn,
        string $titulo,
        string $autor,
        int $codigoEditora,
        int $ano,
        string $situacao,
        int $edicao,
        int $quantidadeDisponivel
    ): bool {
        $sql = 'INSERT INTO livro
                (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'sssiisii',
            $isbn,
            $titulo,
            $autor,
            $codigoEditora,
            $ano,
            $situacao,
            $edicao,
            $quantidadeDisponivel
        );

        return $stmt->execute();
    }
}