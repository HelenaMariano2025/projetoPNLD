<?php

session_start();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/LivroRepository.php';

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$titulo = isset($_GET['titulo']) && is_string($_GET['titulo'])
    ? trim($_GET['titulo'])
    : '';

$livros = [];
$erroConsulta = false;

try {
    $repository = new LivroRepository($conn);
    $resultado = $repository->consultarTodos($titulo);

    if ($resultado === false) {
        $erroConsulta = true;
    } else {
        $livros = $resultado->fetch_all(MYSQLI_ASSOC);
        $resultado->free();
    }
} catch (mysqli_sql_exception $erro) {
    $erroConsulta = true;
} finally {
    $conn->close();
}

$exclusao = $_GET['exclusao'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar livros</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    <main class="container py-5">
        <h1>Gerenciar livros</h1>

        <nav class="mb-4" aria-label="Navegação">
            <a class="btn btn-secondary" href="funcoes.php">Home</a>
            <a class="btn btn-primary" href="add_livro.php">Adicionar livro</a>
        </nav>

        <?php if ($exclusao === 'sucesso'): ?>
            <p class="alert alert-success" role="status">
                Livro excluído com sucesso.
            </p>
        <?php elseif ($exclusao === 'erro'): ?>
            <p class="alert alert-danger" role="alert">
                Não foi possível excluir o livro. Livros vinculados a
                empréstimos ou devoluções não podem ser excluídos.
            </p>
        <?php endif; ?>

        <form class="mb-4" action="livros.php" method="get">
            <div class="form-group">
                <label for="titulo">Pesquisar por título</label>
                <input
                    class="form-control"
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="<?= escapar($titulo) ?>"
                    placeholder="Digite o título do livro"
                >
            </div>

            <button class="btn btn-primary" type="submit">Buscar</button>
            <a class="btn btn-secondary" href="livros.php">Limpar pesquisa</a>
        </form>

        <?php if ($erroConsulta): ?>
            <p class="alert alert-danger" role="alert">
                Não foi possível consultar os livros. Tente novamente.
            </p>
        <?php elseif ($livros === []): ?>
            <p class="alert alert-info" role="status">
                <?= $titulo !== ''
                    ? 'Nenhum livro encontrado para o título informado.'
                    : 'Nenhum livro cadastrado.' ?>
            </p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <caption>
                        Livros do acervo, incluindo inativos e sem estoque.
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col">ISBN</th>
                            <th scope="col">Título</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Código da editora</th>
                            <th scope="col">Ano</th>
                            <th scope="col">Edição</th>
                            <th scope="col">Situação</th>
                            <th scope="col">Quantidade disponível</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livros as $livro): ?>
                            <tr>
                                <td><?= escapar($livro['isbn']) ?></td>
                                <td><?= escapar($livro['titulo']) ?></td>
                                <td><?= escapar($livro['autor']) ?></td>
                                <td><?= escapar($livro['codigo_editora']) ?></td>
                                <td><?= escapar($livro['ano']) ?></td>
                                <td><?= escapar($livro['edicao']) ?></td>
                                <td><?= escapar($livro['situacao']) ?></td>
                                <td><?= escapar($livro['qtde_disponivel']) ?></td>
                                <td>
                                    <a
                                        class="btn btn-primary btn-sm mb-2"
                                        href="editar_livro.php?codigo=<?= (int) $livro['codigo'] ?>"
                                    >
                                        Editar
                                    </a>

                                    <form
                                        action="excluir_livro.php"
                                        method="post"
                                        onsubmit="return confirm('Deseja excluir permanentemente este livro?')"
                                    >
                                        <input
                                            type="hidden"
                                            name="codigo"
                                            value="<?= (int) $livro['codigo'] ?>"
                                        >
                                        <button
                                            class="btn btn-danger btn-sm"
                                            type="submit"
                                        >
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>