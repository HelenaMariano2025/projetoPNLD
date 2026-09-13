<?php
session_start();
include 'php/conexao.php'; // Inclui o arquivo de conexão

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $matricula = $_POST["matricula"];
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];

    // Verifica se a matrícula já está cadastrada
    $sql_verificar = "SELECT * FROM administrador WHERE matricula = '$matricula'";
    $resultado_verificar = $conn->query($sql_verificar);
    if ($resultado_verificar->num_rows > 0) {
        echo "Matrícula já cadastrada.";
    } else {
        // Insere os dados do novo administrador no banco de dados
        $sql_inserir = "INSERT INTO administrador (matricula, nome, senha) VALUES ('$matricula','$nome', '$senha')";
        if ($conn->query($sql_inserir) === TRUE) {
            // Armazena o nome do administrador em uma variável de sessão
            $_SESSION['nome'] = $nome;
            $_SESSION['matricula'] = $matricula;

            // Redireciona para a tela de login
            header("Location: login.php");
            exit(); // Certifique-se de sair do script após o redirecionamento
        } else {
            echo "Erro ao cadastrar o administrador: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Cadastrar Administrador</title> 
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- Fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- Owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- Font Awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- Nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
    integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- Responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">

  <div class="hero_area">
    <!-- header section starts -->
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
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" >
              <img src="images/White and navy simple book store logo.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav  ">
                  <li class="nav-item ">
                    <a class="nav-link" href="index.html">HOME <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
              </div>
              <div class="quote_btn-container">
                <a href="login.php">
                  <i class="fa fa-user" aria-hidden="true"></i>
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
    <!-- end header section -->
    
    <!-- Formulário de Cadastro -->
    <section class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="heading_container">
            <h2>
              CADASTRAR-SE
            </h2>
          </div>
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Matrícula" name="matricula">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Nome" name="nome">
            </div>
            <div class="form-group">
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Senha" name="senha">
            </div>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cadastrar</button>
          </form>
        </div>
      </div>
    </section>
    <!-- Fim do Formulário de Cadastro -->
  </div>

  <!-- footer section -->
  
  <script src="js/jquery-3.4.1.min.js"></script>
 
  <script src="js/bootstrap.js"></script>
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

  <script src="js/custom.js"></script>

</body>

</html>
