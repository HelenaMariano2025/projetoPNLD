<?php

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/LivroRepository.php';

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

$paginaAtiva = 'livros';
$tituloPagina = 'Gerenciar Livros — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';
?>

<main class="container my-4">

    <section class="painel">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1>Gerenciar livros</h1>
                <p class="text-muted mb-0">
                    Consulte, pesquise e gerencie os livros do acervo didático.
                </p>
            </div>

            <a href="add_livro.php" class="btn btn-primary mt-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Adicionar livro
            </a>
        </div>
    </section>

    <?php if ($exclusao === 'sucesso'): ?>
        <div class="alert alert-success" role="alert">
            Livro excluído com sucesso.
        </div>
    <?php elseif ($exclusao === 'erro'): ?>
        <div class="alert alert-danger" role="alert">
            Não foi possível excluir o livro. Livros vinculados a
            empréstimos ou devoluções não podem ser excluídos.
        </div>
    <?php endif; ?>

    <section class="painel">
        <h2>Livros cadastrados</h2>
        <p class="text-muted">
            Consulte os livros do acervo, incluindo os inativos e sem estoque.
        </p>

        <form action="livros.php" method="get" class="mb-4">
            <div class="form-group">
                <label for="titulo">Pesquisar por título</label>
                <input
                    type="text"
                    class="form-control"
                    id="titulo"
                    name="titulo"
                    value="<?= escapar($titulo) ?>"
                    placeholder="Digite o título do livro"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i>
                Buscar
            </button>

            <a href="livros.php" class="btn btn-secondary">
                Limpar pesquisa
            </a>
        </form>

        <?php if ($erroConsulta): ?>

            <div class="alert alert-danger" role="alert">
                Não foi possível consultar os livros. Tente novamente.
            </div>

        <?php elseif ($livros === []): ?>

            <div class="alert alert-info mb-0" role="alert">
                <?= $titulo !== ''
                    ? 'Nenhum livro encontrado para o título informado.'
                    : 'Nenhum livro cadastrado.' ?>
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
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
                                    <div class="d-flex flex-wrap">
                                        <a
                                            href="editar_livro.php?codigo=<?= (int) $livro['codigo'] ?>"
                                            class="btn btn-primary btn-sm mr-2 mb-1"
                                        >
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
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
                                                type="submit"
                                                class="btn btn-danger btn-sm mb-1"
                                            >
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="text-muted mb-0">
                Total de livros encontrados: <?= count($livros) ?>
            </p>

        <?php endif; ?>
    </section>

</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
