<?php

session_start();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

require_once __DIR__ . '/php/conexao.php';

$paginaAtiva = 'inicio';
$tituloPagina = 'Visão geral — Sistema HBL Control';

$consultarTotal = static function (mysqli $conn, string $sql): ?int {
    try {
        $resultado = $conn->query($sql);

        if ($resultado === false) {
            return null;
        }

        $linha = $resultado->fetch_assoc();
        $resultado->free();

        return $linha !== null ? (int) $linha['total'] : null;
    } catch (mysqli_sql_exception $e) {
        error_log('Falha ao consultar indicador do dashboard: ' . $e->getMessage());
        return null;
    }
};

$totalAlunos = $consultarTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM aluno WHERE situacao = 'ativo'"
);

$totalTurmas = $consultarTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM turma WHERE situacao = 'ativo'"
);

$totalLivros = $consultarTotal(
    $conn,
    "SELECT COUNT(*) AS total FROM livro WHERE situacao = 'ativo'"
);

$totalEmprestimos = $consultarTotal(
    $conn,
    'SELECT COUNT(*) AS total
     FROM emprestimo e
     LEFT JOIN devolucao d
       ON d.codigo_emprestimo = e.codigo_emprestimo
     WHERE d.codigo_emprestimo IS NULL'
);

require_once __DIR__ . '/includes/cabecalho.php';

$indicadores = [
    [
        'titulo' => 'Alunos ativos',
        'valor' => $totalAlunos,
        'descricao' => 'Alunos cadastrados no sistema',
        'icone' => 'fa-users',
        'cor' => 'alunos',
    ],
    [
        'titulo' => 'Turmas ativas',
        'valor' => $totalTurmas,
        'descricao' => 'Turmas disponíveis para gestão',
        'icone' => 'fa-graduation-cap',
        'cor' => 'turmas',
    ],
    [
        'titulo' => 'Livros ativos',
        'valor' => $totalLivros,
        'descricao' => 'Títulos cadastrados como ativos',
        'icone' => 'fa-book',
        'cor' => 'livros',
    ],
    [
        'titulo' => 'Empréstimos em andamento',
        'valor' => $totalEmprestimos,
        'descricao' => 'Empréstimos sem registro de devolução',
        'icone' => 'fa-exchange',
        'cor' => 'emprestimos',
    ],
];

$atalhos = [
    [
        'titulo' => 'Alunos',
        'descricao' => 'Consulte e gerencie os alunos cadastrados.',
        'href' => 'aluno.php',
        'icone' => 'fa-users',
    ],
    [
        'titulo' => 'Turmas',
        'descricao' => 'Acesse as turmas e seus dados.',
        'href' => 'turmas.php',
        'icone' => 'fa-graduation-cap',
    ],
    [
        'titulo' => 'Livros',
        'descricao' => 'Consulte o acervo de livros didáticos.',
        'href' => 'livros.php',
        'icone' => 'fa-book',
    ],
    [
        'titulo' => 'Empréstimos e devoluções',
        'descricao' => 'Acompanhe empréstimos e registre devoluções.',
        'href' => 'emprestimosDevolucoes.php',
        'icone' => 'fa-exchange',
    ],
    [
        'titulo' => 'Histórico',
        'descricao' => 'Consulte o histórico de movimentações.',
        'href' => 'historico.php',
        'icone' => 'fa-history',
    ],
];
?>

<main class="hbl-dashboard">
    <div class="container-fluid hbl-dashboard-container">
        <section class="hbl-dashboard-heading" aria-labelledby="dashboard-title">
            <div>
                <span class="hbl-eyebrow">PAINEL ADMINISTRATIVO</span>
                <h1 id="dashboard-title">Visão geral</h1>
                <p>Acompanhe os principais dados e acesse as áreas de gestão do Sistema HBL Control.</p>
            </div>
            <div class="hbl-dashboard-date">
                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                <span><?= htmlspecialchars(date('d/m/Y'), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </section>

        <section class="hbl-dashboard-section" aria-labelledby="indicadores-title">
            <div class="hbl-section-heading">
                <div>
                    <h2 id="indicadores-title">Resumo do sistema</h2>
                    <p>Indicadores atualizados a partir dos registros do banco de dados.</p>
                </div>
            </div>

            <div class="hbl-stats-grid">
                <?php foreach ($indicadores as $indicador): ?>
                    <article class="hbl-stat-card">
                        <div class="hbl-stat-top">
                            <span class="hbl-stat-label">
                                <?= htmlspecialchars($indicador['titulo'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                            <span class="hbl-stat-icon hbl-stat-icon--<?= htmlspecialchars($indicador['cor'], ENT_QUOTES, 'UTF-8') ?>">
                                <i class="fa <?= htmlspecialchars($indicador['icone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
                            </span>
                        </div>
                        <strong class="hbl-stat-value">
                            <?= $indicador['valor'] === null ? '—' : number_format($indicador['valor'], 0, ',', '.') ?>
                        </strong>
                        <span class="hbl-stat-description">
                            <?= htmlspecialchars($indicador['descricao'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <?php if ($indicador['valor'] === null): ?>
                            <span class="hbl-stat-warning">Não foi possível consultar este indicador.</span>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="hbl-dashboard-section hbl-quick-access" aria-labelledby="atalhos-title">
            <div class="hbl-section-heading">
                <div>
                    <h2 id="atalhos-title">Acesso rápido</h2>
                    <p>Escolha uma área para continuar o trabalho.</p>
                </div>
            </div>

            <div class="hbl-shortcuts-grid">
                <?php foreach ($atalhos as $atalho): ?>
                    <a class="hbl-shortcut-card" href="<?= htmlspecialchars($atalho['href'], ENT_QUOTES, 'UTF-8') ?>">
                        <span class="hbl-shortcut-icon">
                            <i class="fa <?= htmlspecialchars($atalho['icone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
                        </span>
                        <span class="hbl-shortcut-content">
                            <strong><?= htmlspecialchars($atalho['titulo'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span><?= htmlspecialchars($atalho['descricao'], ENT_QUOTES, 'UTF-8') ?></span>
                        </span>
                        <i class="fa fa-angle-right hbl-shortcut-arrow" aria-hidden="true"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
