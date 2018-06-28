<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './bbdd/cliente.php';
require_once './bbdd/servicio.php';
require_once './bbdd/recursos.php';

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
  <title>Modificar recursos actividad</title>
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
          if ($opcion == 51) {
            echo '<a href="nuevoServicio.php>Nueva actividad </a>"';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
          }elseif ($opcion == 52) {

          }elseif ($opcion == 53) {

          }
        }
         ?>
      </nav>

    </header>

    <?php
      $infoservicio=$servicio->ServicioId($_GET['servicio']);
      $inforecurso=$recursos->RecursosId($_GET['servicio']);
     ?>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Modificar recursos actividad</h2>
        <h3><?=$infoservicio['descripcion']?></h3>
        <form action="modificarRecursos.php" method="post" id="formulario">
          <div class="formthird" id='contenedor'>
            <input type="hidden" value=<?=$infoservicio['id']?> name="id">
            <p><label>SELECCIONAR DIAS</label></p>
            <p><label><i class="fa fa-question-circle"></i>Dia suelto</label><input type="date" name="suelto" min= <?php echo date('Y-m-d');?> id="suelto"/></p>
            <p><label>Mas de un dia</label></p>
            <p><label><i class="fa fa-question-circle"></i>Inicio</label><input type="date" name="inicio" min= <?php echo date('Y-m-d');?> id="inicio"/></p>
            <p><label><i class="fa fa-question-circle"></i>Fin</label><input type="date" name="fin" min= <?php echo date('Y-m-d');?> id="fin"/></p>

              <p><label><i class="fa fa-question-circle"></i>Recursos totales</label><input type="number" min='0' value=<?=$inforecurso['total']?> name="recursos" id="total" readonly/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno mañana</label><input type="number" min='0' name="tm" id="tm" value=<?=$inforecurso['tm']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno tarde</label><input type="number" min='0' name="tt" id="tt" value=<?=$inforecurso['tt']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno noche</label><input type="number" min='0'name="tn" id="tn" value=<?=$inforecurso['tn']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno central</label><input type="number" min='0'name="tc" id="tc" value=<?=$inforecurso['tc']?> onclick="suma();" onkeyup="suma();"/></p>
          </div>
          <div class="formthird">
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i1'value='<?=$inforecurso['inicio1']?>'/>
              <input class='threeinputs2' type='time' name='f1' id="f1" value='<?=$inforecurso['fin1']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro1']?> min='0' id='in1' onclick='suma();' onkeyup='suma();' name='o1'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i2'value='<?=$inforecurso['inicio2']?>'/>
              <input class='threeinputs2' type='time' name='f2'id="f2" value='<?=$inforecurso['fin2']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro2']?> min='0' id='in2' onclick='suma();' onkeyup='suma();' name='o2'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i3'value='<?=$inforecurso['inicio3']?>'/>
              <input class='threeinputs2' type='time' name='f3' id="f3"value='<?=$inforecurso['fin3']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro3']?> min='0' id='in3' onclick='suma();' onkeyup='suma();' name='o3'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i4' value="<?=$inforecurso['inicio4']?>"/>
              <input class='threeinputs2' type='time' name='f4' id="f4"value='<?=$inforecurso['fin4']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro4']?> min='0' id='in4' onclick='suma();' onkeyup='suma();' name='o4'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i5' value="<?=$inforecurso['inicio5']?>"/>
              <input class='threeinputs2' type='time' name='f5' id="f5"value="<?=$inforecurso['fin5']?>"/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro5']?> min='0' id='in5' onclick='suma();' onkeyup='suma();' name='o5'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i6' value="<?=$inforecurso['inicio6']?>"/>
              <input class='threeinputs2' type='time' name='f6' id="f6" value="<?=$inforecurso['fin6']?>"/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro6']?> min='0' id='in6' onclick='suma();' onkeyup='suma();' name='o6'/>
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
<?php
if (isset($_POST['id']) && isset($_POST['recursos'])) {
  if (isset($_POST['id']) && empty($_POST['inicio'])==false || isset($_POST['id']) && empty($_POST['suelto'])==false) {
    $nuevorecurso=$recursos->modundia($_POST['id'], $_POST['suelto'], $_POST['inicio'], $_POST['fin'], $_POST['recursos'], $_POST['tm'], $_POST['tt'], $_POST['tn'], $_POST['tc'], $_POST['o1'], $_POST['i1'], $_POST['f1'],
     $_POST['o2'], $_POST['i2'],
     $_POST['f2'], $_POST['o3'], $_POST['i3'], $_POST['f3'], $_POST['o4'], $_POST['i4'], $_POST['f4'], $_POST['o5'], $_POST['i5'], $_POST['f5'],
      $_POST['o6'], $_POST['i6'], $_POST['f6']);
      $modComentarios=$servicio->ActualizarComentarios($_POST['id'], $_POST['csup'], $_POST['crrhh'], $_POST['caf'], $_POST['cdo']);
      if ($nuevorecurso==null || $modComentarios==null) {
        ?>
          <script type="text/javascript">
            alert('ERROR AL ACTUALIZAR LA ACTIVIDAD. INTENTELO DE NUEVO');
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
        alert('ELEGIR LAS FECHAS ANTES DE CONTINUAR');
        window.location='actividadesActuales.php';
      </script>
    <?php
  }
}

 ?>
 <?php } ?>
