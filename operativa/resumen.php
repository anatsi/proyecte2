<?php
//Reconocimiento idioma
require('./languages/languages.php');
  $lang = "es";
if ( isset($_GET['lang']) ){
  $lang = $_GET['lang'];
}
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
        <?php
        $menu=$usuario->menuDash($_SESSION['usuario']);
        $opciones = explode(",", $menu['menu']);
        foreach ($opciones as $opcion) {
          if ($opcion == 21) {
            echo '<a href="nuevoServicio.php>Nueva actividad </a>"';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
          }elseif ($opcion == 22) {

          }elseif ($opcion == 23) {

          }elseif ($opcion == 0) {
            echo '<a href="nuevoServicio.php>Nueva actividad </a>"';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
          }
        }
         ?>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Búsqueda por fechas</h2>
        <form action="resumenTabla.php" method="post" id="formulario">
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>INICIO (*)</label><input type="date" name="inicio" required/></p>
          </div>
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>FIN (*)</label><input type="date" name="fin" required/></p>
          </div>
          <div class="submitbuttons">
              <input id="exportarResumen" type="submit" value="EXPORTAR"/>
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
