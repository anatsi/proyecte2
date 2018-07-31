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
require_once '../bbdd/empleados.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();
$recursos=new Recursos();
$empleados = new Empleados();

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
            echo '<a href="../operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='../operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='../operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='../operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente</a>";
          }elseif ($opcion == 22) {
            echo '<a href="filtroRRHH.php">Selección personal</a>';

          }elseif ($opcion == 23) {
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';

          }elseif ($opcion == 0) {
            echo '<a href="../operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='../operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='../operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='../operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente</a>";
            echo '<a href="filtroRRHH.php">Selección personal</a>';
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';
          }
        }
         ?>
      </nav>
    </header>

    <div class="site-content">
      <div class="container">
        <?php
          //recogemos la fecha sea por get o por post.
          if (isset($_GET['fecha'])) {
            $fecha = $_GET['fecha'];
          }else {
            $fecha = $_POST['fecha'];
          }
          //transformamos la fecha
          $fechaMostrar=explode("-", $fecha);
          $fechaMostrar=$fechaMostrar[2]."-".$fechaMostrar[1]."-".$fechaMostrar[0];

         ?>
        <!-- Contenido de la pagina. -->
        <h2>Actividades para el <?php echo $fechaMostrar; ?></h2>

        <br>
        <table id="tablamod" class="tablesorter">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod"><?php echo __('Actividad', $lang); ?></th>
            <th scope="col" id="thmod">Comentario</th>
            <th scope="col" id="thmod"><?php echo __('Recursos', $lang); ?></th>
            <th scope="col" id="thmod">Asignados</th>
            <th scope="col" id="thmod">Estado</th>
            <th scope="col" id="thmod"><?php echo __('Opciones', $lang); ?></th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <?php
          //sacamos la lista de los servicios de hoy
            $listamodificar=$servicio->listaRRHH($fecha);
            foreach ($listamodificar as $lista) {
              //sacar si hay alguna modificacion en los recursos de ese dia.
              $modRecursos = $recursos -> ModificacionId($lista['id'], $fecha);
                //guardamos en dos variables los recursos y el comentario
              if ($modRecursos == null || $modRecursos == false) {
                $recursosTotal = $lista['recursos'];
                $comentario = $lista['com_rrhh'];
              }else {
                $recursosTotal = $modRecursos['total'];
                $comentario = $modRecursos['com_rrhh'];
              }
              //sacamos el personal que hay asociado a esa actividad ese dia(si lo hay)
              $asignados = $empleados -> personalAsignado($lista['id'], $fecha);

              //sacamos los recursos del dia anterior para el boton de recuperar
              //restar un dia a la fecha elegida
              $nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
              $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
              //llamamos a la consulta para sacar los recursos de la tabla modificaciones.
              $modRecursosAyer = $recursos -> ModificacionId($lista['id'], $nuevafecha);
                //si no hay modificaciones sacamos los recursos normales
              if ($modRecursosAyer == null || $modRecursosAyer == false) {
                $recursosAyer = $lista['recursos'];
              }else {
                $recursosAyer = $modRecursosAyer['total'];
              }
              //sacamos el personal asignado para el dia anterior.
              $asignadosAyer = $empleados -> personalAsignado($lista['id'], $nuevafecha);

              //sacamos solo las actividades que no tengan 0 recursos.
              if ($recursosTotal > 0) {
                echo "<tr id='trmod'>";
                echo "<td scope='row' data-label='".__('Actividad', $lang)."' id='tdmod'>".$lista['descripcion']."</td>";
                echo "<td data-label='Comentario' id='tdmod'>".$comentario."</td>";
                echo "<td data-label='Recursos' id='tdmod'>".$recursosTotal."</td>";
                echo "<td data-label='Asignados' id='tdmod'>".$asignados['total']."</td>";

                if ($asignados['total']==0) {
                  echo "<td data-label='Estado' id='tdmod'><i class='material-icons' style='color:red;'>clear</i></td>";
                  echo "  <td data-label='".__('Opciones', $lang)."' id='tdmod'>
                  <a href='seleccionPersonal.php?id=".$lista['id']."&fecha=".$fecha."' title='Seleccionar personal'><i class='material-icons'>people</i></a>";
                  //si los recursos de ayer son iguales a los del dia seleccionado y estan asignados, sacamos el boton de recuperar personal.
                  if ($recursosAyer == $recursosTotal && $asignadosAyer['total'] == $recursosAyer) {
                    echo "<a href='recuperarPersonal.php?id=".$lista['id']."&fecha=".$fecha."' title='Recuperar personal del dia anterior.'><i class='material-icons'>repeat</i></a>";
                  }
                }else {
                  if ($asignados['total'] == $recursosTotal) {
                    echo "<td data-label='Estado' id='tdmod'><i class='material-icons' style='color:green;'>done</i></td>";
                  }else {
                    echo "<td data-label='Estado' id='tdmod'><i class='material-icons' style='color:red;'>clear</i></td>";
                  }
                  echo "  <td data-label='".__('Opciones', $lang)."' id='tdmod'>
                  <a href='editarPersonal.php?id=".$lista['id']."&fecha=".$fecha."' title='Modificar personal'><i class='material-icons'>mode_edit</i></a>";
                }

                echo "</td>";
              }

            }
           ?>
        </tbody>
      </table>
      <form class="" action="correoSupervisores.php" method="post">
        <?php
          echo "<input type='hidden' name='fecha' value='".$fecha."'>";
         ?>
        <div class="submitbuttons">
            <input class="submitone" type="submit" value="CONFIRMAR Y ENVIAR EXCEL"/>
        </div>
      </form>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!--ORDENAR TABLA -->
<script type="text/javascript" src="../../js/jquery.min.js"></script>
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
