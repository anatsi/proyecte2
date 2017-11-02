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
  <link rel="shortcut icon" href="../imagenes/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
  <script type="text/javascript" src="../js/servicioForm.js"></script>
</head>
<style media="screen">
#contactForm {
      margin: 0 auto;
}

  #contactForm input, textarea {
      letter-spacing: 2px;
      color: black;
      font-weight: bold;
      background-color: RGBA(204, 204, 204, .1);
      outline: none; border: none;
      display:block;
      margin: 0 auto;
      padding: 1em;
      width: 90%;
      max-width: 400px;
 }

#contactForm textarea {
  height: 150px;
  color: black;
}
</style>
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
        <a href="actividadesActuales.php">Actividades Actuales</a>
        <a href="#">Histórico Actividades</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Finalizar servicio</h2>
        <form id="contactForm" action="finalizarServicio.php" method="post">
          <input type="hidden" name="fin" value="<?=$_GET['servicio']?>">
         <textarea class="formInput" name="comentario" id="message" placeholder="Ultimo comentario"></textarea>
          <br>
          <input class="submitForm" type="submit" value="Finalizar Servicio"/>
         </form>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../js/menu.js"></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>
</html>
<?php
  echo $_GET['servicio'];
  $fecha= date('Y-m-d');
  if (isset($_POST['fin'])) {
    $finalizado=$servicio->FinalizarActividad($_POST['fin'], $fecha, $_POST['comentario']);
    if ($finalizado == true) {
      header('Location: actividadesActuales.php');
    }else {
      echo "Error";
    }
  }

?>
<?php } ?>
