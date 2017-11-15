<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once 'cliente.php';
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
    <link rel="stylesheet" href="../css/timeline.css">
    <script type="text/javascript" src="../js/timeline.js"></script>
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
  <span class="right"><a href="../logout.php" id='logout'>Cerrar Sesion</a></span>
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
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <?php
          $servicioId= $servicio->servicioId($_GET['servicio']);
          $clienteId= $cliente->ClienteId($servicioId['id_cliente']);
          echo "<h2>".$servicioId['descripcion']."</h2>";
          //parte de la derecha
          echo "<div id='listaDerecha'>";
          echo "<ul>";
          echo "<li>Modelos: ".$servicioId['modelos']."</li>";
          echo "<li>Fecha inicio: ".$servicioId['f_inicio']."</li>";
          if ($servicioId['f_fin']!= null) {
            echo "<li>Fecha fin: ".$servicioId['f_fin']."</li>";
          }
          echo "<li>Recursos: ".$servicioId['recursos']."</li>";
          echo "<li>Cliente: ".$clienteId['nombre']."</li>";
          echo "<li>Responsable: ".$servicioId['responsable']."</li>";
          echo "<li>Tel. responsable: ".$servicioId['telefono']."</li>";
          echo "<li>Correo responsable: ".$servicioId['correo']."</li>";
          echo "</ul>";
          echo "</div>";
          //parte de la izquierda
          echo "<div id='listaIzquierda'>";
          echo "<ul>";
          if ($servicioId['com_supervisor']!=null) {
            echo "<li>Comentario Supervisor: ".$servicioId['com_supervisor']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['com_rrhh']!=null) {
            echo "<li>Comentario RRHH: ".$servicioId['com_rrhh']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['com_admin_fin']!=null) {
            echo "<li>Comentario Admin. Financiero: ".$servicioId['com_admin_fin']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['com_depto']!=null) {
            echo "<li>Comentario Dept. Operativo: ".$servicioId['com_depto']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['com_fin']!=null) {
            echo "<li>Comentario Final: ".$servicioId['com_fin']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['archivo1']!=null) {
            echo "<li><a href='".$servicioId['archivo1']."'>Descargar archivo</a></li>";
          }
          if ($servicioId['archivo2']!=null) {
            echo "<li><a href='".$servicioId['archivo2']."'>Descargar archivo</a></li>";
          }
          if ($servicioId['archivo3']!=null) {
            echo "<li><a href='".$servicioId['archivo3']."'>Descargar archivo</a></li>";
          }
          if ($servicioId['archivo4']!=null) {
            echo "<li><a href='".$servicioId['archivo4']."'>Descargar archivo</a></li>";
          }
          if ($servicioId['archivo5']!=null) {
            echo "<li><a href='".$servicioId['archivo5']."'>Descargar archivo</a></li>";
          }
          echo "</ul>";
          echo "</div>";
         ?>
         <div class="botones">
           <button id="general" onclick="timeline1();">General</button>
           <button id="recursos" onclick="timeline2();">Recursos</button>
         </div>

        <!-- Vertical Timeline -->
        <div id="timeline1" class="shown">
          <section id="conference-timeline">
            <div class="timeline-start">Start</div>
            <div class="conference-center-line"></div>
            <div class="conference-timeline-content">
              <?php
                $modificaciones=$servicio->mod_info($servicioId['id']);
                foreach ($modificaciones as $modificacion) {
                  //sacamos los datos
                  echo "<div class='timeline-article'>";
                  echo "<div class='content-left-container'>";
                  echo "<div class='content-left'>";
                  echo "<p><b>ACTIVIDAD:</b> ".$modificacion['descripcion']."</p>";
                  echo "<p><b>MODELOS:</b> ".$modificacion['modelos']."</p>";
                  echo "<p><b>RESPONSABLE:</b> ".$modificacion['responsable']."</p>";
                  if ($modificacion['com_supervisor'] != null && $modificacion['com_supervisor'] != "") {
                    echo "<p><b>COMENTARIO SUPERVISOR:</b> ".$modificacion['com_supervisor']."</p>";
                  }
                  if ($modificacion['com_rrhh'] != null && $modificacion['com_rrhh'] != "") {
                    echo "<p><b>COMENTARIO RRHH:</b> ".$modificacion['com_rrhh']."</p>";
                  }
                  if ($modificacion['com_admin_fin'] != null && $modificacion['com_admin_fin'] != "") {
                    echo "<p><b>COMENTARIO ADMIN. FINANCIERO:</b> ".$modificacion['com_admin_fin']."</p>";
                  }
                  if ($modificacion['com_depto'] != null && $modificacion['com_depto'] != "") {
                    echo "<p><b>COMENTARIO DEPT.OPERATIVO:</b> ".$modificacion['com_depto']."</p>";
                  }
                  echo "</div></div>";
                  echo "<div class='meta-date'>";
                  if ($modificacion['inicio'] != null && $modificacion['inicio'] != '0000-00-00') {
                    //transformamos la fecha
                    $inicio=explode("-", $modificacion['inicio']);
                    $fin=explode("-", $modificacion['fin']);
                    $inicio= $inicio[2]."-".$inicio[1]."-".$inicio[0];
                    $fin=$fin[2]."-".$fin[1]."-".$fin[0];
                    //sacamos la informacion
                    echo "<span class='date'>".$inicio."</span>";
                    echo "<span class='date'>".$fin."</span>";
                  }else {
                    $suelto=explode("-", $modificacion['suelto']);
                    $suelto=$suelto[2]."-".$suelto[1]."-".$suelto[0];
                    echo "<span class='month'>".$suelto."</span>";
                  }
                  echo "</div></div>";
                }
               ?>
            </div>
            <div class="timeline-end">End</div>
          </section>
        </div>
        <div id="timeline2" class="hidden">
          <section id="conference-timeline">
            <div class="timeline-start">Start</div>
            <div class="conference-center-line"></div>
            <div class="conference-timeline-content">
              <?php
                $modificaciones=$servicio->dias_recursos($servicioId['id']);
                foreach ($modificaciones as $modificacion) {
                  //sacamos los datos
                  echo "<div class='timeline-article'>";
                  echo "<div class='content-left-container'>";
                  echo "<div class='content-left'>";
                  echo "<p><b>RECURSOS TOTAL:</b> ".$modificacion['total']."</p>";
                  if ($modificacion['tm'] != 0) {
                    echo "<p><b>TURNO MAÑANA:</b> ".$modificacion['tm']."</p>";
                  }
                  if ($modificacion['tt'] != 0) {
                    echo "<p><b>TURNO TARDE:</b> ".$modificacion['tt']."</p>";
                  }
                  if ($modificacion['tn'] != 0) {
                    echo "<p><b>TURNO NOCHE:</b> ".$modificacion['tn']."</p>";
                  }
                  if ($modificacion['tc'] != 0) {
                    echo "<p><b>TURNO CENTRAL:</b> ".$modificacion['tc']."</p>";
                  }
                  if ($modificacion['otro1'] != 0) {
                    echo "<p><b>DE ".$modificacion['inicio1']." A ".$modificacion['fin1'].":</b> ".$modificacion['otro1']."</p>";
                  }
                  if ($modificacion['otro2'] != 0) {
                    echo "<p><b>DE ".$modificacion['inicio2']." A ".$modificacion['fin2'].":</b> ".$modificacion['otro2']."</p>";
                  }
                  if ($modificacion['otro3'] != 0) {
                    echo "<p><b>DE ".$modificacion['inicio3']." A ".$modificacion['fin3'].":</b> ".$modificacion['otro3']."</p>";
                  }
                  if ($modificacion['otro4'] != 0) {
                    echo "<p><b>DE ".$modificacion['inicio4']." A ".$modificacion['fin4'].":</b> ".$modificacion['otro4']."</p>";
                  }
                  if ($modificacion['otro5'] != 0) {
                    echo "<p><b>DE ".$modificacion['inicio5']." A ".$modificacion['fin5'].":</b> ".$modificacion['otro5']."</p>";
                  }
                  if ($modificacion['otro6'] != 0) {
                    echo "<p><b>DE ".$modificacion['inicio6']." A ".$modificacion['fin6'].":</b> ".$modificacion['otro6']."</p>";
                  }

                  echo "</div></div>";
                  echo "<div class='meta-date'>";
                  if ($modificacion['inicio'] != null && $modificacion['inicio'] != '0000-00-00') {
                    //transformamos la fecha
                    $inicio=explode("-", $modificacion['inicio']);
                    $fin=explode("-", $modificacion['fin']);
                    $inicio= $inicio[2]."-".$inicio[1]."-".$inicio[0];
                    $fin=$fin[2]."-".$fin[1]."-".$fin[0];
                    //sacamos la informacion
                    echo "<span class='date'>".$inicio."</span>";
                    echo "<span class='date'>".$fin."</span>";
                  }else {
                    $suelto=explode("-", $modificacion['suelto']);
                    $suelto=$suelto[2]."-".$suelto[1]."-".$suelto[0];
                    echo "<span class='month'>".$suelto."</span>";
                  }
                  echo "</div></div>";
                }
               ?>
            </div>
            <div class="timeline-end">End</div>
          </section>
        </div>
        <!-- // Vertical Timeline -->
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
