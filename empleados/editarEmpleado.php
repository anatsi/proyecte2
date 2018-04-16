<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './bbdd/empleados.php';

$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();

//si la sesion no esta iniciada, devolvemos al usuario a la pagina de inicio de sesion
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
        <a href="index.php">Inicio</a>
      </nav>

    </header>
    <?php
    //sacamos el empleado que se quiere editar por su id
      $seleccionado=$empleado->EmpleadoId($_GET['e']);
     ?>
    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Editar empleado</h2>
        <form action="editarEmpleado.php" method="post">
          <input type="hidden" name="e" value="<?=$seleccionado['id']?>">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Nombre</label><input name='nombre' value='<?=$seleccionado['nombre']?>' type="text" required></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Apellidos</label><input name='apellidos' value='<?=$seleccionado['apellidos']?>' type="text" required></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Telefono</label><input name='tel' value='<?=$seleccionado['telefono']?>' type="tel"></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit"/>
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
//comprobamos que se ha rellenado el nombre y los apellidos
  if (isset($_POST['nombre']) && isset($_POST['apellidos'])) {
    //inicializamos activo a 0
    $activo=0;
    //si se ha pulsado en la casilla de activo, ponemos la variable a 1
    if (isset($_POST['activo'])) {
      $activo=1;
    }
    //llamamos a la consulta de editar el empleado
    $editarEmpleado= $empleado->editarEmpleado($_POST['e'], $_POST['nombre'], $_POST['apellidos'], $_POST['tel']);
    if ($editarEmpleado==null) {
      //si no se ha podido actualizar, avisamos al usaurio
      ?>
      <script type="text/javascript">
        alert('Error al registrar el nuevo empleado');
        window.location='index.php';
      </script>
      <?php
    }else {
      //si se ha editado correctamente, devolvemos el usuario a inicio
      ?>
        <script type="text/javascript">
          alert('Información editada con exito.');
          window.location='index.php';
        </script>
      <?php
    }
  }
 ?>
 <?php } ?>
