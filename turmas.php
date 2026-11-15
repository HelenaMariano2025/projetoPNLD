<?php

use App\TurmaRepository;

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/vendor/autoload.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';

$repository = new TurmaRepository($conn);

$curso = isset($_GET['curso']) && is_string($_GET['curso'])
    ? trim($_GET['curso'])
    : '';

if ($curso !== '') {
    $result = $repository->pesquisarPorCurso($curso);
} else {
    $result = $repository->consultarAtivos();
}

$turmas = [];

if ($result instanceof mysqli_result) {
    $turmas = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

$paginaAtiva = 'turmas';
$tituloPagina = 'Gerenciar Turmas — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';
?>

<main class="container my-4">

    <section class="painel">
        <h1>Gerenciar turmas</h1>
        <p class="text-muted mb-0">
            Consulte, pesquise e gerencie as turmas cadastradas no sistema.
        </p>
    </section>

    <?php if ($mensagem !== ''): ?>
        <div class="alert alert-info" role="alert">
            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <section class="painel">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h2>Turmas cadastradas</h2>
                <p class="text-muted mb-0">
                    Consulte as informações das turmas disponíveis.
                </p>
            </div>

            <a href="add_turma.php" class="btn btn-primary mt-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Adicionar turma
            </a>
        </div>

        <form action="turmas.php" method="get" class="mb-4">
            <div class="form-group">
                <label for="curso">Pesquisar por curso</label>
                <input
                    type="text"
                    class="form-control"
                    id="curso"
                    name="curso"
                    value="<?= htmlspecialchars($curso, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Digite o nome do curso"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i>
                Buscar
            </button>

            <a href="turmas.php" class="btn btn-secondary">
                Limpar pesquisa
            </a>
        </form>

        <?php if ($turmas === []): ?>

            <div class="alert alert-info mb-0" role="alert">
                <?= $curso !== ''
                    ? 'Nenhuma turma encontrada para o curso pesquisado.'
                    : 'Nenhuma turma disponível no momento.' ?>
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Sigla</th>
                            <th scope="col">Período</th>
                            <th scope="col">Série</th>
                            <th scope="col">Matriz curricular</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($turmas as $turma): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars(
                                        (string) $turma['codigo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $turma['curso'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $turma['siglaCurso'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        (string) $turma['periodo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        (string) $turma['serie'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $turma['matrizCurricular'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap">
                                        <a
                                            href="editar_turma.php?codigo=<?= (int) $turma['codigo'] ?>"
                                            class="btn btn-primary btn-sm mr-2 mb-1"
                                        >
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                            Editar
                                        </a>

                                        <a
                                            href="excluir_turma.php?codigo=<?= (int) $turma['codigo'] ?>"
                                            class="btn btn-danger btn-sm mb-1"
                                            onclick="return confirm('Tem certeza de que deseja excluir esta turma?')"
                                        >
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            Excluir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="text-muted mb-0">
                Total de turmas encontradas: <?= count($turmas) ?>
            </p>

        <?php endif; ?>
    </section>

</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
