<?php
session_start();

require_once 'php/auth.php';
exigirAutenticacao();

require_once 'php/conexao.php';
require_once 'php/TurmaRepository.php';

$repository = new TurmaRepository($conn);

$codigo = filter_input(INPUT_GET, 'codigo', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_VALIDATE_INT);
    $curso = trim($_POST['curso'] ?? '');
    $siglaCurso = trim($_POST['siglaCurso'] ?? '');
    $periodo = filter_input(INPUT_POST, 'periodo', FILTER_VALIDATE_INT);
    $serie = filter_input(INPUT_POST, 'serie', FILTER_VALIDATE_INT);
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
        header('Location: turmas.php');
        exit();
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
    header('Location: turmas.php');
    exit();
}

if (!$codigo) {
    $_SESSION['mensagem'] = 'Código de turma inválido.';
    header('Location: turmas.php');
    exit();
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
    header('Location: turmas.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Turma</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body class="sub_page">
    <div class="hero_area">
        <header class="header_section">
            <div class="header_bottom">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container">
                        <a class="navbar-brand" href="funcoes.php">
                            <img src="images/White and navy simple book store logo.png" alt="Página inicial">
                        </a>
                        <div class="navbar-collapse">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="funcoes.php">HOME</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="turmas.php">VOLTAR</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
    </div>

    <section class="contact_section layout_padding-bottom">
        <div class="container">
            <div class="heading_container">
                <h2><br>EDITAR TURMA</h2>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form_container">
                        <form action="editar_turma.php" method="post">
                            <input type="hidden" name="codigo"
                                   value="<?= (int) $turma['codigo'] ?>">

                            <div>
                                <input type="text" name="curso" placeholder="Curso"
                                       value="<?= htmlspecialchars($turma['curso'], ENT_QUOTES, 'UTF-8') ?>"
                                       required>
                            </div>
                            <div>
                                <input type="text" name="siglaCurso" placeholder="Sigla do curso"
                                       value="<?= htmlspecialchars($turma['siglaCurso'], ENT_QUOTES, 'UTF-8') ?>"
                                       required>
                            </div>
                            <div>
                                <input type="number" name="periodo" placeholder="Período"
                                       value="<?= (int) $turma['periodo'] ?>"
                                       min="1" required>
                            </div>
                            <div>
                                <input type="number" name="serie" placeholder="Série"
                                       value="<?= (int) $turma['serie'] ?>"
                                       min="1" required>
                            </div>
                            <div>
                                <input type="text" name="matrizCurricular" placeholder="Matriz curricular"
                                       value="<?= htmlspecialchars($turma['matrizCurricular'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                       required>
                            </div>
                            <div>
                                <select name="situacao" required>
                                    <option value="ativo" <?= $turma['situacao'] === 'ativo' ? 'selected' : '' ?>>
                                        Ativo
                                    </option>
                                    <option value="inativo" <?= $turma['situacao'] === 'inativo' ? 'selected' : '' ?>>
                                        Inativo
                                    </option>
                                </select>
                            </div>
                            <div class="btn_box">
                                <button type="submit">Salvar alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<?php $conn->close(); ?>