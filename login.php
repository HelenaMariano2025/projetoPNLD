<?php
session_start();
include 'php/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST["matricula"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM administrador WHERE matricula = '$matricula'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows == 1) {
        $row = $resultado->fetch_assoc();

        if ($senha === $row["senha"]) {
            $_SESSION["matricula"] = $matricula;
            $_SESSION["nome"] = $row["nome"];

            if (
                isset($_COOKIE['cookies_aceitos']) &&
                $_COOKIE['cookies_aceitos'] == 'true'
            ) {
                setcookie("matricula", $matricula, time() + 604800, "/");
                setcookie("nome", $row["nome"], time() + 604800, "/");
            }

            header("Location: funcoes.php");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Matrícula não encontrada.";
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

  <title>Autenticar Administrador</title> 
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

  <!-- Banner de Consentimento de Cookies -->
  <style>
    #cookieConsent {
      display: none;
      position: fixed;
      bottom: 0;
      width: 100%;
      background-color: #000;
      color: white;
      text-align: center;
      padding: 10px;
    }
  </style>
</head>

<body class="sub_page">
  <!-- Banner de Consentimento de Cookies -->
  <div id="cookieConsent">
    Este site utiliza cookies para melhorar sua experiência. 
    <button id="acceptCookies" style="margin-left: 15px; background-color: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer;">Aceitar</button>
  </div>

  <script>
    // Verifica se o cookie de consentimento já foi definido
    function checkCookieConsent() {
        return document.cookie.split(';').some((cookie) => cookie.trim().startsWith('cookies_aceitos='));
    }

    // Função para ocultar o banner e definir o cookie de consentimento
    function acceptCookies() {
        // Define o cookie de consentimento que expira em 365 dias
        document.cookie = "cookies_aceitos=true; path=/; max-age=" + (365*24*60*60);
        document.getElementById('cookieConsent').style.display = 'none';
    }

    // Exibe o banner se o consentimento ainda não foi dado
    window.onload = function() {
        if (!checkCookieConsent()) {
            document.getElementById('cookieConsent').style.display = 'block';
        }

        // Ação ao clicar no botão "Aceitar"
        document.getElementById('acceptCookies').onclick = function() {
            acceptCookies();
        }
    }
  </script>
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
              <img src="images/White and navy simple book store logo.png"alt="">
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

                
                  </li>
                </ul>
              </div>
              <div class="quote_btn-container">
                <a href="cadastro.php">
                  <i class="fa fa-user" aria-hidden="true"></i>
                  <span>
                    Cadastro
                  </span>
                </a>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <!-- end header section -->
    
    <!-- Formulário de Login -->
    <section class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="heading_container">
            <h2>
              AUTENTICAÇÃO
            </h2>
          </div>
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Matrícula" name="matricula">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Senha" name="senha">
            </div>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Entrar</button>
          </form>
        </div>
      </div>
    </section>
    <!-- Fim do Formulário de Login -->
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
