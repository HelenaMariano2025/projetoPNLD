<?php

const FORMATO_DATA = 'd/m/Y';

require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/php/conexao.php';

$historico = [];
$erroConsulta = false;

try {
    $query = "
        SELECT
            emprestimo.codigo_emprestimo,
            livro.titulo AS livro,
            aluno.nome AS aluno,
            aluno.matricula,
            emprestimo.dataEmprestimo,
            emprestimo.dataDevolucao,
            devolucao.dataDevolucao AS data_devolucao_real
        FROM emprestimo
        INNER JOIN livro
            ON emprestimo.codigo_livro = livro.codigo
        INNER JOIN aluno
            ON emprestimo.matricula_aluno = aluno.matricula
        LEFT JOIN devolucao
            ON emprestimo.codigo_emprestimo = devolucao.codigo_emprestimo
        ORDER BY emprestimo.codigo_emprestimo DESC
    ";

    $resultado = $conn->query($query);
    $historico = $resultado->fetch_all(MYSQLI_ASSOC);

    $resultado->free();
} catch (mysqli_sql_exception $erro) {
    $erroConsulta = true;
}

if (!defined('TESTE_PAGINA_HISTORICO')) {
    $conn->close();
}

$paginaAtiva = 'historico';

$tituloPagina = 'Histórico de Empréstimos — Sistema HBL Control';

require_once __DIR__ . '/includes/cabecalho.php';
?>

<main class="container my-4">

    <section class="painel">
        <h1>Histórico de empréstimos</h1>
        <p class="text-muted mb-0">
            Consulte os empréstimos realizados, as datas previstas e a situação das devoluções.
        </p>
    </section>

    <section class="painel">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h2>Relatório de empréstimos</h2>
                <p class="text-muted mb-0">
                    Acompanhe todos os registros de empréstimos do sistema.
                </p>
            </div>

            <?php if (!$erroConsulta): ?>
                <span class="text-muted">
                    <?= count($historico) ?>
                    <?= count($historico) === 1 ? 'registro' : 'registros' ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if ($erroConsulta): ?>

            <div class="alert alert-danger" role="alert">
                Não foi possível carregar o histórico de empréstimos.
                Tente novamente mais tarde.
            </div>

        <?php elseif (empty($historico)): ?>

            <div class="alert alert-info mb-0" role="alert">
                Nenhum registro de empréstimo encontrado.
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Livro</th>
                            <th scope="col">Aluno</th>
                            <th scope="col">Matrícula</th>
                            <th scope="col">Data do empréstimo</th>
                            <th scope="col">Data prevista</th>
                            <th scope="col">Data da devolução</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($historico as $row): ?>

                            <?php
                            $dataEmprestimo = date(
                                FORMATO_DATA,
                                strtotime($row['dataEmprestimo'])
                            );

                            $dataPrevista = date(
                                FORMATO_DATA,
                                strtotime($row['dataDevolucao'])
                            );

                            if (!empty($row['data_devolucao_real'])) {
                                $dataDevolucao = date(
                                    FORMATO_DATA,
                                    strtotime($row['data_devolucao_real'])
                                );

                                $status = 'Devolvido';
                                $classeStatus = 'badge-success';
                            } else {
                                $dataDevolucao = '-';

                                if (strtotime($row['dataDevolucao']) < time()) {
                                    $status = 'Atrasado';
                                    $classeStatus = 'badge-danger';
                                } else {
                                    $status = 'Em andamento';
                                    $classeStatus = 'badge-warning';
                                }
                            }
                            ?>

                            <tr>
                                <td>
                                    <?= (int) $row['codigo_emprestimo'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['livro'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string) $row['matricula'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($dataEmprestimo, ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($dataPrevista, ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($dataDevolucao, ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <span class="badge <?= $classeStatus ?>">
                                        <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </section>

</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>

