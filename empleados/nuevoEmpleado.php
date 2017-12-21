<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once 'empleados.php';

$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Nuevo empleado</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
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
  <span class="right"><a href="../logout.php" id='logout'>Cerrar sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="gestionEmpleados.php">Inicio</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Nuevo empleado</h2>
        <form action="nuevoEmpleado.php" method="post">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Nombre (*)</label><input name='nombre' type="text" required></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Apellidos (*)</label><input name='apellidos' type="text" required></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Activo</label><input Name='activo' type="checkbox"/></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit" />
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
  if (isset($_POST['nombre']) && isset($_POST['apellidos'])) {
    $activo=0;
    if (isset($_POST['activo'])) {
      $activo=1;
    }
    $nuevoEmpleado= $empleado->nuevoEmpleado($_POST['nombre'], $_POST['apellidos'], $activo);
    if ($nuevoEmpleado==null) {
      ?>
      <script type="text/javascript">
        alert('Error al registrar el nuevo empleado');
      </script>
      <?php
    }else {
      ?>
        <script type="text/javascript">
          alert('Nuevo empleado registrado con exito.');
          window.location='gestionEmpleados.php';
        </script>
      <?php
    }
  }
 ?>
 <?php } ?>
