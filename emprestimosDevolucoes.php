<?php

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/php/conexao.php';

$titulo = isset($_GET['titulo']) && is_string($_GET['titulo'])
    ? trim($_GET['titulo'])
    : '';

$emprestimos = [];
$erroConsulta = false;

try {
    $sql = "
        SELECT
            emprestimo.codigo_emprestimo,
            emprestimo.dataEmprestimo,
            emprestimo.dataDevolucao,
            livro.titulo,
            livro.isbn,
            aluno.nome
        FROM emprestimo
        INNER JOIN livro
            ON emprestimo.codigo_livro = livro.codigo
        INNER JOIN aluno
            ON emprestimo.matricula_aluno = aluno.matricula
        LEFT JOIN devolucao
            ON emprestimo.codigo_emprestimo = devolucao.codigo_emprestimo
        WHERE devolucao.codigo_emprestimo IS NULL
    ";

    if ($titulo !== '') {
        $sql .= " AND livro.titulo LIKE ?";
        $stmt = $conn->prepare($sql);
        $tituloPesquisa = '%' . $titulo . '%';
        $stmt->bind_param('s', $tituloPesquisa);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $emprestimos = $resultado->fetch_all(MYSQLI_ASSOC);

    $resultado->free();
    $stmt->close();
} catch (mysqli_sql_exception $erro) {
    $erroConsulta = true;
}

$paginaAtiva = 'emprestimos';
$tituloPagina = 'Empréstimos e Devoluções — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';
?>

<main class="container my-4">

    <section class="painel">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1>Empréstimos e devoluções</h1>
                <p class="text-muted mb-0">
                    Consulte os empréstimos em aberto e registre a devolução dos livros.
                </p>
            </div>

            <a href="addEmprestimo.php" class="btn btn-primary mt-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Adicionar empréstimo
            </a>
        </div>
    </section>

    <section class="painel">
        <h2>Empréstimos em aberto</h2>
        <p class="text-muted">
            Consulte os livros que ainda não foram devolvidos.
        </p>

        <form action="emprestimosDevolucoes.php" method="get" class="mb-4">
            <div class="form-group">
                <label for="titulo">Pesquisar por título do livro</label>
                <input
                    type="text"
                    class="form-control"
                    id="titulo"
                    name="titulo"
                    value="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Digite o título do livro"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i>
                Buscar
            </button>

            <a href="emprestimosDevolucoes.php" class="btn btn-secondary">
                Limpar pesquisa
            </a>
        </form>

        <?php if ($erroConsulta): ?>

            <div class="alert alert-danger" role="alert">
                Não foi possível consultar os empréstimos. Tente novamente.
            </div>

        <?php elseif ($emprestimos === []): ?>

            <div class="alert alert-info mb-0" role="alert">
                <?= $titulo !== ''
                    ? 'Nenhum empréstimo encontrado para o título pesquisado.'
                    : 'Nenhum livro emprestado no momento.' ?>
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Aluno</th>
                            <th scope="col">Livro</th>
                            <th scope="col">ISBN</th>
                            <th scope="col">Data do empréstimo</th>
                            <th scope="col">Data prevista para devolução</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($emprestimos as $emprestimo): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars(
                                        $emprestimo['nome'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $emprestimo['titulo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $emprestimo['isbn'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        (string) $emprestimo['dataEmprestimo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        (string) $emprestimo['dataDevolucao'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <a
                                        href="devolverLivro.php?id=<?= (int) $emprestimo['codigo_emprestimo'] ?>"
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="fa fa-undo" aria-hidden="true"></i>
                                        Devolver
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="text-muted mb-0">
                Total de empréstimos em aberto: <?= count($emprestimos) ?>
            </p>

        <?php endif; ?>
    </section>

</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
