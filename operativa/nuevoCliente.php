<?php

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './bbdd/cliente.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Nuevo cliente</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <script type="text/javascript" src="../js/servicioForm.js"></script>
    <script src="../js/jquery.min.js"></script>
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
    echo "<a><strong>Bienvenido".$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../logout.php" id="logout">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>

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
        <h2>Nuevo cliente</h2>
        <form action="nuevoCliente.php" method="post" id="formulario" enctype="multipart/form-data">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Nombre cliente: (*)</label><input type="text" name="nombre" required/></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit"  name="Enviar" value="Enviar"/>
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

<?php
  if (isset($_POST['Enviar'])) {
    if (isset($_POST['nombre'])) {
      $nuevoCliente = $cliente -> nuevoCliente($_POST['nombre']);
      if ($nuevoCliente == null || $nuevoCliente == false) {
        ?>
          <script type="text/javascript">
            alert('Algo salio mal');
          </script>
        <?php
      }else {
        ?>
        <script type="text/javascript">
          alert('Nuevo cliente registrado');
        </script>
        <?php
      }
    }else {
      ?>
      <script type="text/javascript">
        alert('Rellenar el nombre antes de continuar');
        window.location = 'nuevoCliente.php';
      </script>
      <?php
    }
  }
 ?>
 <?php } ?>
