<?php
require_once 'php/http.php';

iniciarSessao();

require_once 'php/conexao.php';
require_once 'php/AdministradorRepository.php';
require_once 'php/regras.php';

$repository = new AdministradorRepository($conn);
$erro = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matricula = trim($_POST["matricula"] ?? '');
    $senha = $_POST["senha"] ?? '';

    if (!validarCampoObrigatorio($matricula) || !validarCampoObrigatorio($senha)) {
        $erro = "Preencha sua matrícula e senha para continuar.";
    } else {
        $administrador = autenticarAdministrador(
            $matricula,
            $senha,
            $repository
        );

        if ($administrador !== null) {
            regenerarSessao();

            $_SESSION["matricula"] = $administrador["matricula"];
            $_SESSION["nome"] = $administrador["nome"];

            if (
                isset($_COOKIE['cookies_aceitos']) &&
                $_COOKIE['cookies_aceitos'] === 'true'
            ) {
                definirCookie("matricula", $administrador["matricula"]);
                definirCookie("nome", $administrador["nome"]);
            }

            redirecionar("funcoes.php");
        }

        $erro = "Matrícula ou senha inválida. Verifique seus dados e tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Entrar | Sistema HBL Control</title>

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --verde-principal: #2d6a4f;
            --verde-hover: #1b4332;
            --verde-claro: #e8f5e9;
            --fundo: #f8fafc;
            --branco: #ffffff;
            --texto: #1e293b;
            --texto-secundario: #64748b;
            --borda: #e2e8f0;
            --erro: #b42318;
            --erro-fundo: #fef3f2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--fundo);
            color: var(--texto);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .pagina {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .cabecalho {
            width: 100%;
            min-height: 82px;
            background: var(--branco);
            border-bottom: 1px solid var(--borda);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 7%;
        }

        .marca {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .marca img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .marca-texto {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .marca-texto strong {
            font-size: 19px;
            color: var(--verde-principal);
            font-weight: 800;
        }

        .marca-texto span {
            font-size: 11px;
            color: var(--texto-secundario);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .voltar {
            color: var(--texto-secundario);
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .voltar:hover {
            color: var(--verde-principal);
        }

        .conteudo {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 55px 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1050px;
            min-height: 550px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--branco);
            border: 1px solid var(--borda);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.06);
        }

        .painel-info {
            background: var(--verde-principal);
            color: var(--branco);
            padding: 65px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .painel-info::before,
        .painel-info::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50%;
        }

        .painel-info::before {
            width: 330px;
            height: 330px;
            right: -160px;
            top: -100px;
        }

        .painel-info::after {
            width: 260px;
            height: 260px;
            left: -150px;
            bottom: -100px;
        }

        .painel-conteudo {
            position: relative;
            z-index: 1;
        }

        .icone-livro {
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            margin-bottom: 32px;
            font-size: 31px;
        }

        .painel-info h1 {
            font-size: 31px;
            line-height: 1.3;
            margin-bottom: 18px;
            font-weight: 800;
        }

        .painel-info p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
            line-height: 1.8;
            max-width: 380px;
        }

        .painel-detalhe {
            margin-top: 38px;
            padding-top: 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
        }

        .painel-formulario {
            padding: 65px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .etiqueta {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            padding: 7px 12px;
            background: var(--verde-claro);
            color: var(--verde-principal);
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .painel-formulario h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .subtitulo {
            color: var(--texto-secundario);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .campo {
            margin-bottom: 21px;
        }

        .campo label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 9px;
            color: var(--texto);
        }

        .campo input {
            width: 100%;
            height: 49px;
            padding: 0 15px;
            border: 1px solid var(--borda);
            border-radius: 8px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            color: var(--texto);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .campo input::placeholder {
            color: #94a3b8;
        }

        .campo input:focus {
            border-color: var(--verde-principal);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.10);
        }

        .botao-entrar {
            width: 100%;
            height: 49px;
            margin-top: 8px;
            border: none;
            border-radius: 8px;
            background: var(--verde-principal);
            color: var(--branco);
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .botao-entrar:hover {
            background: var(--verde-hover);
            transform: translateY(-1px);
        }

        .mensagem-erro {
            padding: 13px 15px;
            margin-bottom: 22px;
            border: 1px solid #fecdca;
            border-radius: 8px;
            background: var(--erro-fundo);
            color: var(--erro);
            font-size: 13px;
            line-height: 1.5;
        }

        .rodape {
            padding: 22px 20px;
            text-align: center;
            color: var(--texto-secundario);
            font-size: 12px;
            border-top: 1px solid var(--borda);
            background: var(--branco);
        }

        #cookieConsent {
            display: none;
            position: fixed;
            z-index: 1000;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 600px;
            padding: 17px 20px;
            background: var(--branco);
            border: 1px solid var(--borda);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.15);
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        #cookieConsent p {
            color: var(--texto-secundario);
            font-size: 12px;
            line-height: 1.5;
        }

        #acceptCookies {
            flex-shrink: 0;
            padding: 10px 17px;
            background: var(--verde-principal);
            color: var(--branco);
            border: none;
            border-radius: 7px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        #acceptCookies:hover {
            background: var(--verde-hover);
        }

        @media (max-width: 760px) {
            .cabecalho {
                padding: 0 5%;
                min-height: 72px;
            }

            .login-container {
                max-width: 480px;
                grid-template-columns: 1fr;
            }

            .painel-info {
                padding: 35px 30px;
            }

            .icone-livro {
                width: 52px;
                height: 52px;
                font-size: 24px;
                margin-bottom: 20px;
            }

            .painel-info h1 {
                font-size: 24px;
                margin-bottom: 10px;
            }

            .painel-info p {
                font-size: 13px;
            }

            .painel-detalhe {
                margin-top: 20px;
                padding-top: 15px;
            }

            .painel-formulario {
                padding: 40px 30px;
            }

            .conteudo {
                padding: 30px 15px;
            }
        }

        @media (max-width: 420px) {
            .marca-texto strong {
                font-size: 16px;
            }

            .marca img {
                width: 42px;
                height: 42px;
            }

            .voltar {
                font-size: 12px;
            }

            .painel-formulario h2 {
                font-size: 24px;
            }

            #cookieConsent {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>

    <div class="pagina">

        <header class="cabecalho">
            <a href="index.html" class="marca">
                <img src="images/White and navy simple book store logo.png" alt="Logo do Sistema HBL Control">

                <div class="marca-texto">
                    <strong>HBL Control</strong>
                    <span>Gestão de livros didáticos</span>
                </div>
            </a>

            <a href="index.html" class="voltar">
                Voltar ao início
            </a>
        </header>

        <main class="conteudo">

            <div class="login-container">

                <section class="painel-info">
                    <div class="painel-conteudo">

                        <div class="icone-livro">
                            <span aria-hidden="true">📚</span>
                        </div>

                        <h1>Organização e controle em um só lugar.</h1>

                        <p>
                            O Sistema HBL Control facilita o gerenciamento
                            de alunos, turmas, livros didáticos e empréstimos,
                            contribuindo para uma gestão mais organizada e eficiente.
                        </p>

                        <div class="painel-detalhe">
                            Sistema de gerenciamento de livros didáticos
                        </div>

                    </div>
                </section>

                <section class="painel-formulario">

                    <span class="etiqueta">Área administrativa</span>

                    <h2>Bem-vindo!</h2>

                    <p class="subtitulo">
                        Entre com suas credenciais para acessar o sistema.
                    </p>

                    <?php if ($erro !== ''): ?>
                        <div class="mensagem-erro" role="alert">
                            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8') ?>">

                        <div class="campo">
                            <label for="matricula">Matrícula</label>

                            <input
                                type="text"
                                id="matricula"
                                name="matricula"
                                placeholder="Digite sua matrícula"
                                value="<?= htmlspecialchars($matricula ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                autocomplete="username"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="senha">Senha</label>

                            <input
                                type="password"
                                id="senha"
                                name="senha"
                                placeholder="Digite sua senha"
                                autocomplete="current-password"
                                required
                            >
                        </div>

                        <button type="submit" class="botao-entrar">
                            Entrar no sistema
                        </button>

                    </form>

                </section>

            </div>

        </main>

        <footer class="rodape">
            Sistema HBL Control — Gestão de livros didáticos
        </footer>

    </div>

    <div id="cookieConsent">
        <p>
            Utilizamos cookies para melhorar sua experiência.
            Você pode aceitar o armazenamento de suas preferências.
        </p>

        <button id="acceptCookies" type="button">
            Aceitar
        </button>
    </div>

    <script>
        function verificarConsentimento() {
            return document.cookie
                .split(';')
                .some(cookie => cookie.trim().startsWith('cookies_aceitos=true'));
        }

        function aceitarCookies() {
            document.cookie =
                "cookies_aceitos=true; path=/; max-age=" + (365 * 24 * 60 * 60) + "; SameSite=Lax";

            document.getElementById('cookieConsent').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const banner = document.getElementById('cookieConsent');
            const botao = document.getElementById('acceptCookies');

            if (!verificarConsentimento()) {
                banner.style.display = 'flex';
            }

            botao.addEventListener('click', aceitarCookies);
        });
    </script>

</body>
</html>
