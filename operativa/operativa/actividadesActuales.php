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
require_once '../bbdd/cliente.php';
require_once '../bbdd/servicio.php';
require_once '../bbdd/recursos.php';
require_once '../bbdd/responsable.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();
$recursos=new Recursos();
$responsable=new Responsable();

//comprobamos si hay una sesion iniciada
if (isset($_SESSION['usuario'])==false) {
  //si no se ha iniciado sesion, lo devolvemos a la pagina de inicio de sesion
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo __('Actividades actuales', $lang); ?></title>
    <link rel="stylesheet" href="../../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../../css/formulario.css">
    <link rel="shortcut icon" href="../../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
    <link rel="stylesheet" href="../../css/modificar.css">
    <script type="text/javascript" src="../../js/servicioForm.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- CSS para alternar los colores de la tabla -->
    <style media="screen">
      tr:nth-child(even) {
        background-color: #CAC6C5;
      }
    </style>
    <!--Script para fijar la cabecera de las tablas-->
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.stickytableheaders.min.js"></script>
    <script type="text/javascript">
      $(function() {
        $("table").stickyTableHeaders();
      });
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
  <span class="right"><a href="../../logout.php" id='logout'><?php echo __('Cerrar Sesion', $lang); ?></a></span>
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
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente/resp.</a>";
          }elseif ($opcion == 22) {
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
          }elseif ($opcion == 23) {
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';
          }elseif ($opcion == 0) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente/resp.</a>";
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';
          }
        }
         ?>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%; color:black;">
          <a href="../../dashboard.php">Inicio</a> >> <a href="../index.php">Gestión actividades</a> >> <a href="actividadesActuales.php">Actividades actuales</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2><?php echo __('Actividades actuales', $lang); ?></h2>
        <h3><a href="excelActuales.php" title="Exportar todo a excel"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=="></a></h3>
        <br>
        <table id="tablamod" class="tablesorter">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod"><?php echo __('Actividad', $lang); ?></th>
            <th scope="col" id="thmod">Responsable</th>
            <th scope="col" id="thmod">Estado</th>
            <th scope="col" id="thmod">Hoy</th>
            <th scope="col" id="thmod">Mañana</th>
            <th scope="col" id="thmod"><?php echo __('Opciones', $lang); ?></th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <?php
          //sacamos la lista de los servicios de hoy
            $listamodificar=$servicio->listaActividadesActuales();
            foreach ($listamodificar as $lista) {
              //comprobar si ya se ha iniciado o no.
              $fechaActual=date('Y-m-d');
              if ($lista['f_inicio'] <= $fechaActual) {
                $status='En marcha';
              }elseif ($lista['f_inicio'] > $fechaActual) {
                //transformamos la fecha
                $fecha=explode("-", $lista['f_inicio']);
                $status=$fecha[2]."-".$fecha[1]."-".$fecha[0];
              }

              //sacar los recursos para ese dia y ese servicio-
              $recursoHoy = $recursos -> ModificacionId($lista['id'], $fechaActual);
              if ($recursoHoy == null || $recursoHoy == false) {
                $recursoHoy = $lista['recursos'];
              }else {
                $recursoHoy = $recursoHoy['total'];
              }

              //sacar los recursos del dia siguiente para ese servicio
              $nuevafecha = strtotime ( '+1 day' , strtotime ( $fechaActual ) ) ;
              $fechaTomorrow = date ( 'Y-m-d' , $nuevafecha );
              $recursoTomorrow = $recursos -> ModificacionId($lista['id'], $fechaTomorrow);
              if ($recursoTomorrow == null || $recursoTomorrow == false) {
                $recursoTomorrow = $lista['recursos'];
              }else {
                $recursoTomorrow = $recursoTomorrow['total'];
              }


              if ($lista['f_inicio']>$fechaActual) {
                $recursoHoy=0;
              }
              if ($lista['f_inicio']>$fechaTomorrow) {
                $recursoTomorrow=0;
              }
              if ($lista['f_fin']!=null && $lista['f_fin']<$fechaTomorrow) {
                $recursoTomorrow=0;
              }
              $resp=$responsable->responsableId($lista['responsable']);

              echo "<tr id='trmod'>";
              echo "<td scope='row' data-label='".__('Actividad', $lang)."' id='tdmod'><a id='timeline' href='timeline.php?servicio=".$lista['id']."'>".$lista['descripcion']."</a></td>";
              echo "<td data-label='Responsable' id='tdmod'>".$resp['nombre']."</td>";
              echo "<td data-label='Estado' id='tdmod'>".$status."</td>";
              echo "<td data-label='Hoy' id='tdmod'>".$recursoHoy."</td>";
              echo "<td data-label='Mañana' id='tdmod'>".$recursoTomorrow."</td>";
              echo "  <td data-label='".__('Opciones', $lang)."' id='tdmod'>
              <a href='modificarRecursos.php?servicio=".$lista['id']."' title='".__('Modificar recursos', $lang)."'><i class='material-icons'>people</i></a>
              <a href='modificarInfo.php?servicio=".$lista['id']."' title='".__('Modificar información', $lang)."'><i class='material-icons'>mode_edit</i></a>";
              /*echo"<a href='excelIndividual.php?id=".$lista['id']."' title='Exportar a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALCSURBVEhLvVZLaFNBFA3+EBFRUMGVIAjS5r1XjBQEoYLuBNFFXIjgrmKheTMvCoKLuCi6FBdK0ZUIRbrWjYJFumkzk9RPVcTfRhERrAs/oGI952aSvnwsL0n1wuG9e9+Ze2bmzp0klcQydnBlb1kHnlHHfaMu+kbf96267T53Zki6Jl0Kd3tWDyHpVSQ1SPrdt3o+DohOuiHJzCtF+5DoFAaPIelTCPxqTNoKFBqYKKzwHp3ZsBj6p4bXiVCrJElAId9Ge1p9q4PRv4HcvxcCwL3SlVC6pHbh/RUB/51n9A/U9VMViH1x3O6EZO+dcXUQuuVcMfhHHbdByOiHmIkFnsXjGPCGcRyUxwsxNbl1orDaK6ptRGDVEcTuVX2B1cpx64UQ0JxJz2xhFd7fVmL6606jtrgZnotxO68REs31lYc3SdLabPQF8THDeD9RKF4jfHvPSS34UrcPeDYLSdCqUSZm42K7XrIXRAj7X8drqFFg1AFynCsG/y81YhANG0yHfSQG06pXBhSjg008CPXMDq31SvmMwKqQsZpPmOis4zYLVaDuUICWHc8uR4xbUcdh0iXoIzVSFeETs73eyKEQV+5OKfECvM8xH6dUvXbcljV6zmNLARz3vDwf5Ddjv+fqeF3VCPdSuqgHSAqsPgzRn33l3Hb6volOxrmyoplwPU7jfgHqgcRTNV9i6rzjNgldY1JuGT4+caSbjKXmC8twUIpVLoU6qhFm/01mISdIn64RuUobHqqcJDVY41OomEvj/S6BMTOIfaz6LiY3SZ1Qu6CQrNRZ232UFBTK2GijZ6OsADcIVl+q+RJTlxy3O6H/9nuUVAg9dZnNKE3VLiiUttEONPf44tBjvHylaOyHwIR7MVgDN3ha2EONyeOgkAzu1ng7BEb3AycgOirNiBZYcqFWxr9X7J2gpI9xAi6cwFKpPxzlsvQlqWiqAAAAAElFTkSuQmCC'></a>";*/
              echo"<a href='finalizarServicio.php?servicio=".$lista['id']."' title='".__('Finalizar actividad', $lang)."'><i class='material-icons'>power_settings_new</i></a>
              </td>";
            }
           ?>
        </tbody>
      </table>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!--ORDENAR TABLA -->
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
<script>
  $(function(){
    $("#tablamod").tablesorter();
  });
</script>
<!-- Scripts para que el menu en versión movil funcione
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script  src="../../js/menu.js"></script>

</body>
</html>
 <?php } ?>
