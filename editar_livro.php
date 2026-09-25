<?php

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/LivroRepository.php';

$codigo = filter_input(INPUT_POST, 'codigo', FILTER_VALIDATE_INT);

if ($codigo === null || $codigo === false) {
    $codigo = filter_input(INPUT_GET, 'codigo', FILTER_VALIDATE_INT);
}

if ($codigo === null || $codigo === false || $codigo <= 0) {
    http_response_code(400);
    exit('Código do livro inválido.');
}

$mensagem = '';
$repository = new LivroRepository($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = trim($_POST['isbn'] ?? '');
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $codigoEditora = filter_input(INPUT_POST, 'codigo_editora', FILTER_VALIDATE_INT);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $situacao = $_POST['situacao'] ?? '';
    $edicao = filter_input(INPUT_POST, 'edicao', FILTER_VALIDATE_INT);
    $quantidade = filter_input(INPUT_POST, 'qtde_disponivel', FILTER_VALIDATE_INT);

    if (
        $isbn === '' || $titulo === '' || $autor === ''
        || $codigoEditora === false || $codigoEditora === null
        || $ano === false || $ano === null
        || !in_array($situacao, ['ativo', 'inativo'], true)
        || $edicao === false || $edicao === null
        || $quantidade === false || $quantidade === null
    ) {
        $mensagem = 'Preencha todos os campos com valores válidos.';
    } else {
        try {
            $salvou = $repository->atualizar(
                $codigo,
                $isbn,
                $titulo,
                $autor,
                $codigoEditora,
                $ano,
                $situacao,
                $edicao,
                $quantidade
            );

            $mensagem = $salvou
                ? 'Livro atualizado com sucesso!'
                : 'Não foi possível atualizar o livro.';
        } catch (mysqli_sql_exception $erro) {
            $mensagem = 'Não foi possível atualizar o livro. Verifique os dados informados.';
        }
    }
}

$stmt = $conn->prepare('SELECT * FROM livro WHERE codigo = ?');
$stmt->bind_param('i', $codigo);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();

if ($livro === null) {
    http_response_code(404);
    exit('Livro não encontrado.');
}

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar livro</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    <main class="container py-5">
        <h1>Editar livro</h1>
        <p><a href="livros.php">Voltar para livros</a></p>

        <?php if ($mensagem !== ''): ?>
            <p role="status"><?= escapar($mensagem) ?></p>
        <?php endif; ?>

        <form method="post" action="editar_livro.php">
            <input type="hidden" name="codigo" value="<?= escapar($livro['codigo']) ?>">

            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input class="form-control" id="isbn" name="isbn"
                       value="<?= escapar($livro['isbn']) ?>" required>
            </div>

            <div class="form-group">
                <label for="titulo">Título</label>
                <input class="form-control" id="titulo" name="titulo"
                       value="<?= escapar($livro['titulo']) ?>" required>
            </div>

            <div class="form-group">
                <label for="autor">Autor</label>
                <input class="form-control" id="autor" name="autor"
                       value="<?= escapar($livro['autor']) ?>" required>
            </div>

            <div class="form-group">
                <label for="codigo_editora">Código da editora</label>
                <input class="form-control" type="number" id="codigo_editora"
                       name="codigo_editora" value="<?= escapar($livro['codigo_editora']) ?>" required>
            </div>

            <div class="form-group">
                <label for="ano">Ano</label>
                <input class="form-control" type="number" id="ano" name="ano"
                       value="<?= escapar($livro['ano']) ?>" required>
            </div>

            <div class="form-group">
                <label for="situacao">Situação</label>
                <select class="form-control" id="situacao" name="situacao" required>
                    <option value="ativo" <?= $livro['situacao'] === 'ativo' ? 'selected' : '' ?>>
                        Ativo
                    </option>
                    <option value="inativo" <?= $livro['situacao'] === 'inativo' ? 'selected' : '' ?>>
                        Inativo
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="edicao">Edição</label>
                <input class="form-control" type="number" id="edicao" name="edicao"
                       value="<?= escapar($livro['edicao']) ?>" required>
            </div>

            <div class="form-group">
                <label for="qtde_disponivel">Quantidade disponível</label>
                <input class="form-control" type="number" id="qtde_disponivel"
                       name="qtde_disponivel" min="0"
                       value="<?= escapar($livro['qtde_disponivel']) ?>" required>
            </div>

            <button class="btn btn-primary" type="submit">Salvar alterações</button>
        </form>
    </main>
</body>
</html>