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

    public function consultarTodos(?string $titulo = null): mysqli_result|false
    {
        $sql = 'SELECT * FROM livro';
        $temBusca = $titulo !== null && trim($titulo) !== '';

        if ($temBusca) {
            $sql .= ' WHERE titulo LIKE ?';
        }

        $sql .= ' ORDER BY titulo, codigo';

        $stmt = $this->conn->prepare($sql);

        if ($temBusca) {
            $termo = '%' . trim($titulo) . '%';
            $stmt->bind_param('s', $termo);
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    public function consultarDisponiveis(?string $titulo = null): mysqli_result|false
    {
        $sql = "SELECT * FROM livro
                WHERE situacao = 'ativo' AND qtde_disponivel > 0";

        $temBusca = $titulo !== null && trim($titulo) !== '';

        if ($temBusca) {
            $sql .= ' AND titulo LIKE ?';
        }

        $stmt = $this->conn->prepare($sql);

        if ($temBusca) {
            $termo = '%' . trim($titulo) . '%';
            $stmt->bind_param('s', $termo);
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    public function atualizar(
        int $codigo,
        string $isbn,
        string $titulo,
        string $autor,
        int $codigoEditora,
        int $ano,
        string $situacao,
        int $edicao,
        int $quantidadeDisponivel
    ): bool {
        $sql = 'UPDATE livro
                SET isbn = ?, titulo = ?, autor = ?, codigo_editora = ?,
                    ano = ?, situacao = ?, edicao = ?, qtde_disponivel = ?
                WHERE codigo = ?';

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            'sssiisiii',
            $isbn,
            $titulo,
            $autor,
            $codigoEditora,
            $ano,
            $situacao,
            $edicao,
            $quantidadeDisponivel,
            $codigo
        );

        return $stmt->execute();
    }

    public function excluir(int $codigo): bool
    {
        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total FROM emprestimo WHERE codigo_livro = ?'
        );
        $stmt->bind_param('i', $codigo);

        if (!$stmt->execute()) {
            return false;
        }

        $resultado = $stmt->get_result();

        if ($resultado === false) {
            return false;
        }

        $vinculos = $resultado->fetch_assoc();

        if ($vinculos === null || (int) $vinculos['total'] > 0) {
            return false;
        }

        $stmt = $this->conn->prepare(
            'DELETE FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $codigo);

        return $stmt->execute();
    }
}