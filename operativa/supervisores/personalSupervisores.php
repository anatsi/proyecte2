<?php
//Reconocimiento idioma
require('../languages/languages.php');
  $lang = "es";
if ( isset($_GET['lang']) ){
  $lang = $_GET['lang'];
}

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../../ddbb/sesiones.php';
require_once '../../ddbb/users.php';
require_once '../bbdd/servicio.php';
require_once '../bbdd/recursos.php';
require_once '../bbdd/empleados.php';
require_once '../bbdd/personal.php';

$usuario=new User();
$sesion=new Sesiones();
$servicio=new Servicio();
$recursos=new Recursos();
$empleado = new Empleados();
$personal = new Personal();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Elegir dia</title>
  <link rel="stylesheet" href="../../css/menu.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet" href="../../css/formulario.css">
  <link rel="shortcut icon" href="../../imagenes/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
  <script type="text/javascript" src="../../js/servicioForm.js"></script>
  <link href="../../css/fSelect.css" rel="stylesheet">
  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/fSelect.js"></script>
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
    echo "<a><strong>".__('Bienvenido ', $lang).$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../../logout.php" id="logout"><?php echo __('Cerrar Sesion', $lang); ?></a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>

      <a href="../../dashboard.php?lang=<?php echo $lang; ?>" class="header__logo"><img src="../../imagenes/logo.png" alt=""></a>

      <nav class="menu">
        <a href="../index.php?lang=<?php echo $lang; ?>"><?php echo __('Inicio', $lang); ?></a>
        <?php
        $menu=$usuario->menuDash($_SESSION['usuario']);
        $opciones = explode(",", $menu['menu']);
        foreach ($opciones as $opcion) {
          if ($opcion == 21) {
            echo '<a href="../operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='../operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='../operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='../operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente</a>";
          }elseif ($opcion == 22) {
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
          }elseif ($opcion == 23) {
            echo '<a href="filtroSupervisores.php">Jefe de turno</a>';

          }elseif ($opcion == 0) {
            echo '<a href="../operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='../operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='../operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='../operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente</a>";
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
            echo '<a href="filtroSupervisores.php">Jefe de turno</a>';
          }
        }
         ?>
      </nav>

    </header>

<div class="site-content">
  <div class="container">
    <!-- Contenido de la pagina -->
    <?php
    //transformamos la fecha
      $fechaMostrar=explode("-", $_POST['fecha']);
      $fechaMostrar=$fechaMostrar[2]."-".$fechaMostrar[1]."-".$fechaMostrar[0];
      echo "<h2>".$fechaMostrar." - Turno ".$_POST['turno']."</h2>";
      echo "<h3><a href='excelSupervisores.php?fecha=".$_POST['fecha']."&turno=".$_POST['turno']."' title='Exportar excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a></h3>";
     ?>
    <form method="post" id="formulario" action="guardarSupervisores.php" method="post">
      <?php
      $i=0;
      //transformar los turnos.
      //convertimos el turno que nos han enviado.
      if ($_POST['turno'] == 'Mañana') {
        $turno = 'tm';
      }elseif ($_POST['turno'] == 'Tarde') {
        $turno = 'tt';
      }elseif ( $_POST['turno'] == 'Noche') {
        $turno = 'tn';
      }
      //guardar la fecha en un input para pasarla a la siguiente pantalla
      echo "<input type='hidden' name='fecha' value='".$_POST['fecha']."'>";
      echo "<input type='hidden' name='turno' value='".$turno."'>";
        //sacamos las actividades que hay para el dia seleccionado.
        $actividadesHoy = $servicio -> listaRRHH($_POST['fecha']);
        $personalHoy = $personal -> personalHoy($_POST['fecha'], $turno);
        //recorremos cada una de las actividades.
        foreach ($actividadesHoy as $act) {
          //sacamos el nombre de la actividad
          echo "<div class='formthird'>";
          //sacar los nombres asignados a esa actividad para ese dia.
          $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], $turno);
          if ($asignados != null && $asignados != false) {
            echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']."</label></p>";
            echo "<input type='hidden' name='act".$i."[]' value='".$act['id']."'>";
            //sacamos tantos selects como personas asignadas habia para ese dia.
            foreach ($asignados as $persona) {
              echo "<p><select name='select".$i."[]' class='test' id='multiple'>";
              echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
              if ($persona['empleado'] == '') {
                echo "<option value='' selected>Sin asignar</option>";
              }
              //sacamos la lista de personal
              foreach ($personalHoy as $disponibles) {
                //comparamos para sacar como seleccionado el que ja estaba
                if ($disponibles['empleado'] == $persona['empleado']) {
                  echo "<option value='".$disponibles['empleado']."' selected>".$disponibles['empleado']."</option>";
                }else {
                  echo "<option value='".$disponibles['empleado']."'>".$disponibles['empleado']."</option>";
                }
              }
              echo "</select></p>";
            }
            $i++;

          }else {
            //si la actividad esta para hoy, pero no hay nadie asignado, comprobamos cuantos recursos deberia de tener.
            $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
            if ($recurso == null || $recurso == false) {
              $recurso = $recursos -> RecursosId($act['id']);
            }
            if ($recurso[$turno]>0) {
              echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']."</label></p>";
              echo "<input type='hidden' name='act".$i."[]' value='".$act['id']."'>";

              //si tenia recursos asignados, sacamos el select con la opcion sin asignar seleccionada
              for ($r=0; $r < $recurso[$turno] ; $r++) {
                echo "<p><select name='select".$i."[]' class='test' id='multiple'>";
                echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                echo "<option value='' selected>SIN ASIGNAR</option>";
                //sacamos la lista de personal
                foreach ($personalHoy as $disponibles) {
                  //comparamos para sacar como seleccionado el que ja estaba
                  echo "<option value='".$disponibles['empleado']."'>".$disponibles['empleado']."</option>";
                }
                echo "</select></p>";
              }
              $i++;
            }
          }


        echo "</div>";
        }
      ?>
      <div class="submitbuttons">
          <input class="submitone" type="submit" value='CONFIRMAR'/>
      </div>
    </form>
  </div>
</div>
</div>
</div>
<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../../js/menu.js"></script>

</body>
</html>
<?php } ?>
