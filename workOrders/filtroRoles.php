<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';

$usuario=new User();
$sesion=new Sesiones();
if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Búsqueda por fechas</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/modificar.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
  <span class="right"><a href="../logout.php" id="logout">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>

      <nav class="menu">
        <a href="filtroMovimientos.php">Movimientos</a>
        <a href="registroWrap.php">Wrap Guard</a>
        <a href="filtroRoles.php">Roles</a>
        <a href="registroReallocation.php">Reallocation</a>
        <a href="registroRadio.php">Bridas</a>
        <a href="registroWow.php">Howling</a>

        <a href="registroClatter.php">Clatter</a>
        <a href="movimientosJockeys.php">Work orders</a>


      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Filtro cambios de rol</h2>
        <form action="registroRoles.php" method="post" id="formulario">
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>INICIO</label><input type="date" name="inicio"/></p>
            <p><label><i class="fa fa-question-circle"></i>USUARIO</label><input type="text" name="usuario"/></p>
          </div>
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>FIN</label><input type="date" name="fin"/></p>
            <p><label><i class="fa fa-question-circle"></i>ROL</label><input type="text" name="rol"/></p>
          </div>
          <div class="submitbuttons">
              <input id="exportarResumen" type="submit" value="BUSCAR"/>
          </div>
  </form>

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
