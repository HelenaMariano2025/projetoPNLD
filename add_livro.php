<?php

session_start();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/LivroValidator.php';

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$dados = [
    'isbn' => '',
    'titulo' => '',
    'autor' => '',
    'codigo_editora' => '',
    'ano' => '',
    'situacao' => 'ativo',
    'edicao' => '',
    'qtde_disponivel' => '',
];

$mensagem = $_SESSION['mensagem_livro'] ?? '';
unset($_SESSION['mensagem_livro']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valor) {
        $entrada = $_POST[$campo] ?? '';
        $dados[$campo] = is_string($entrada) ? trim($entrada) : '';
    }

    $erroValidacao = LivroValidator::validar($dados);

    if ($erroValidacao !== null) {
        $mensagem = $erroValidacao;
    } else {
        require_once __DIR__ . '/php/conexao.php';
        require_once __DIR__ . '/php/LivroRepository.php';

        $cadastrado = false;

        try {
            $repository = new LivroRepository($conn);
            $cadastrado = $repository->inserir(
                $dados['isbn'],
                $dados['titulo'],
                $dados['autor'],
                (int) $dados['codigo_editora'],
                (int) $dados['ano'],
                $dados['situacao'],
                (int) $dados['edicao'],
                (int) $dados['qtde_disponivel']
            );

            if (!$cadastrado) {
                $mensagem = 'Não foi possível cadastrar o livro.';
            }
        } catch (mysqli_sql_exception $erro) {
            $mensagem = 'Não foi possível cadastrar o livro. Verifique se o ISBN já está cadastrado.';
        } finally {
            $conn->close();
        }

        if ($cadastrado) {
            $_SESSION['mensagem_livro'] = 'Novo livro adicionado com sucesso!';
            header('Location: add_livro.php', true, 303);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar livro</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    <main class="container py-5">
        <h1>Adicionar livro</h1>
        <p><a href="livros.php">Voltar para livros</a></p>

        <?php if ($mensagem !== ''): ?>
            <p class="alert alert-info" role="status">
                <?= escapar($mensagem) ?>
            </p>
        <?php endif; ?>

        <form action="add_livro.php" method="post">
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input
                    class="form-control"
                    type="text"
                    id="isbn"
                    name="isbn"
                    inputmode="numeric"
                    value="<?= escapar($dados['isbn']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="titulo">Título</label>
                <input
                    class="form-control"
                    type="text"
                    id="titulo"
                    name="titulo"
                    maxlength="100"
                    value="<?= escapar($dados['titulo']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="autor">Autor</label>
                <input
                    class="form-control"
                    type="text"
                    id="autor"
                    name="autor"
                    maxlength="100"
                    value="<?= escapar($dados['autor']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="codigo_editora">Código da editora</label>
                <input
                    class="form-control"
                    type="number"
                    id="codigo_editora"
                    name="codigo_editora"
                    min="1"
                    max="2147483647"
                    value="<?= escapar($dados['codigo_editora']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="ano">Ano</label>
                <input
                    class="form-control"
                    type="number"
                    id="ano"
                    name="ano"
                    min="1"
                    max="2147483647"
                    value="<?= escapar($dados['ano']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="situacao">Situação</label>
                <select
                    class="form-control"
                    id="situacao"
                    name="situacao"
                    required
                >
                    <option value="">Selecione</option>
                    <option
                        value="ativo"
                        <?= $dados['situacao'] === 'ativo' ? 'selected' : '' ?>
                    >
                        Ativo
                    </option>
                    <option
                        value="inativo"
                        <?= $dados['situacao'] === 'inativo' ? 'selected' : '' ?>
                    >
                        Inativo
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="edicao">Edição</label>
                <input
                    class="form-control"
                    type="number"
                    id="edicao"
                    name="edicao"
                    min="1"
                    max="2147483647"
                    value="<?= escapar($dados['edicao']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="qtde_disponivel">Quantidade disponível</label>
                <input
                    class="form-control"
                    type="number"
                    id="qtde_disponivel"
                    name="qtde_disponivel"
                    min="0"
                    max="2147483647"
                    value="<?= escapar($dados['qtde_disponivel']) ?>"
                    required
                >
            </div>

            <button class="btn btn-primary" type="submit">
                Adicionar livro
            </button>
        </form>
    </main>
</body>
</html>