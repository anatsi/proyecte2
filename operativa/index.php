<?php
//Reconocimiento idioma
require('./languages/languages.php');
  $lang = "es";
if ( isset($_GET['lang']) ){
  $lang = $_GET['lang'];
}

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
  <title><?php echo __('Inicio', $lang); ?></title>
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
    echo "<a><strong>".__('Bienvenido ', $lang).$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../logout.php" id="logout"><?php echo __('Cerrar Sesion', $lang); ?></a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>

      <a href="../dashboard.php?lang=<?php echo $lang; ?>" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>

      <nav class="menu">
        <a href="index.php?lang=<?php echo $lang; ?>"><?php echo __('Inicio', $lang); ?></a>
        <a href="nuevoServicio.php?lang=<?php echo $lang; ?>"><?php echo __('Nueva actividad', $lang); ?></a>
        <a href="actividadesActuales.php?lang=<?php echo $lang; ?>"><?php echo __('Actividades actuales', $lang); ?></a>
        <a href="historicoActividades.php?lang=<?php echo $lang; ?>"><?php echo __('Historico actividades', $lang); ?></a>
      </nav>

    </header>

<div class="site-content">
  <div class="container">
    <div class="derecha">
      <h1><?php echo __('HOY', $lang); ?></h1>
      <table class="rwd-table">
        <tr>
          <th><?php echo __('Actividad', $lang); ?></th>
          <th><?php echo __('Recursos', $lang); ?></th>
        </tr>
        <?php
          $listahoy= $servicio->listaServiciosHoy();
          foreach ($listahoy as $servicio) {
            echo "<tr>";
            echo "<td data-th='".__('Actividad', $lang)."'>".$servicio['descripcion']."</td>";
            echo "<td data-th='".__('Recursos', $lang)."'>".$servicio['recursos']."</td>";
            echo "</tr>";
          }
         ?>
      </table>
    </div>
    <div class="izquierda">
      <h1><?php echo __('MAÑANA', $lang); ?></h1>
      <table class="rwd-table">
        <tr>
          <th><?php echo __('Actividad', $lang); ?></th>
          <th><?php echo __('Recursos', $lang); ?></th>
        </tr>
        <?php
        require_once 'servicio.php';
        $servicio=new Servicio();
          $listamanana= $servicio->ServiciosTomorrow();
          foreach ($listamanana as $servicio) {
            echo "<tr>";
            echo "<td data-th='".__('Actividad', $lang)."'>".$servicio['descripcion']."</td>";
            echo "<td data-th='".__('Recursos', $lang)."'>".$servicio['recursos']."</td>";
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
