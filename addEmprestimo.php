<?php

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = trim($_POST['codLivro'] ?? '');
    $dataEmprestimo = $_POST['dataEmprestimo'] ?? '';
    $matricula = trim($_POST['matricula'] ?? '');
    $matriculaAdm = $_SESSION['matricula'] ?? null;

    if (!validarIdLivro($isbn) || !validarEmprestimo($matricula, $isbn)) {
        $_SESSION['mensagem'] = 'Preencha corretamente o livro e a matrícula do aluno.';
    } elseif (empty($matriculaAdm)) {
        $_SESSION['mensagem'] = 'Administrador não autenticado.';
    } else {
        $stmt = $conn->prepare(
            'SELECT codigo, qtde_disponivel
             FROM livro
             WHERE isbn = ?'
        );

        if ($stmt === false) {
            $_SESSION['mensagem'] = 'Erro ao consultar o livro.';
        } else {
            $stmt->bind_param('s', $isbn);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 0) {
                $_SESSION['mensagem'] = 'Livro não encontrado.';
            } else {
                $livro = $resultado->fetch_assoc();

                if (!podeEmprestar((int) $livro['qtde_disponivel'])) {
                    $_SESSION['mensagem'] = 'Não há exemplares disponíveis para empréstimo.';
                } else {
                    $codLivro = (int) $livro['codigo'];
                    $devolucao = calcularDataDevolucao($dataEmprestimo);

                    $repository = new EmprestimoRepository($conn);

                    $sucesso = $repository->registrarEmprestimo(
                        $codLivro,
                        $dataEmprestimo,
                        $devolucao,
                        (int) $matricula,
                        (int) $matriculaAdm
                    );

                    if ($sucesso) {
                        $_SESSION['mensagem'] = 'Livro emprestado com sucesso!';
                    } else {
                        $_SESSION['mensagem'] = 'Erro ao registrar o empréstimo.';
                    }
                }
            }

            $stmt->close();
        }
    }

    $conn->close();
}

$paginaAtiva = 'emprestimos';
$tituloPagina = 'Adicionar Empréstimo — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';

?>

<main class="container my-4">

    <section class="painel">
        <h1>Adicionar empréstimo</h1>
        <p class="text-muted mb-0">
            Preencha os dados abaixo para registrar um novo empréstimo de livro.
        </p>
    </section>

    <section class="painel">

        <div class="mb-4">
            <h2>Dados do empréstimo</h2>
            <p class="text-muted mb-0">
                Informe o livro, a data do empréstimo e a matrícula do aluno.
            </p>
        </div>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info" role="alert">
                <?= htmlspecialchars($_SESSION['mensagem'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <form action="addEmprestimo.php" method="post">

            <div class="form-group">
                <label for="codLivro">Código do livro (ISBN)</label>
                <input
                    type="number"
                    class="form-control"
                    id="codLivro"
                    name="codLivro"
                    placeholder="Informe o ISBN do livro"
                    required
                >
            </div>

            <div class="form-group">
                <label for="dataEmprestimo">Data do empréstimo</label>
                <input
                    type="date"
                    class="form-control"
                    id="dataEmprestimo"
                    name="dataEmprestimo"
                    value="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="matricula">Matrícula do aluno</label>
                <input
                    type="number"
                    class="form-control"
                    id="matricula"
                    name="matricula"
                    placeholder="Informe a matrícula do aluno"
                    required
                >
            </div>

            <div class="d-flex flex-wrap justify-content-end mt-4">
                <a href="emprestimosDevolucoes.php" class="btn btn-secondary mr-2 mb-2">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary mb-2">
                    Registrar empréstimo
                </button>
            </div>

        </form>
    </section>

</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
