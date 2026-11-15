<?php

use App\TurmaRepository;

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';

$repository = new TurmaRepository($conn);

$codigo = filter_var($_GET['codigo'] ?? null, FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = filter_var($_POST['codigo'] ?? null, FILTER_VALIDATE_INT);
    $curso = trim($_POST['curso'] ?? '');
    $siglaCurso = trim($_POST['siglaCurso'] ?? '');
    $periodo = filter_var($_POST['periodo'] ?? null, FILTER_VALIDATE_INT);
    $serie = filter_var($_POST['serie'] ?? null, FILTER_VALIDATE_INT);
    $matrizCurricular = trim($_POST['matrizCurricular'] ?? '');
    $situacao = $_POST['situacao'] ?? '';

    if (
        !$codigo ||
        $curso === '' ||
        $periodo === false || $periodo === null || $periodo <= 0 ||
        $serie === false || $serie === null || $serie <= 0 ||
        !in_array($situacao, ['ativo', 'inativo'], true)
    ) {
        $_SESSION['mensagem'] = 'Preencha os campos obrigatórios com valores válidos.';
        $conn->close();
        redirecionar('turmas.php');
    }

    try {
        $resultado = $repository->atualizar(
            $codigo,
            $curso,
            $siglaCurso,
            $periodo,
            $serie,
            $matrizCurricular,
            $situacao
        );

        $_SESSION['mensagem'] = $resultado
            ? 'Turma atualizada com sucesso!'
            : 'Não foi possível atualizar a turma.';
    } catch (mysqli_sql_exception $e) {
        error_log($e->getMessage());
        $_SESSION['mensagem'] = 'Ocorreu um erro ao atualizar a turma.';
    }

    $conn->close();
    redirecionar('turmas.php');
}

if (!$codigo) {
    $_SESSION['mensagem'] = 'Código de turma inválido.';
    $conn->close();
    redirecionar('turmas.php');
}

$stmt = $conn->prepare('SELECT * FROM turma WHERE codigo = ?');
$stmt->bind_param('i', $codigo);
$stmt->execute();
$result = $stmt->get_result();
$turma = $result->fetch_assoc();
$stmt->close();

if (!$turma) {
    $_SESSION['mensagem'] = 'Turma não encontrada.';
    $conn->close();
    redirecionar('turmas.php');
}

$paginaAtiva = 'turmas';
$tituloPagina = 'Editar Turma — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';
?>

<main class="container my-4">

    <section class="painel">
        <h1>Editar turma</h1>
        <p class="text-muted mb-0">
            Atualize as informações da turma selecionada.
        </p>
    </section>

    <section class="painel">

        <div class="mb-4">
            <h2>Dados da turma</h2>
            <p class="text-muted mb-0">
                Altere os campos necessários e salve as modificações.
            </p>
        </div>

        <form action="editar_turma.php" method="post">

            <input
                type="hidden"
                name="codigo"
                value="<?= (int) $turma['codigo'] ?>"
            >

            <div class="form-group">
                <label for="curso">Curso</label>
                <input
                    type="text"
                    class="form-control"
                    id="curso"
                    name="curso"
                    value="<?= htmlspecialchars($turma['curso'], ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="siglaCurso">Sigla do curso</label>
                <input
                    type="text"
                    class="form-control"
                    id="siglaCurso"
                    name="siglaCurso"
                    value="<?= htmlspecialchars($turma['siglaCurso'], ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="periodo">Período</label>
                    <input
                        type="number"
                        class="form-control"
                        id="periodo"
                        name="periodo"
                        value="<?= (int) $turma['periodo'] ?>"
                        min="1"
                        required
                    >
                </div>

                <div class="form-group col-md-6">
                    <label for="serie">Série</label>
                    <input
                        type="number"
                        class="form-control"
                        id="serie"
                        name="serie"
                        value="<?= (int) $turma['serie'] ?>"
                        min="1"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="matrizCurricular">Matriz curricular</label>
                <input
                    type="text"
                    class="form-control"
                    id="matrizCurricular"
                    name="matrizCurricular"
                    value="<?= htmlspecialchars($turma['matrizCurricular'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                    <option value="ativo" <?= $turma['situacao'] === 'ativo' ? 'selected' : '' ?>>
                        Ativo
                    </option>

                    <option value="inativo" <?= $turma['situacao'] === 'inativo' ? 'selected' : '' ?>>
                        Inativo
                    </option>
                </select>
            </div>

            <div class="d-flex flex-wrap justify-content-end mt-4">
                <a href="turmas.php" class="btn btn-secondary mr-2 mb-2">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary mb-2">
                    Salvar alterações
                </button>
            </div>

        </form>
    </section>

</main>

<?php
$conn->close();
require_once __DIR__ . '/includes/rodape.php';
?>
