<?php

session_start();

include 'php/conexao.php';
require_once 'php/CadastroAdministradorRepository.php';
require_once 'php/CadastroAdministrador.php';

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        $mensagem = $cadastro->cadastrar(
            $matricula,
            $nome,
            $senha
        );

        if ($mensagem === "Administrador cadastrado com sucesso.") {
            $tipoMensagem = "success";

            $_SESSION['nome'] = $nome;
            $_SESSION['matricula'] = $matricula;
        } else {
            $tipoMensagem = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >

    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cadastrar Administrador</title>

    <link
        rel="shortcut icon"
        href="images/favicon.ico"
        type="image/x-icon"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="css/bootstrap.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
    >

    <link
        href="css/font-awesome.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4="
        crossorigin="anonymous"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css"
    >

    <link
        href="css/style.css"
        rel="stylesheet"
    >

    <link
        href="css/responsive.css"
        rel="stylesheet"
    >

</head>

<body class="sub_page">

    <div class="hero_area">

        <header class="header_section">

            <div class="header_top">

                <div class="container">

                    <div class="contact_nav">

                        <a href="">
                            <i
                                class="fa fa-phone"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Contato : +01 123455678990
                            </span>
                        </a>

                        <a href="">
                            <i
                                class="fa fa-envelope"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Email : ifpb@gmail.com
                            </span>
                        </a>

                        <a href="">
                            <i
                                class="fa fa-map-marker"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Localização
                            </span>
                        </a>

                    </div>

                </div>

            </div>

            <div class="header_bottom">

                <div class="container-fluid">

                    <nav class="navbar navbar-expand-lg custom_nav-container">

                        <a class="navbar-brand">

                            <img
                                src="images/White and navy simple book store logo.png"
                                alt=""
                            >

                        </a>

                        <button
                            class="navbar-toggler"
                            type="button"
                            data-toggle="collapse"
                            data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent"
                            aria-expanded="false"
                            aria-label="Toggle navigation"
                        >
                            <span></span>
                        </button>

                        <div
                            class="collapse navbar-collapse"
                            id="navbarSupportedContent"
                        >

                            <div
                                class="d-flex mr-auto flex-column flex-lg-row align-items-center"
                            >

                                <ul class="navbar-nav">

                                    <li class="nav-item">

                                        <a
                                            class="nav-link"
                                            href="index.html"
                                        >
                                            HOME
                                            <span class="sr-only">
                                                (current)
                                            </span>
                                        </a>

                                    </li>

                                </ul>

                            </div>

                            <div class="quote_btn-container">

                                <a href="login.php">

                                    <i
                                        class="fa fa-user"
                                        aria-hidden="true"
                                    ></i>

                                    <span>
                                        Login
                                    </span>

                                </a>

                            </div>

                        </div>

                    </nav>

                </div>

            </div>

        </header>

        <section class="container mt-5">

            <div class="row justify-content-center">

                <div class="col-md-6">

                    <div class="heading_container">

                        <h2>
                            CADASTRAR-SE
                        </h2>

                    </div>

                    <?php if ($mensagem !== ""): ?>

                        <div
                            class="alert alert-<?php echo htmlspecialchars($tipoMensagem); ?>"
                        >
                            <?php echo htmlspecialchars($mensagem); ?>
                        </div>

                    <?php endif; ?>

                    <form
                        method="post"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                    >

                        <div class="form-group">

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Matrícula"
                                name="matricula"
                                value="<?php echo htmlspecialchars($_POST["matricula"] ?? ""); ?>"
                            >

                        </div>

                        <div class="form-group">

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Nome"
                                name="nome"
                                value="<?php echo htmlspecialchars($_POST["nome"] ?? ""); ?>"
                            >

                        </div>

                        <div class="form-group">

                            <input
                                type="password"
                                class="form-control"
                                placeholder="Senha"
                                name="senha"
                            >

                        </div>

                        <button
                            class="btn btn-outline-success my-2 my-sm-0"
                            type="submit"
                        >
                            Cadastrar
                        </button>

                    </form>

                </div>

            </div>

        </section>

    </div>

    <script src="js/jquery-3.4.1.min.js"></script>

    <script src="js/bootstrap.js"></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"
        integrity="sha256-Zr3vByTlMGHq2QlspKYJnkjZTmo="
        crossorigin="anonymous"
    ></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    ></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"
    ></script>

    <script src="js/custom.js"></script>

</body>

</html>