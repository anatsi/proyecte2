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
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <script type="text/javascript" src="../js/servicioForm.js"></script>
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
        <a href="actividadesActuales.php">Actividades Actuales</a>
        <a href="#">Histórico Actividades</a>
      </nav>

    </header>

    <?php
      $infoservicio=$servicio->ServicioId($_GET['servicio']);
     ?>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Modificar recursos actividad</h2>
        <h3><?=$infoservicio['descripcion']?></h3>
        <form action="modificarInfo.php" method="post" id="formulario">
          <div class="formthird" id='contenedor'>
            <input type="hidden" value=<?=$infoservicio['id']?> name="id">
            <p><label>SELECCIONAR DIAS</label></p>
            <p><label><i class="fa fa-question-circle"></i>Dia suelto</label><input type="date" name="suelto" id="suelto"/></p>
            <p><label>Mas de un dia</label></p>
            <p><label><i class="fa fa-question-circle"></i>Inicio</label><input type="date" name="inicio" id="inicio"/></p>
            <p><label><i class="fa fa-question-circle"></i>Fin</label><input type="date" name="fin" id="fin"/></p>
          </div>
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>Actividad</label><input type="text" value=<?=$infoservicio['descripcion']?> name="descripcion"/></p>
            <p><label><i class="fa fa-question-circle"></i>Modelos (*)</label></p>
              <?php
              //comprobamos que modelos estan seleccionados para asi ponerlos checkeados en el formulario
                $mondeo=0;
                $galaxy=0;
                $smax=0;
                $transit=0;
                $kuga=0;
                $todos=0;

                //sacamos los modelos de la bbdd y los separamos
                $modelos=explode(",", $infoservicio['modelos']);
                foreach ($modelos as $modelo) {
                  if ($modelo==" MONDEO") {
                    $mondeo=1;
                  }
                  if ($modelo==" GALAXY") {
                    $galaxy=1;
                  }
                  if ($modelo==" S-MAX") {
                    $smax=1;
                  }
                  if ($modelo==" TRANSIT CONNECT") {
                    $transit=1;
                  }
                  if ($modelo==" KUGA") {
                    $kuga=1;
                  }
                  if ($modelo==" TODOS") {
                    $todos=1;
                  }
                }
                //sacamos por pantalla todos deoendiendo de si estan checked o no
                if ($todos==1) {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='todos' value='TODOS' checked/>TODOS</p>";
                }else {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='todos' value='TODOS'/>TODOS</p>";
                }
                if ($mondeo==1) {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='mondeo' value='MONDEO' checked/>MONDEO</p>";
                }else {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='mondeo' value='MONDEO'/>MONDEO</p>";
                }
                if ($galaxy==1) {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='galaxy' value='GALAXY' checked/>GALAXY</p>";
                }else {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='galaxy' value='GALAXY'/>GALAXY</p>";
                }
                if ($smax==1) {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='smax' value='S-MAX' checked/>S-MAX</p>";
                }else {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='smax' value='S-MAX'/>S-MAX</p>";
                }
                if ($transit==1) {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='transit' value='TRANSIT CONNECT' checked/>TRANSIT CONNECT</p>";
                }else {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='transit' value='TRANSIT CONNECT'/>TRANSIT CONNECT</p>";
                }
                if ($kuga==1) {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='kuga' value='KUGA' checked/>KUGA</p>";
                }else {
                  echo "<p><label><i class='fa fa-question-circle'></i></label><input type='checkbox' name='kuga' value='KUGA'/>KUGA</p>";
                }

               ?>
            <p><label><i class="fa fa-question-circle"></i>Responsable</label><input type="text" value=<?=$infoservicio['responsable']?> name="responsable"/></p>
            <p><label><i class="fa fa-question-circle"></i>Tel. responsable</label><input type="tel" value=<?=$infoservicio['telefono']?> name="tel"/></p>
            <p><label><i class="fa fa-question-circle"></i>Correo responsable</label><input type="email" value=<?=$infoservicio['correo']?> name="correo"/></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Comentario supervisor</label><textarea name="csup"><?=$infoservicio['com_supervisor']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario RRHH</label><textarea name="crrhh"><?=$infoservicio['com_rrhh']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario Admin. Financiero</label><textarea name="caf"><?=$infoservicio['com_admin_fin']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario Depto. Operativo</label><textarea name="cdo"><?=$infoservicio['com_depto']?></textarea></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit" value="Modificar" name="submit"/>
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
if (isset($_POST['submit'])) {
  if (isset($_POST['id']) && empty($_POST['inicio'])==false || isset($_POST['id']) && empty($_POST['suelto'])==false) {
    //juntamos los modelos en una variable
    $modelos="";
    if (isset($_POST['mondeo'])) {
      $modelos=$modelos.", ".$_POST['mondeo'];
    }
    if (isset($_POST['galaxy'])) {
      $modelos=$modelos.", ".$_POST['galaxy'];
    }
    if (isset($_POST['smax'])) {
      $modelos=$modelos.", ".$_POST['smax'];
    }
    if (isset($_POST['transit'])) {
      $modelos=$modelos.", ".$_POST['transit'];
    }
    if (isset($_POST['kuga'])) {
      $modelos=$modelos.", ".$_POST['kuga'];
    }
    if (isset($_POST['todos'])) {
      $modelos=$modelos.", ".$_POST['todos'];
    }
    //llamamos a la funcion de modificar la informacion
    $modificacion=$servicio->modificarInfo($_POST['id'], $_POST['inicio'], $_POST['fin'], $_POST['suelto'], $_POST['descripcion'], $modelos, $_POST['responsable'], $_POST['tel'], $_POST['correo'], $_POST['csup'], $_POST['crrhh'],
     $_POST['caf'], $_POST['cdo']);
      if ($modificacion==null) {
        echo "Error";
      }else {
        ?>
          <script type="text/javascript">
            alert('Actividad actualizada con exito');
            window.location='actividadesActuales.php';
          </script>
        <?php
      }
  }else {
    ?>
      <script type="text/javascript">
        alert('ERROR AL ACTUALIZAR LA ACTIVIDAD. INTENTELO DE NUEVO.');
        window.location='actividadesActuales.php';
      </script>
    <?php
  }
}


 ?>
 <?php } ?>
