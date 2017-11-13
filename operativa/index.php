<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once 'cliente.php';
require_once 'servicio.php';


$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Nuevo servicio</title>
    <link rel="stylesheet" href="../css/tabla.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
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
        <a href="index.php">Inicio</a>
        <a href="nuevoServicio.php">Nueva Actividad</a>
        <a href="actividadesActuales.php">Actividades Actuales</a>
        <a href="historicoActividades.php">Histórico Actividades</a>
      </nav>

    </header>

<div class="site-content">
  <div class="container">
    <div class="derecha">
      <h1>HOY</h1>
      <table class="rwd-table">
        <tr>
          <th>Servicio</th>
          <th>Recursos</th>
        </tr>
        <?php
          $listahoy= $servicio->listaServiciosHoy();
          foreach ($listahoy as $servicio) {
            echo "<tr>";
            echo "<td data-th='Servicio'>".$servicio['descripcion']."</td>";
            echo "<td data-th='Recursos'>".$servicio['recursos']."</td>";
            echo "</tr>";
          }
         ?>
      </table>
    </div>
    <div class="izquierda">
      <h1>MAÑANA</h1>
      <table class="rwd-table">
        <tr>
          <th>Servicio</th>
          <th>Recursos</th>
        </tr>
        <?php
        require_once 'servicio.php';
        $servicio=new Servicio();
          $listamanana= $servicio->ServiciosTomorrow();
          foreach ($listamanana as $servicio) {
            echo "<tr>";
            echo "<td data-th='Servicio'>".$servicio['descripcion']."</td>";
            echo "<td data-th='Recursos'>".$servicio['recursos']."</td>";
            echo "</tr>";
          }
         ?>
      </table>
    </div>
  </div>
</div>
</div>
</div>
<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../js/menu.js"></script>

</body>
</html>
<?php } ?>
