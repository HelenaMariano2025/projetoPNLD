<?php
session_start();

include 'php/conexao.php';
require_once 'php/regras.php';
require_once 'php/regras_emprestimo.php';
require_once 'php/EmprestimoRepository.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = trim($_POST['codLivro'] ?? '');
    $dataEmprestimo = $_POST['dataEmprestimo'] ?? '';
    $matricula = trim($_POST['matricula'] ?? '');
    $matriculaAdm = $_SESSION['matricula'] ?? null;

    if (!validarIdLivro($isbn) || !validarEmprestimo($matricula, $isbn)) {
        $_SESSION['mensagem'] = "Preencha corretamente o livro e a matrícula do aluno.";
    } elseif (empty($matriculaAdm)) {
        $_SESSION['mensagem'] = "Administrador não autenticado.";
    } else {
        $stmt = $conn->prepare(
            'SELECT codigo, qtde_disponivel
             FROM livro
             WHERE isbn = ?'
        );

        if ($stmt === false) {
            $_SESSION['mensagem'] = "Erro ao consultar o livro.";
        } else {
            $stmt->bind_param('s', $isbn);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 0) {
                $_SESSION['mensagem'] = "Livro não encontrado.";
            } else {
                $livro = $resultado->fetch_assoc();

                if (!podeEmprestar((int) $livro['qtde_disponivel'])) {
                    $_SESSION['mensagem'] = "Não há exemplares disponíveis para empréstimo.";
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
                        $_SESSION['mensagem'] = "Livro emprestado com sucesso!";
                    } else {
                        $_SESSION['mensagem'] = "Erro ao registrar o empréstimo.";
                    }
                }
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Adicionar Emprestimo de Livro</title>

  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">

  <div class="hero_area">

    <header class="header_section">
      <div class="header_top">
        <div class="container">
          <div class="contact_nav">
            <a href="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Contato : +01 123455678990
              </span>
            </a>

            <a href="">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                Email : ifpb@gmail.com
              </span>
            </a>

            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                <a href="https://www.bing.com/maps?osid=2d9cc22c-c352-4b15-84e1-a645eaf97d8a&cp=-7.025562~-37.280592&lvl=17&pi=0&v=2&sV=2&form=S00027">Localização</a>
              </span>
            </a>
          </div>
        </div>
      </div>

      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container">
            <a class="navbar-brand">
              <img src="images/White and navy simple book store logo.png" alt="">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link" href="funcoes.php">HOME <span class="sr-only">(current)</span></a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="emprestimosDevolucoes.php">VOLTAR <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
              </div>

              <div class="quote_btn-container">
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header>

  </div>

  <section class="contact_section layout_padding-bottom">
    <div class="container">

      <div class="heading_container">
        <h2><br/>
          Adicionar Emprestimo de Livro
        </h2>
      </div>

      <div class="row">

        <div class="col-md-7">
          <div class="form_container">

            <?php
            if (isset($_SESSION['mensagem'])) {
                echo "<p>" . htmlspecialchars($_SESSION['mensagem']) . "</p>";
                unset($_SESSION['mensagem']);
            }
            ?>

            <form action="addEmprestimo.php" method="post">

              <div>
                <input
                  type="number"
                  name="codLivro"
                  placeholder="Código do Livro"
                  required
                />
              </div>

              <div>
                <input
                  type="date"
                  name="dataEmprestimo"
                  placeholder="Data de Emprestimo"
                  value="<?= date("Y-m-d") ?>"
                  required
                />
              </div>

              <div>
                <input
                  type="number"
                  name="matricula"
                  placeholder="Matricula Aluno"
                  required
                />
              </div>

              <div class="btn_box">
                <button type="submit">
                  Adicionar
                </button>
              </div>

            </form>

          </div>
        </div>

        <div class="col-md-5">
          <div class="img-box">
            <img src="images/.png" alt="">
          </div>
        </div>

      </div>
    </div>
  </section>

</body>

</html>
