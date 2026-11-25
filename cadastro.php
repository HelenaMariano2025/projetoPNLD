<?php

session_start();

require_once 'php/auth.php';
exigirAutenticacao();

require_once 'php/conexao.php';
require_once 'php/CadastroAdministradorRepository.php';
require_once 'php/CadastroAdministrador.php';

$mensagem = "";
$tipoMensagem = "";

$matricula = "";
$nome = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matricula = trim($_POST["matricula"] ?? "");
    $nome = trim($_POST["nome"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($matricula === "") {
        $mensagem = "A matrícula é obrigatória.";
        $tipoMensagem = "danger";
    } elseif ($nome === "") {
        $mensagem = "O nome é obrigatório.";
        $tipoMensagem = "danger";
    } elseif ($senha === "") {
        $mensagem = "A senha é obrigatória.";
        $tipoMensagem = "danger";
    } else {
        $repository = new CadastroAdministradorRepository($conn);
        $cadastro = new CadastroAdministrador($repository);

        $mensagem = $cadastro->cadastrar($matricula, $nome, $senha);

        if ($mensagem === "Administrador cadastrado com sucesso.") {
            $tipoMensagem = "success";
            $matricula = "";
            $nome = "";
        } else {
            $tipoMensagem = "danger";
        }
    }
}

$classeMensagem = in_array($tipoMensagem, ["success", "danger"], true)
    ? $tipoMensagem
    : "danger";

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar administrador | HBL Control</title>

    <link rel="icon" href="images/favicon.png" type="image/png">

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2d6a4f;
            --primary-dark: #1b4332;
            --primary-light: #e8f5e9;
            --background: #f8fafc;
            --white: #ffffff;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #dc3545;
            --danger-light: #fff1f2;
            --success: #198754;
            --success-light: #ecfdf3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            width: 100%;
            min-height: 78px;
            padding: 0 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--white);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .brand-name {
            color: var(--primary-dark);
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary-dark);
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 55px 20px;
        }

        .content-wrapper {
            width: 100%;
            max-width: 1050px;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            background: var(--white);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.07);
            border: 1px solid var(--border);
        }

        .info-panel {
            background: linear-gradient(
                145deg,
                var(--primary),
                var(--primary-dark)
            );
            color: var(--white);
            padding: 55px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-label {
            display: inline-block;
            width: fit-content;
            padding: 8px 13px;
            margin-bottom: 25px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.13);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .info-panel h1 {
            max-width: 340px;
            margin-bottom: 20px;
            font-size: 34px;
            line-height: 1.25;
            font-weight: 700;
        }

        .info-panel p {
            max-width: 350px;
            color: rgba(255, 255, 255, 0.83);
            font-size: 15px;
            line-height: 1.8;
        }

        .info-divider {
            width: 55px;
            height: 4px;
            margin-top: 32px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.75);
        }

        .form-panel {
            padding: 55px 55px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h2 {
            margin-bottom: 10px;
            color: var(--text);
            font-size: 27px;
            font-weight: 700;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            height: 49px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: none;
            background: #ffffff;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.12);
        }

        .alert {
            margin-bottom: 24px;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-success {
            color: #166534;
            background: var(--success-light);
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            color: #b91c1c;
            background: var(--danger-light);
            border: 1px solid #fecdd3;
        }

        .submit-button {
            width: 100%;
            min-height: 49px;
            margin-top: 8px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: var(--white);
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .submit-button:focus-visible,
        .back-link:focus-visible {
            outline: 3px solid rgba(45, 106, 79, 0.3);
            outline-offset: 3px;
        }

        .footer {
            padding: 22px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .topbar {
                min-height: 70px;
                padding: 0 5%;
            }

            .brand img {
                width: 43px;
                height: 43px;
            }

            .brand-name {
                font-size: 18px;
            }

            .content-wrapper {
                max-width: 520px;
                grid-template-columns: 1fr;
            }

            .info-panel {
                padding: 35px 30px;
            }

            .info-panel h1 {
                font-size: 27px;
            }

            .info-panel p {
                font-size: 14px;
            }

            .info-divider {
                margin-top: 22px;
            }

            .form-panel {
                padding: 38px 30px;
            }
        }

        @media (max-width: 420px) {
            .topbar {
                padding: 0 18px;
            }

            .brand-name {
                font-size: 16px;
            }

            .back-link {
                font-size: 12px;
            }

            .main-content {
                padding: 30px 14px;
            }

            .info-panel,
            .form-panel {
                padding: 30px 22px;
            }

            .form-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="page-container">

        <header class="topbar">

            <a href="funcoes.php" class="brand">
                <img
                    src="images/White and navy simple book store logo.png"
                    alt="Logo HBL Control"
                >
                <span class="brand-name">HBL Control</span>
            </a>

            <a href="funcoes.php" class="back-link">
                <span aria-hidden="true">&larr;</span>
                Voltar ao painel
            </a>

        </header>

        <main class="main-content">

            <div class="content-wrapper">

                <section class="info-panel">

                    <span class="info-label">Área administrativa</span>

                    <h1>Gerencie os acessos do sistema.</h1>

                    <p>
                        Cadastre novos administradores para permitir que
                        outros responsáveis tenham acesso às funcionalidades
                        de gerenciamento do HBL Control.
                    </p>

                    <div class="info-divider"></div>

                </section>

                <section class="form-panel">

                    <div class="form-header">

                        <h2>Cadastrar administrador</h2>

                        <p>
                            Preencha os campos abaixo para registrar um
                            novo administrador no sistema.
                        </p>

                    </div>

                    <?php if ($mensagem !== ""): ?>

                        <div
                            class="alert alert-<?php echo htmlspecialchars($classeMensagem, ENT_QUOTES, 'UTF-8'); ?>"
                            role="alert"
                        >
                            <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
                        </div>

                    <?php endif; ?>

                    <form
                        method="post"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>"
                    >

                        <div class="form-group">

                            <label for="matricula">Matrícula</label>

                            <input
                                type="text"
                                class="form-control"
                                id="matricula"
                                name="matricula"
                                placeholder="Digite a matrícula"
                                value="<?php echo htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8'); ?>"
                                autocomplete="username"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label for="nome">Nome completo</label>

                            <input
                                type="text"
                                class="form-control"
                                id="nome"
                                name="nome"
                                placeholder="Digite o nome completo"
                                value="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>"
                                autocomplete="name"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label for="senha">Senha</label>

                            <input
                                type="password"
                                class="form-control"
                                id="senha"
                                name="senha"
                                placeholder="Crie uma senha"
                                autocomplete="new-password"
                                required
                            >

                        </div>

                        <button type="submit" class="submit-button">
                            Cadastrar administrador
                        </button>

                    </form>

                </section>

            </div>

        </main>

        <footer class="footer">
            Sistema HBL Control &copy; <?php echo date("Y"); ?>
        </footer>

    </div>

</body>

</html>
