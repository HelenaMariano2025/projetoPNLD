<?php

session_start();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/LivroRepository.php';
require_once __DIR__ . '/php/LivroValidator.php';

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$metodo = $_SERVER['REQUEST_METHOD'];

if (!in_array($metodo, ['GET', 'POST'], true)) {
    header('Allow: GET, POST');
    http_response_code(405);
    exit('Método não permitido.');
}

$origem = $metodo === 'POST' ? INPUT_POST : INPUT_GET;
$codigo = filter_input($origem, 'codigo', FILTER_VALIDATE_INT);

if ($codigo === null || $codigo === false || $codigo <= 0) {
    http_response_code(400);
    exit('Código do livro inválido.');
}

try {
    $stmt = $conn->prepare('SELECT * FROM livro WHERE codigo = ?');
    $stmt->bind_param('i', $codigo);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado === false) {
        throw new RuntimeException('Falha na consulta do livro.');
    }

    $livro = $resultado->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception | RuntimeException $erro) {
    $conn->close();
    http_response_code(500);
    exit('Não foi possível consultar o livro.');
}

if ($livro === null) {
    $conn->close();
    http_response_code(404);
    exit('Livro não encontrado.');
}

$campos = [
    'isbn',
    'titulo',
    'autor',
    'codigo_editora',
    'ano',
    'situacao',
    'edicao',
    'qtde_disponivel',
];

$dados = [];

foreach ($campos as $campo) {
    $dados[$campo] = (string) ($livro[$campo] ?? '');
}

$mensagem = $_SESSION['mensagem_edicao_livro'] ?? '';
unset($_SESSION['mensagem_edicao_livro']);

if ($metodo === 'POST') {
    foreach ($campos as $campo) {
        $entrada = $_POST[$campo] ?? '';
        $dados[$campo] = is_string($entrada) ? trim($entrada) : '';
    }

    $erroValidacao = LivroValidator::validar($dados);

    if ($erroValidacao !== null) {
        $mensagem = $erroValidacao;
    } else {
        $salvou = false;

        try {
            $repository = new LivroRepository($conn);
            $salvou = $repository->atualizar(
                $codigo,
                $dados['isbn'],
                $dados['titulo'],
                $dados['autor'],
                (int) $dados['codigo_editora'],
                (int) $dados['ano'],
                $dados['situacao'],
                (int) $dados['edicao'],
                (int) $dados['qtde_disponivel']
            );

            if (!$salvou) {
                $mensagem = 'Não foi possível atualizar o livro.';
            }
        } catch (mysqli_sql_exception $erro) {
            $mensagem = 'Não foi possível atualizar o livro. Verifique se o ISBN já pertence a outro livro.';
        }

        if ($salvou) {
            $conn->close();
            $_SESSION['mensagem_edicao_livro'] = 'Livro atualizado com sucesso!';

            header(
                'Location: editar_livro.php?codigo=' . $codigo,
                true,
                303
            );
            exit;
        }
    }
}

$conn->close();
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
            <p class="alert alert-info" role="status">
                <?= escapar($mensagem) ?>
            </p>
        <?php endif; ?>

        <form method="post" action="editar_livro.php">
            <input type="hidden" name="codigo" value="<?= $codigo ?>">

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
                Salvar alterações
            </button>
        </form>
    </main>
</body>
</html>