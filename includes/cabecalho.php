<?php
/**
 * Cabeçalho e Navegação Compartilhada - Sistema HBL Control (PNLD)
 * IFPB — Campus Patos
 */

$tituloPagina = $tituloPagina ?? 'Sistema HBL Control - Controle de Livros Didáticos';
$paginaAtiva = $paginaAtiva ?? '';
$usuarioAutenticado = !empty($_SESSION['matricula']);
$nomeUsuario = $_SESSION['nome'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
</head>
<body class="sub_page">

<header class="hbl-header">
    <div class="hbl-header-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="funcoes.php" class="hbl-brand-group">
                <img src="images/White and navy simple book store logo.png" alt="Logo do Sistema HBL Control" class="hbl-brand-logo">
                <div class="hbl-brand-titles">
                    <span class="hbl-brand-title">Sistema HBL Control</span>
                    <span class="hbl-brand-sub">IFPB — Campus Patos</span>
                </div>
            </a>
            <?php if ($usuarioAutenticado): ?>
                <div class="hbl-user-box">
                    <span class="hbl-user-info">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                        <span>Olá, <strong><?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></strong></span>
                    </span>
                    <a href="logout.php" class="hbl-btn-logout" title="Encerrar sessão no sistema">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span>Sair</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($usuarioAutenticado): ?>
        <nav class="hbl-navbar-bar">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg hbl-navbar">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#hblNavbarContent" aria-controls="hblNavbarContent" aria-expanded="false" aria-label="Alternar navegação">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <span class="ml-1" style="font-size: 0.85rem; font-weight: 500;">Menu</span>
                    </button>
                    <div class="collapse navbar-collapse" id="hblNavbarContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'inicio' ? 'active' : '' ?>" href="funcoes.php">
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                    <span>Início</span>
                                </a>
                            </li>
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'alunos' ? 'active' : '' ?>" href="aluno.php">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span>Alunos</span>
                                </a>
                            </li>
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'turmas' ? 'active' : '' ?>" href="turmas.php">
                                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                    <span>Turmas</span>
                                </a>
                            </li>
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'livros' ? 'active' : '' ?>" href="livros.php">
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <span>Livros</span>
                                </a>
                            </li>
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'emprestimos' ? 'active' : '' ?>" href="emprestimosDevolucoes.php">
                                    <i class="fa fa-exchange" aria-hidden="true"></i>
                                    <span>Empréstimos</span>
                                </a>
                            </li>
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'historico' ? 'active' : '' ?>" href="historico.php">
                                    <i class="fa fa-history" aria-hidden="true"></i>
                                    <span>Histórico</span>
                                </a>
                            </li>
                            <li class="nav-item hbl-nav-item">
                                <a class="hbl-nav-link <?= $paginaAtiva === 'administradores' ? 'active' : '' ?>" href="cadastro.php">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    <span>Administradores</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </nav>
    <?php endif; ?>
</header>
