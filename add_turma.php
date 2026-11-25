<?php

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = (int) ($_POST['codigo'] ?? 0);
    $curso = trim($_POST['curso'] ?? '');
    $siglaCurso = trim($_POST['siglaCurso'] ?? '');
    $periodo = (int) ($_POST['periodo'] ?? 0);
    $serie = (int) ($_POST['serie'] ?? 0);
    $matrizCurricular = trim($_POST['matrizCurricular'] ?? '');
    $situacao = $_POST['situacao'] ?? 'ativo';

    if (
        $codigo <= 0 ||
        $curso === '' ||
        $periodo <= 0 ||
        $serie <= 0 ||
        !in_array($situacao, ['ativo', 'inativo'], true)
    ) {
        $_SESSION['mensagem'] = 'Preencha os campos obrigatórios com valores válidos.';
    } else {
        try {
            $sql = "INSERT INTO turma
                (codigo, curso, siglaCurso, periodo, serie, matrizCurricular, situacao)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                'issiiss',
                $codigo,
                $curso,
                $siglaCurso,
                $periodo,
                $serie,
                $matrizCurricular,
                $situacao
            );

            $stmt->execute();

            $_SESSION['mensagem'] = 'Turma cadastrada com sucesso!';
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            if ((int) $e->getCode() === 1062) {
                $_SESSION['mensagem'] = 'Já existe uma turma cadastrada com esse código.';
            } else {
                error_log($e->getMessage());
                $_SESSION['mensagem'] = 'Não foi possível cadastrar a turma. Tente novamente.';
            }
        }
    }

    $conn->close();
}

$paginaAtiva = 'turmas';
$tituloPagina = 'Adicionar Turma — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';

?>

<main class="container my-4">

    <section class="painel">
        <h1>Adicionar turma</h1>
        <p class="text-muted mb-0">
            Preencha os dados abaixo para cadastrar uma nova turma no sistema.
        </p>
    </section>

    <section class="painel">

        <div class="mb-4">
            <h2>Dados da turma</h2>
            <p class="text-muted mb-0">
                Os campos indicados são necessários para o cadastro.
            </p>
        </div>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info" role="alert">
                <?= htmlspecialchars($_SESSION['mensagem'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <form action="add_turma.php" method="post">

            <div class="form-group">
                <label for="codigo">Código</label>
                <input
                    type="number"
                    class="form-control"
                    id="codigo"
                    name="codigo"
                    placeholder="Informe o código da turma"
                    required
                >
            </div>

            <div class="form-group">
                <label for="curso">Curso</label>
                <input
                    type="text"
                    class="form-control"
                    id="curso"
                    name="curso"
                    placeholder="Informe o nome do curso"
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
                    placeholder="Informe a sigla do curso"
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
                        placeholder="Informe o período"
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
                        placeholder="Informe a série"
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
                    placeholder="Informe a matriz curricular"
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
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>
            </div>

            <div class="d-flex flex-wrap justify-content-end mt-4">
                <a href="turmas.php" class="btn btn-secondary mr-2 mb-2">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary mb-2">
                    Cadastrar turma
                </button>
            </div>

        </form>
    </section>

</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
