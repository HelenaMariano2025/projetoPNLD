<?php
session_start();
?>

<!DOCTYPE html>
<html>

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

  <title>Sistema HBL Control - Controle de Livros Didáticos</title> 
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  


  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body>

  <div class="hero_area">
    <!-- header section strats -->
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
            <a class="navbar-brand">
              <img src="images/White and navy simple book store logo.png" alt="">
            </a>


            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav  ">
                  <li class="nav-item active">
                    <a class="nav-link" href="funcoes.php">HOME<span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href="historico.php">HISTÓRICO DE EMPRESTIMOS<span class="sr-only">(current)</span></a>
                  </li>



                </ul>
              </div>
              <div class="quote_btn-container">

                <a href="index.html">
                  <i class="fa fa-user" aria-hidden="true"></i>
                  <span>
                    Sair
                  </span>
                  <?php
                  // Verifica se o nome do administrador está definido na sessão
                  if (isset($_SESSION['nome'])) {
                    echo "<p>Bem-vindo(a), " . $_SESSION['nome'] . "</p>";
                  } else {
                    echo "<p>Bem-vindo(a), Administrador</p>"; // Mensagem padrão caso o nome do administrador não esteja definido na sessão
                  }
                  ?>
                </a>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div class="dot_design">
        <img src="images/ .png" alt="">
      </div>
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-box">
                    <div class="">



                    </div>
                    <h1>
                      sistema <br>
                      <span>
                        BHL
                      </span>
                    </h1>
                    <p>
                      Apresentamos o Sistema BHL, uma ferramenta inovadora feita sob medida para facilitar a organização dos livros didáticos no Instituto Federal de Educação, Ciência e Tecnologia do Campus Patos. Com o BHL, vamos tornar mais fácil e eficiente o controle e a distribuição desses materiais, garantindo uma gestão mais tranquila para todos.
                    </p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                    <img src="images/R.png" alt="">
                  </div>
                </div>
              </div>
            </div>


            <!-- end about section -->


            <!-- treatment section -->

            <section class="treatment_section layout_padding">
              <div class="side_img">
                <img src="images/treatment-side-img.jpg" alt="">
              </div>
              <div class="container">
                <div class="heading_container heading_center">
                  <h2>
                    <span></span>
                  </h2>
                </div>
                <div class="row">
                  <div class="col-md-6 col-lg-3">
                    <div class="box ">
                      <div class="img-box">
                        <img src="images/addaluno.png" alt="">
                      </div>
                      <div class="detail-box">
                        <h4>
                          Aluno
                        </h4>
                        <a href="aluno.php">
                          Acessar
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="box ">
                      <div class="img-box">
                        <img src="images/addturma.png" alt="">
                      </div>
                      <div class="detail-box">
                        <h4>
                          Turma
                        </h4>
                        <a href="turmas.php">
                          Acessar
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="box ">
                      <div class="img-box">
                        <img src="images/addlivros.png" alt="">
                      </div>
                      <div class="detail-box">
                        <h4>
                          Livro
                        </h4>
                        <a href="livros.php">
                          Acessar
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="box ">
                      <div class="img-box">
                        <img src="images/addemp.png" alt="">
                      </div>
                      <div class="detail-box">
                        <h4>
                          Empréstimos +
                          Devoluções
                        </h4>
                        <a href="emprestimosDevolucoes.php">
                          Acessar
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- end treatment section -->
            <!-- contact section -->
</body>

</html>