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
        <a href="modificarServicio.php">Actividades Actuales</a>
        <a href="#">Histórico Actividades</a>
      </nav>

    </header>
    <p color='black'>
    <?php
      $infoservicio=$servicio->ServicioId($_GET['servicio']);
      $inforecurso=$recursos->RecursosId($_GET['servicio']);
     ?>
</p>
    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Modificar recursos actividad</h2>
        <form action="modificarRecursos.php" method="post" id="formulario">
          <div class="formthird" id='contenedor'>
              <p><label><i class="fa fa-question-circle"></i>Recursos totales (*)</label><input type="number" min='0' value=<?=$inforecurso['total']?> name="recursos" id="total" readonly/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno mañana</label><input type="number" min='0' name="tm" id="tm" value=<?=$inforecurso['tm']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno tarde</label><input type="number" min='0' name="tt" id="tt" value=<?=$inforecurso['tt']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno noche</label><input type="number" min='0'name="tn" id="tn" value=<?=$inforecurso['tn']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno central</label><input type="number" min='0'name="tc" id="tc" value=<?=$inforecurso['tc']?> onclick="suma();" onkeyup="suma();"/></p>
          </div>
          <div class="formthird">
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='f1'/>
              <input class='threeinputs2' type='time' name='i1'/>
              <input class='threeinputs1' type='number' value='0' min='0' id='in1' onclick='suma();' onkeyup='suma();' name='o1'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='f2'/>
              <input class='threeinputs2' type='time' name='i2'/>
              <input class='threeinputs1' type='number' value='0' min='0' id='in2' onclick='suma();' onkeyup='suma();' name='o2'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='f3'/>
              <input class='threeinputs2' type='time' name='i3'/>
              <input class='threeinputs1' type='number' value='0' min='0' id='in3' onclick='suma();' onkeyup='suma();' name='o3'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='f4'/>
              <input class='threeinputs2' type='time' name='i4'/>
              <input class='threeinputs1' type='number' value='0' min='0' id='in4' onclick='suma();' onkeyup='suma();' name='o4'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='f5'/>
              <input class='threeinputs2' type='time' name='i5'/>
              <input class='threeinputs1' type='number' value='0' min='0' id='in5' onclick='suma();' onkeyup='suma();' name='o5'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='f6'/>
              <input class='threeinputs2' type='time' name='i6'/>
              <input class='threeinputs1' type='number' value='0' min='0' id='in6' onclick='suma();' onkeyup='suma();' name='o6'/>
            </p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Comentario supervisor</label><textarea name="csup"><?=$infoservicio['com_supervisor']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario RRHH</label><textarea name="crrhh"><?=$infoservicio['com_rrhh']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario Admin. Financiero</label><textarea name="caf"><?=$infoservicio['com_admin_fin']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario Depto. Operativo</label><textarea name="cdo"><?=$infoservicio['com_depto']?></textarea></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit" value="Modificar"/>
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
