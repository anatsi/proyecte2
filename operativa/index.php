<?php
//Reconocimiento idioma
require('./languages/languages.php');
  $lang = "es";
if ( isset($_GET['lang']) ){
  $lang = $_GET['lang'];
}

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
$recursos= new Recursos();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo __('Inicio', $lang); ?></title>
    <link rel="stylesheet" href="../css/tabla.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
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
  <span class="right"><a href="../logout.php" id="logout"><?php echo __('Cerrar Sesion', $lang); ?></a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>

      <a href="../dashboard.php?lang=<?php echo $lang; ?>" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>

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

<div class="site-content">
  <div class="container">
    <?php echo "<h2>Semana ".date('W')."</h2>"; ?>
    <div class="derecha">
      <?php
      $dia=date('w');
      $fecha = date('d-m-Y');
      if ($dia == 0) {
        $hoy= strtotime('+1 day', strtotime($fecha));
        $hoy = date('d-m-Y', $hoy);
        $manana= strtotime('+2 day', strtotime($fecha));
        $manana = date('d-m-Y', $manana);
      }elseif ($dia == 6) {
        $hoy= strtotime('+2 day', strtotime($fecha));
        $hoy = date('d-m-Y', $hoy);
        $manana= strtotime('+3 day', strtotime($fecha));
        $manana = date('d-m-Y', $manana);
      }elseif ($dia == 5) {
        $hoy= date('d-m-Y');
        $manana= strtotime('+3 day', strtotime($fecha));
        $manana = date('d-m-Y', $manana);
      }else {
        $hoy=date('d-m-Y');
        $manana= strtotime('+1 day', strtotime($fecha));
        $manana = date('d-m-Y', $manana);
      }
       ?>
       <h2><?php echo $hoy; ?></h2>
      <table class="rwd-table">
        <tr>
          <th><?php echo __('Actividad', $lang); ?></th>
          <th>Relacion</th>
          <th><?php echo __('Recursos', $lang); ?></th>
          <th>Mañana</th>
          <th>Tarde</th>
          <th>Noche</th>
          <th>Central</th>
          <th>Especiales</th>
        </tr>
        <?php
        //variables para sacar el total de recursos al final de la tabla
        $totalRecursos=0;
        $totalM=0;
        $totalT=0;
        $totalN=0;
        $totalC=0;
        $totalE=0;
          //empezar a sacar la tabla
          $listahoy= $servicio->listaServiciosHoy();
          foreach ($listahoy as $servicios) {
            $recursoId=$recursos->recursosId($servicios['id']);

            //sumamos los recursos de cada servicio
            $totalRecursos=$totalRecursos + $recursoId['tm'] + $recursoId['tt'] + $recursoId['tn'] + $recursoId['tc'] + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];
            $totalM = $totalM + $recursoId['tm'];
            $totalT = $totalT + $recursoId['tt'];
            $totalN = $totalN + $recursoId['tn'];
            $totalC = $totalC + $recursoId['tc'];
            $totalE = $totalE + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];

            echo "<tr>";
            echo "<td data-th='".__('Actividad', $lang)."'>".$servicios['descripcion']."</td>";
            echo "<td data-th='Relacion'>";
            if ($servicios['relacion']!=null && $servicios['relacion']!=0) {
              $relacion=$servicio->ServicioRelacion($servicios['id']);
              echo $relacion['relacionada'];
            }
            echo "</td>";
            echo "<td data-th='".__('Recursos', $lang)."'>".$servicios['recursos']."</td>";
            echo "<td data-th='Mañana'>".$recursoId['tm']."</td>";
            echo "<td data-th='Tarde'>".$recursoId['tt']."</td>";
            echo "<td data-th='Noche'>".$recursoId['tn']."</td>";
            echo "<td data-th='Central'>".$recursoId['tc']."</td>";
            echo "<td data-th='Especiales'>";
            if ($recursoId['otro1']!=0) {
              echo $recursoId['otro1'] ." (" .$recursoId['inicio1'] ."-". $recursoId['fin1'] .")<br>";
            }
            if ($recursoId['otro2']!=0) {
              echo $recursoId['otro2'] ." (" .$recursoId['inicio2'] ."-". $recursoId['fin2'] .")<br>";
            }
            if ($recursoId['otro3']!=0) {
              echo $recursoId['otro3'] ." (" .$recursoId['inicio3'] ."-". $recursoId['fin3'] .")<br>";
            }
            if ($recursoId['otro4']!=0) {
              echo $recursoId['otro4'] ." (" .$recursoId['inicio4'] ."-". $recursoId['fin4'] .")<br>";
            }
            if ($recursoId['otro5']!=0) {
              echo $recursoId['otro5'] ." (" .$recursoId['inicio5'] ."-". $recursoId['fin5'] .")<br>";
            }
            if ($recursoId['otro6']!=0) {
              echo $recursoId['otro6'] ." (" .$recursoId['inicio6'] ."-". $recursoId['fin6'] .")<br>";
            }
            echo "</td>";
            echo "</tr>";
          }
          echo "<tr id='totalRecursos'>";
          echo "<td data-th='Actividad'>TOTAL RECURSOS</td>";
          echo "<td data-th='Relacion'></td>";
          echo "<td data-th='".__('Recursos', $lang)."'>".$totalRecursos."</td>";
          echo "<td data-th='Mañana'>".$totalM."</td>";
          echo "<td data-th='Tarde'>".$totalT."</td>";
          echo "<td data-th='Noche'>".$totalN."</td>";
          echo "<td data-th='Central'>".$totalC."</td>";
          echo "<td data-th='Especiales'>".$totalE."</td>";
          echo "</tr>";
         ?>
      </table>
    </div>
    <div class="izquierda">
      <h2><?php echo $manana; ?></h2>
      <table class="rwd-table">
        <tr>
          <th><?php echo __('Actividad', $lang); ?></th>
          <th>Relacion</th>
          <th><?php echo __('Recursos', $lang); ?></th>
          <th>Mañana</th>
          <th>Tarde</th>
          <th>Noche</th>
          <th>Central</th>
          <th>Especiales</th>
        </tr>
        <?php
        //variables para sacar el total de recursos al final de la tabla
        $totalRecursos=0;
        $totalM=0;
        $totalT=0;
        $totalN=0;
        $totalC=0;
        $totalE=0;

        require_once './bbdd/servicio.php';
        $servicio=new Servicio();
          $listamanana= $servicio->ServiciosTomorrow();
          foreach ($listamanana as $servicios) {
            $recursoId=$recursos->recursosId($servicios['id']);

            //sumamos los recursos de cada servicio
            $totalRecursos=$totalRecursos + $recursoId['tm'] + $recursoId['tt'] + $recursoId['tn'] + $recursoId['tc'] + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];
            $totalM = $totalM + $recursoId['tm'];
            $totalT = $totalT + $recursoId['tt'];
            $totalN = $totalN + $recursoId['tn'];
            $totalC = $totalC + $recursoId['tc'];
            $totalE = $totalE + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];


            echo "<tr>";
            echo "<td data-th='".__('Actividad', $lang)."'>".$servicios['descripcion']."</td>";
            echo "<td data-th='relacion'>";
            if ($servicios['relacion']!=null && $servicios['relacion']!=0) {
              $relacion=$servicio->ServicioRelacion($servicios['id']);
              echo $relacion['relacionada'];
            }
            echo "</td>";
            echo "<td data-th='".__('Recursos', $lang)."'>".$servicios['recursos']."</td>";
            echo "<td data-th='Mañana'>".$recursoId['tm']."</td>";
            echo "<td data-th='Tarde'>".$recursoId['tt']."</td>";
            echo "<td data-th='Noche'>".$recursoId['tn']."</td>";
            echo "<td data-th='Central'>".$recursoId['tc']."</td>";
            echo "<td data-th='Especiales'>";
            if ($recursoId['otro1']!=0) {
              echo $recursoId['otro1'] ." (" .$recursoId['inicio1'] ."-". $recursoId['fin1'] .")<br>";
            }
            if ($recursoId['otro2']!=0) {
              echo $recursoId['otro2'] ." (" .$recursoId['inicio2'] ."-". $recursoId['fin2'] .")<br>";
            }
            if ($recursoId['otro3']!=0) {
              echo $recursoId['otro3'] ." (" .$recursoId['inicio3'] ."-". $recursoId['fin3'] .")<br>";
            }
            if ($recursoId['otro4']!=0) {
              echo $recursoId['otro4'] ." (" .$recursoId['inicio4'] ."-". $recursoId['fin4'] .")<br>";
            }
            if ($recursoId['otro5']!=0) {
              echo $recursoId['otro5'] ." (" .$recursoId['inicio5'] ."-". $recursoId['fin5'] .")<br>";
            }
            if ($recursoId['otro6']!=0) {
              echo $recursoId['otro6'] ." (" .$recursoId['inicio6'] ."-". $recursoId['fin6'] .")<br>";
            }
            echo "</td>";
            echo "</tr>";
          }

          echo "<tr id='totalRecursos'>";
          echo "<td data-th='Actividad'>TOTAL RECURSOS</td>";
          echo "<td data-th='Relacion'></td>";
          echo "<td data-th='".__('Recursos', $lang)."'>".$totalRecursos."</td>";
          echo "<td data-th='Mañana'>".$totalM."</td>";
          echo "<td data-th='Tarde'>".$totalT."</td>";
          echo "<td data-th='Noche'>".$totalN."</td>";
          echo "<td data-th='Central'>".$totalC."</td>";
          echo "<td data-th='Especiales'>".$totalE."</td>";
          echo "</tr>";
         ?>
      </table>
    </div>
  </div>
</div>
</div>
</div>
<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../js/menu.js"></script>

</body>
</html>
<?php } ?>
