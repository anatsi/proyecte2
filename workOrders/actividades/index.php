<?php
//header("Refresh: 30; URL='movimientosJockeys.php'");

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../../ddbb/sesiones.php';
require_once '../../ddbb/users.php';
require_once '../bbdd/campa.php';

$usuario=new User();
$sesion=new Sesiones();
$campa = new Campa();


if (isset($_SESSION['usuario'])==false) {
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>WORK ORDERS</title>
    <link rel="stylesheet" href="../../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../../css/formulario.css">
    <link rel="shortcut icon" href="../../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
    <link rel="stylesheet" href="../../css/modificar.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style media="screen">
      tr:nth-child(even) {
        background-color: #CAC6C5;
      }
    </style>
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
  <span class="right"><a href="../../logout.php" id="logout">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../../dashboard.php" class="header__logo"><img src="../../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <?php
        $menu=$usuario->menuDash($_SESSION['usuario']);
        $opciones = explode(",", $menu['menu']);
        foreach ($opciones as $opcion) {
          if ($opcion == 51) {
            echo "<a href='registroCampa.php'>Circuito</a>";
          }elseif ($opcion == 52) {
            echo "<a href='registroClatter.php'>Clatter</a>";
          }elseif ($opcion == 53) {
            echo "<a href='registroDisengagement.php'>Disengagement</a>";
          }elseif ($opcion == 54) {
            echo "<a href='registroMotor.php'>Weekend 25</a>";
          }elseif ($opcion == 55) {
            echo "<a href='registroPuerta.php'>Puerta</a>";
          }elseif ($opcion == 56) {
            echo "<a href='registroRadio.php'>Bridas</a>";
          }elseif ($opcion == 57) {
            echo "<a href='registroReallocation.php'>Reallocation</a>";
          }elseif ($opcion == 58) {
            echo "<a href='registroWow.php'>Howling</a>";
          }elseif ($opcion == 59) {
            echo "<a href='registroWrap.php'>Wrap guard</a>";
          }elseif ($opcion == 0) {
            echo "<a href='registroCampa.php'>Circuito</a>";
            echo "<a href='registroClatter.php'>Clatter</a>";
            echo "<a href='registroDisengagement.php'>Disengagement</a>";
            echo "<a href='registroMotor.php'>Weekend 25</a>";
            echo "<a href='registroPuerta.php'>Puerta</a>";
            echo "<a href='registroRadio.php'>Bridas</a>";
            echo "<a href='registroReallocation.php'>Reallocation</a>";
            echo "<a href='registroWow.php'>Howling</a>";
            echo "<a href='registroWrap.php'>Wrap guard</a>";
          }
        }
         ?>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%;">
          <a href="../../dashboard.php">INICIO</a> >> <a href="index.php">REGISTRO ACTIVIDADES</a>
        </div>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->


<!-- Scripts para que el menu en versión movil funcione-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../../js/menu.js"></script>

</body>
</html>
 <?php } ?>
