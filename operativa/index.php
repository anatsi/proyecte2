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
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
    <link rel="stylesheet" href="../css/modificar.css">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <style media="screen">
      tr:nth-child(even) {
        background-color: #CAC6C5;
      }
      table{
        color: black;
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
  <span class="right"><a href="../logout.php" id="logout"><?php echo __('Cerrar Sesion', $lang); ?></a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>

      <a href="../dashboard.php?lang=<?php echo $lang; ?>" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <!-- menu -->
      <nav class="menu">
        <a href="index.php?lang=<?php echo $lang; ?>"><?php echo __('Inicio', $lang); ?></a>
        <?php
        $menu=$usuario->menuDash($_SESSION['usuario']);
        $opciones = explode(",", $menu['menu']);
        foreach ($opciones as $opcion) {
          if ($opcion == 21) {
            echo '<a href="./operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='./operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='./operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='./operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='./operativa/nuevoCliente.php'>Nuevo cliente/resp.</a>";
          }elseif ($opcion == 22) {
            echo '<a href="./rrhh/filtroRRHH.php">Selección personal</a>';

          }elseif ($opcion == 23) {
            echo '<a href="./supervisores/filtroSupervisores.php">Jefe de turno</a>';

          }elseif ($opcion == 0) {
            echo '<a href="./operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='./operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='./operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='./operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='./operativa/nuevoCliente.php'>Nuevo cliente/resp.</a>";
            echo '<a href="./rrhh/filtroRRHH.php">Selección personal</a>';
            echo '<a href="./supervisores/filtroSupervisores.php">Jefe de turno</a>';

          }
        }
         ?>
      </nav>

    </header>

<div class="site-content">
  <div class="container">
    <!-- migas de pan -->
    <div class="breadcrumb" style="margin-left: 2%; color:black;">
      <a href="../dashboard.php">Inicio</a> >> <a href="index.php">Gestión actividades</a>
    </div>
    <!-- titulo -->
    <?php echo "<h2>Semana ".date('W')."</h2>"; ?>
      <?php
      //comprobar que dia sera mañana para que no saque los sabados/domingos
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
       <!-- primera tabla -->
       <h2><?php echo $hoy; ?></h2>
       <table id="tablamod" class="tablesorter">
       <thead id="theadmod">
         <tr id="trmod">
           <th scope="col" id="thmod">Actividad</th>
           <th scope="col" id="thmod">Recursos</th>
           <th scope="col" id="thmod">Mañana</th>
           <th scope="col" id="thmod">Tarde</th>
           <th scope="col" id="thmod">Noche</th>
           <th scope="col" id="thmod">Central</th>
           <th scope="col" id="thmod">Especiales</th>
         </tr>
       </thead>
       <tbody id="tbodymod">
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
             $fechaHoy=date('Y-m-d', strtotime($hoy));
             //sacamos los recursos de la actividad para ese dia.
             $recursoId = $recursos -> ModificacionId($servicios['id'], $fechaHoy);
             if ($recursoId == null || $recursoId == false) {
               $recursoId = $recursos -> RecursosId($servicios['id']);
             }

             //sumamos los recursos de cada servicio
             $totalRecursos=$totalRecursos + $recursoId['tm'] + $recursoId['tt'] + $recursoId['tn'] + $recursoId['tc'] + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];
             $totalM = $totalM + $recursoId['tm'];
             $totalT = $totalT + $recursoId['tt'];
             $totalN = $totalN + $recursoId['tn'];
             $totalC = $totalC + $recursoId['tc'];
             $totalE = $totalE + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];

             echo "<tr id='trmod'>";
             echo "<td data-label='".__('Actividad', $lang)."' id='tdmod'>".$servicios['descripcion']."</td>";
             echo "<td data-label='".__('Recursos', $lang)."' id='tdmod'>".$recursoId['total']."</td>";
             echo "<td data-label='Mañana' id='tdmod'>".$recursoId['tm']."</td>";
             echo "<td data-label='Tarde' id='tdmod'>".$recursoId['tt']."</td>";
             echo "<td data-label='Noche' id='tdmod'>".$recursoId['tn']."</td>";
             echo "<td data-label='Central' id='tdmod'>".$recursoId['tc']."</td>";
             echo "<td data-label='Especiales' id='tdmod'>";
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
           echo "<tr id='totalRecursos trmod'>";
           echo "<td data-label='Actividad' id='tdmod'>TOTAL RECURSOS</td>";
           echo "<td data-label='".__('Recursos', $lang)."' id='tdmod'>".$totalRecursos."</td>";
           echo "<td data-label='Mañana' id='tdmod'>".$totalM."</td>";
           echo "<td data-label='Tarde' id='tdmod'>".$totalT."</td>";
           echo "<td data-label='Noche' id='tdmod'>".$totalN."</td>";
           echo "<td data-label='Central' id='tdmod'>".$totalC."</td>";
           echo "<td data-label='Especiales' id='tdmod'>".$totalE."</td>";
           echo "</tr>";
          ?>
       </tbody>
     </table>


      <!-- segunda tabla -->
      <h2><?php echo $manana; ?></h2>
      <table id="tablamod" class="tablesorter">
      <thead id="theadmod">
        <tr id="trmod">
          <th scope="col" id="thmod">Actividad</th>
          <th scope="col" id="thmod">Recursos</th>
          <th scope="col" id="thmod">Mañana</th>
          <th scope="col" id="thmod">Tarde</th>
          <th scope="col" id="thmod">Noche</th>
          <th scope="col" id="thmod">Central</th>
          <th scope="col" id="thmod">Especiales</th>
        </tr>
      </thead>
      <tbody id="tbodymod">
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
            $fechaHoy=date('Y-m-d', strtotime($manana));
            //sacamos los recursos de la actividad para ese dia.
            $recursoId = $recursos -> ModificacionId($servicios['id'], $fechaHoy);
            if ($recursoId == null || $recursoId == false) {
              $recursoId = $recursos -> RecursosId($servicios['id']);
            }

            //sumamos los recursos de cada servicio
            $totalRecursos=$totalRecursos + $recursoId['tm'] + $recursoId['tt'] + $recursoId['tn'] + $recursoId['tc'] + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];
            $totalM = $totalM + $recursoId['tm'];
            $totalT = $totalT + $recursoId['tt'];
            $totalN = $totalN + $recursoId['tn'];
            $totalC = $totalC + $recursoId['tc'];
            $totalE = $totalE + $recursoId['otro1'] + $recursoId['otro2'] + $recursoId['otro3'] + $recursoId['otro4'] + $recursoId['otro5'] + $recursoId['otro6'];


            echo "<tr id='trmod'>";
            echo "<td data-label='".__('Actividad', $lang)."' id='tdmod'>".$servicios['descripcion']."</td>";
            echo "<td data-label='".__('Recursos', $lang)."' id='tdmod'>".$recursoId['total']."</td>";
            echo "<td data-label='Mañana' id='tdmod'>".$recursoId['tm']."</td>";
            echo "<td data-label='Tarde' id='tdmod'>".$recursoId['tt']."</td>";
            echo "<td data-label='Noche' id='tdmod'>".$recursoId['tn']."</td>";
            echo "<td data-label='Central' id='tdmod'>".$recursoId['tc']."</td>";
            echo "<td data-label='Especiales' id='tdmod'>";
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

          echo "<tr id='totalRecursos trmod0'>";
          echo "<td data-label='Actividad' id='tdmod'>TOTAL RECURSOS</td>";
          echo "<td data-label='".__('Recursos', $lang)."' id='tdmod'>".$totalRecursos."</td>";
          echo "<td data-label='Mañana' id='tdmod'>".$totalM."</td>";
          echo "<td data-label='Tarde' id='tdmod'>".$totalT."</td>";
          echo "<td data-label='Noche' id='tdmod'>".$totalN."</td>";
          echo "<td data-label='Central' id='tdmod'>".$totalC."</td>";
          echo "<td data-label='Especiales' id='tdmod'>".$totalE."</td>";
          echo "</tr>";
         ?>
      </tbody>
      </table>
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
