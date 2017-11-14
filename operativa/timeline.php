<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once 'cliente.php';
require_once 'servicio.php';
require_once 'recursos.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();
$recursos=new Recursos();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Nuevo servicio</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/timeline.css">
</head>
<body>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
</head>

<div class="codrops-top clearfix">
  <?php
    //llamamos a la función para devolver el nombre de usuario.
    $nombreuser=$usuario->nombreUsuario($_SESSION['usuario']);
    //sacamos el nombre de usuario por su id
    echo "<a><strong>Bienvenido ".$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../logout.php" id='logout'>Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="index.php">Inicio</a>
        <a href="nuevoServicio.php">Nueva Actividad</a>
        <a href="actividadesActuales.php">Actividades Actuales</a>
        <a href="historicoActividades.php">Histórico Actividades</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Actividad X</h2>
        <div id="listaDerecha">
          <ul style="color: black;">
            <li>Fecha inicio:</li>
            <li>Modelos</li>
            <li>Recursos</li>
            <li>Cliente</li>
            <li>Responsable</li>
            <li>Telefono responsable</li>
            <li>Correo responsable</li>
          </ul>
        </div>
        <div id="listaIzquierda">
          <ul style="color: black;">
            <li>Comentario 1</li>
            <li>Comentario 2</li>
            <li>Comentario 3</li>
            <li>Comentario 4</li>
            <li>Archivo1</li>
            <li>Archivo 2</li>
            <li>Archivo 3</li>
            <li>Archivo 4</li>
            <li>Archivo 5</li>
          </ul>
        </div>

        <!-- Vertical Timeline -->
        <section id="conference-timeline">
          <div class="timeline-start">Start</div>
          <div class="conference-center-line"></div>
          <div class="conference-timeline-content">
            <!-- Article -->
            <div class="timeline-article">
              <div class="content-left-container">
                <div class="content-left">
                  <p>When I orbited the Earth in a spaceship, I saw for the first time how beautiful our planet is. Mankind, let us preserve and increase this beauty, and not destroy it! <span class="article-number">01</span></p>
                </div>
              </div>
            <!--  <div class="content-right-container">
                <div class="content-right">
                  <p>When I orbited the Earth in a spaceship, I saw for the first time how beautiful our planet is. <span class="article-number">02</span></p>
                </div>
              </div>-->
              <div class="meta-date">
                <span class="date">13-11-2017</span>
                <span class="date">15-11-2017</span>
              </div>
            </div>
            <!-- // Article -->

            <!-- Article -->
            <div class="timeline-article">
             <div class="content-left-container">
                <div class="content-left">
                  <p>When I orbited the Earth in a spaceship, I saw for the first time how beautiful our planet is. Mankind, let us preserve and increase this beauty, and not destroy it! <span class="article-number">01</span></p>
                </div>
              </div>
            <!--  <div class="content-right-container">
                <div class="content-right">
                  <p>When I orbited the Earth in a spaceship, I saw for the first time how beautiful our planet is. <span class="article-number">02</span></p>
                </div>
              </div>-->
              <div class="meta-date">
                <span class="month">13-11-2017</span>
              </div>
            </div>
            <!-- // Article -->

          </div>
          <div class="timeline-end">End</div>
        </section>
        <!-- // Vertical Timeline -->
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../js/menu.js"></script>

</body>
</html>
 <?php } ?>
