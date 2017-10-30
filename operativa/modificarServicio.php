<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once '../cliente.php';
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
    <link rel="stylesheet" href="../css/modificar.css">
    <script type="text/javascript" src="../js/servicioForm.js"></script>
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
  <span class="right"><a href="../logout.php">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>

      <nav class="menu">
        <a href="index.php">Inicio</a>
        <a href="nuevoServicio.php">Nueva Actividad</a>
        <a href="modificarServicio.php">Actividades Actuales</a>
        <a href="#">Histórico Actividades</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <table id="tablamod">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod">Fecha inicio</th>
            <th scope="col" id="thmod">Modelos</th>
            <th scope="col" id="thmod">Actividad</th>
            <th scope="col" id="thmod">Personal</th>
            <th scope="col" id="thmod">Cliente</th>
            <th scope="col" id="thmod">Opciones</th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <tr id="trmod">
            <td scope="row" data-label="Fecha inicio" id="tdmod">10/10/17</td>
            <td data-label="Modelos" id="tdmod">KUGA+PLAT.CD</td>
            <td data-label="Actividad" id="tdmod">CMP 71-72-72 STOP SHIP TORNILLO BOMBA</td>
            <td data-label="Personal" id="tdmod">2 2 HORAS + 8(4TT + 4TN)</td>
            <td data-label="Cliente" id="tdmod">FORD</td>
            <td data-label="Opciones" id="tdmod"><a href="#" title="Cancelar servicio"><i class="material-icons">clear</i></a><a href="#" title="Modificar recursos"><i class="material-icons">people</i></a>
              <a href="#" title="Modificar información"><i class="material-icons">mode_edit</i></a><a href="#" title="Finalizar servicio"><i class="material-icons">power_settings_new</i></a></td>
          </tr>
          <tr id="trmod">
            <td scope="row" data-label="Fecha inicio" id="tdmod">11/10/17</td>
            <td data-label="Modelos" id="tdmod">PLATAFORMA CD</td>
            <td data-label="Actividad" id="tdmod">VOLANTE RUIDO BOTONERAS</td>
            <td data-label="Personal" id="tdmod">2 2 HORAS + 8(4TT + 4TN)</td>
            <td data-label="Cliente" id="tdmod">RASER 21</td>
            <td data-label="Opciones" id="tdmod"><a href="#"><i class="material-icons">clear</i></a><a href="#"><i class="material-icons">people</i></a><a href="#"><i class="material-icons">mode_edit</i></a>
              <a href="#" title="Finalizar servicio"><i class="material-icons">power_settings_new</i></a></td>
          </tr>
        </tbody>
      </table>
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
