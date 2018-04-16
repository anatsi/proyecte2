<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './bbdd/cliente.php';
require_once './bbdd/servicio.php';

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
  <title>Modificar información actividad</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <script type="text/javascript" src="../js/servicioForm.js"></script>
    <link href="../css/fSelect.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/fSelect.js"></script>
    <script>
      (function($) {
          $(function() {
              $('.test').fSelect();
          });
      })(jQuery);
    </script>
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
        <a href="resumen.php">Búsqueda por fechas</a>
        <a href="nuevoCliente.php">Nuevo cliente</a>

      </nav>

    </header>

    <?php
      $infoservicio=$servicio->ServicioId($_GET['servicio']);
     ?>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Modificar informacion de la actividad</h2>
        <h3><?=$infoservicio['descripcion']?></h3>
        <h4><a style="color: red;" href='cancelarServicio.php?servicio=<?=$infoservicio['id']?>'>Cancelar actividad</a></h4>
        <form action="modificarInfo.php" method="post" id="formulario">
          <div class="formthird" id='contenedor'>
            <input type="hidden" value=<?=$infoservicio['id']?> name="id">
            <p><label>SELECCIONAR DIAS</label></p>
            <p><label><i class="fa fa-question-circle"></i>Dia suelto</label><input type="date" name="suelto" min= <?php echo date('Y-m-d');?> id="suelto"/></p>
            <p><label>Más de un dia</label></p>
            <p><label><i class="fa fa-question-circle"></i>Inicio</label><input type="date" name="inicio" min= <?php echo date('Y-m-d');?> id="inicio"/></p>
            <p><label><i class="fa fa-question-circle"></i>Fin</label><input type="date" name="fin" min= <?php echo date('Y-m-d');?> id="fin"/></p>
          </div>
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>Actividad</label><input type="text" value='<?=$infoservicio['descripcion']?>' name="descripcion"/></p>
            <p><label><i class="fa fa-question-circle"></i>Descripción</label><textarea name="cdo"><?=$infoservicio['com_depto']?></textarea></p>
            <p><label><i class="fa fa-question-circle"></i>Modelos (*)</label>
            <select name="sel[]" class="test" multiple="multiple" id='multiple'>
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
                  if ($modelo==" MONDEO" || $modelo=="MONDEO") {
                    $mondeo=1;
                  }
                  if ($modelo==" GALAXY" || $modelo=="GALAXY") {
                    $galaxy=1;
                  }
                  if ($modelo==" S-MAX" || $modelo=="S-MAX") {
                    $smax=1;
                  }
                  if ($modelo==" TRANSIT CONNECT" || $modelo=="TRANSIT CONNECT") {
                    $transit=1;
                  }
                  if ($modelo==" KUGA" || $modelo=="KUGA") {
                    $kuga=1;
                  }
                }
                //sacamos por pantalla todos deoendiendo de si estan checked o no
                if ($mondeo==1) {
                  echo "<option value='MONDEO' selected>MONDEO</option>";
                }else {
                  echo "<option value='MONDEO'>MONDEO</option>";
                }
                if ($galaxy==1) {
                  echo "<option value='GALAXY' selected>GALAXY</option>";
                }else {
                  echo "<option value='GALAXY'>GALAXY</option>";
                }
                if ($smax==1) {
                    echo "<option value='S-MAX' selected>S-MAX</option>";
                }else {
                  echo "<option value='S-MAX'>S-MAX</option>";
                }
                if ($transit==1) {
                  echo "<option value='TRANSIT CONNECT' selected>CONNECT</option>";
                }else {
                  echo "<option value='TRANSIT CONNECT'>CONNECT</option>";
                }
                if ($kuga==1) {
                  echo "<option value='KUGA' selected>KUGA</option>";
                }else {
                  echo "<option value='KUGA'>KUGA</option>";
                }

               ?>
               </select></p>
            <p><label><i class="fa fa-question-circle"></i>Responsable</label><input type="text" value='<?=$infoservicio['responsable']?>' name="responsable"/></p>
            <p><label><i class="fa fa-question-circle"></i>Tel. responsable</label><input type="tel" value=<?=$infoservicio['telefono']?> name="tel"/></p>
            <p><label><i class="fa fa-question-circle"></i>Correo responsable</label><input type="email" value='<?=$infoservicio['correo']?>' name="correo"/></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Comentario supervisor</label><textarea name="csup"><?=$infoservicio['com_supervisor']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario RRHH</label><textarea name="crrhh"><?=$infoservicio['com_rrhh']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario Admin. Financiero</label><textarea name="caf"><?=$infoservicio['com_admin_fin']?></textarea></p>
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
    $arrayModelos=$_POST['sel'];
    for ($i=0; $i < count($arrayModelos); $i++) {
      if ($i==0) {
        $modelos=$arrayModelos[$i];
      }else {
        $modelos= $modelos .", ".$arrayModelos[$i];
      }
    }
    //llamamos a la funcion de modificar la informacion
    $modificacion=$servicio->modificarInfo($_POST['id'], $_POST['inicio'], $_POST['fin'], $_POST['suelto'], $_POST['descripcion'], $modelos, $_POST['responsable'], $_POST['tel'], $_POST['correo']);
    $modComentarios= $servicio->ActualizarComentarios($_POST['id'], $_POST['csup'], $_POST['crrhh'], $_POST['caf'], $_POST['cdo']);
      if ($modificacion==null || $modComentarios==null) {
        ?>
          <script type="text/javascript">
            alert('ERROR AL ACTUALIZAR LA ACTIVIDAD. INTENTELO DE NUEVO.');
            window.location='actividadesActuales.php';
          </script>
        <?php
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
        alert('ELEGIR LAS FECHAS ANTES DE CONTINUAR.');
        window.location='actividadesActuales.php';
      </script>
    <?php
  }
}


 ?>
 <?php } ?>
