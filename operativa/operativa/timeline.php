<?php
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

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Información de la actividad</title>
    <link rel="stylesheet" href="../../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../../css/formulario.css">
    <link rel="shortcut icon" href="../../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
    <link rel="stylesheet" href="../../css/timeline.css">
    <script type="text/javascript" src="../../js/timeline.js"></script>
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
  <span class="right"><a href="../../logout.php" id='logout'>Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../../dashboard.php" class="header__logo"><img src="../../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="../index.php">Inicio</a>
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
          <a href="../../dashboard.php">Inicio</a> >> <a href="../index.php">Gestión Actividades</a> >> <a href="actividadesActuales.php">Actividades actuales</a> >> <a href="">Información de la actividad</a>
        </div>
        <!-- Contenido de la pagina. -->
        <?php
        //llamar a las funciones necesarias para sacar la informacion
          $servicioId= $servicio->servicioId($_GET['servicio']);
          $clienteId= $cliente->ClienteId($servicioId['id_cliente']);
          $recursosId= $recursos->recursosId($servicioId['id']);
          //sacar el nombre del servicio que describimos
          echo "<h2>".$servicioId['descripcion']."</h2>";
          echo "<h4><a style='color:red;' href='modificarGeneral.php?servicio=".$servicioId['id']."'>Modificar actividad</a></h4>";
          echo "<br>";
          //parte de la derecha
          echo "<div id='listaDerecha'>";
          echo "<ul>";
          echo "<li><b>Modelos: </b>".$servicioId['modelos']."</li>";
          //descripcion (comentario depto)
          if ($servicioId['com_depto']!=null) {
            echo "<li><b>Descripción: </b>".$servicioId['com_depto']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          //tranformacion de las fechas
            $fecha=explode("-", $servicioId['f_inicio']);
            $f_inicio=$fecha[2]."-".$fecha[1]."-".$fecha[0];
          echo "<li><b>Fecha inicio: </b>".$f_inicio."</li>";
          if ($servicioId['f_fin']!= null) {
            //transformacion de la fechas
            $fecha=explode("-", $servicioId['f_fin']);
            $f_fin=$fecha[2]."-".$fecha[1]."-".$fecha[0];
            echo "<li><b>Fecha fin: </b>".$f_fin."</li>";
          }
          echo "<li><b>Recursos totales: </b>".$servicioId['recursos']."</li>";
          if ($recursosId['tm']!=null && $recursosId['tm']!=0) {
            echo "<li> <b>Turno mañana: </b>".$recursosId['tm']."</li>";
          }
          if ($recursosId['tt']!=null && $recursosId['tt']!=0) {
            echo "<li><b>Turno tarde: </b>".$recursosId['tt']."</li>";
          }
          if ($recursosId['tn']!=null && $recursosId['tn']!=0) {
            echo "<li><b>Turno noche: </b>".$recursosId['tn']."</li>";
          }
          if ($recursosId['tc']!=null && $recursosId['tc']!=0) {
            echo "<li><b>Turno central: </b>".$recursosId['tc']."</li>";
          }
          if ($recursosId['otro1']!=null && $recursosId['otro1']!=0) {
            echo "<li><b>De </b>".$recursosId['inicio1']."<b> a </b>".$recursosId['fin1'].": " .$recursosId['otro1']."</li>";
          }
          if ($recursosId['otro2']!=null && $recursosId['otro2']!=0) {
            echo "<li><b>De </b>".$recursosId['inicio2']."<b> a </b>".$recursosId['fin2'].": " .$recursosId['otro2']."</li>";
          }
          if ($recursosId['otro3']!=null && $recursosId['otro3']!=0) {
            echo "<li><b>De </b>".$recursosId['inicio3']."<b> a </b>".$recursosId['fin3'].": " .$recursosId['otro3']."</li>";
          }
          if ($recursosId['otro4']!=null && $recursosId['otro4']!=0) {
            echo "<li><b>De </b>".$recursosId['inicio4']."<b> a </b>".$recursosId['fin4'].": " .$recursosId['otro4']."</li>";
          }
          if ($recursosId['otro5']!=null && $recursosId['otro5']!=0) {
            echo "<li><b>De </b>".$recursosId['inicio5']."<b> a </b>".$recursosId['fin5'].": " .$recursosId['otro5']."</li>";
          }
          if ($recursosId['otro6']!=null && $recursosId['otro6']!=0) {
            echo "<li><b>De </b>".$recursosId['inicio6']."<b> a </b>".$recursosId['fin6'].": " .$recursosId['otro6']."</li>";
          }
          echo "<li><b>Cliente: </b>".$clienteId['nombre']."</li>";

          echo "</ul>";
          echo "</div>";
          //parte de la izquierda
          echo "<div id='listaIzquierda'>";
          echo "<ul>";
          $resp=$responsable->responsableId($servicioId['responsable']);
          echo "<li><b>Responsable: </b>".$resp['nombre']."</li>";
          echo "<li><b>Tel. responsable: </b>".$resp['telefono']."</li>";
          echo "<li><b>Correo responsable: </b><a href='mailto: ".$resp['email']."'>".$resp['email']."</a></li>";
          //comentarios
          if ($servicioId['com_supervisor']!=null) {
            echo "<li><b>Comentario Supervisor: </b>".$servicioId['com_supervisor']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['com_rrhh']!=null) {
            echo "<li><b>Comentario RRHH: </b>".$servicioId['com_rrhh']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          if ($servicioId['com_admin_fin']!=null) {
            echo "<li><b>Comentario Admin. Financiero: </b>".$servicioId['com_admin_fin']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }

          if ($servicioId['com_fin']!=null) {
            echo "<li><b>Comentario Final: </b>".$servicioId['com_fin']."</li>";
          }else {
            echo "<li id='vacio'>ftgyuhjnkm</li>";
          }
          //archivos
          if ($servicioId['qps1']!=null) {
            echo "<li><a href='".$servicioId['qps1']."' target='_new'>Descargar QPS</a></li>";
          }
          if ($servicioId['qps2']!=null) {
            echo "<li><a href='".$servicioId['qps2']."' target='_new'>Descargar QPS</a></li>";
          }
          if ($servicioId['img1']!=null) {
            echo "<li><a href='".$servicioId['img1']."' target='_new'>Descargar imagen</a></li>";
          }
          if ($servicioId['img2']!=null) {
            echo "<li><a href='".$servicioId['img2']."' target='_new'>Descargar imagen</a></li>";
          }
          if ($servicioId['video1']!=null) {
            echo "<li><a href='".$servicioId['video1']."' target='_new'>Descargar video</a></li>";
          }
          if ($servicioId['video2']!=null) {
            echo "<li><a href='".$servicioId['video2']."' target='_new'>Descargar video</a></li>";
          }
          echo "</ul>";
          echo "</div>";
         ?>

         <a href="#" target='_new'></a>
         <div class="botones">
           <button id="recursos" onclick="timeline1();">Recursos</button>
           <button id="general" onclick="timeline2();">General</button>
         </div>

        <!-- Vertical Timeline -->
        <div id="timeline2" class="hidden">
          <section id="conference-timeline">
            <div class="timeline-start">End</div>
            <div class="conference-center-line"></div>
            <div class="conference-timeline-content">
              <?php
                $modificaciones=$servicio->mod_info($servicioId['id']);
                foreach ($modificaciones as $modificacion) {
                  if ($modificacion['inicio'] != null && $modificacion['inicio'] != '0000-00-00') {

                    //sacamos los datos
                    echo "<div class='timeline-article'>";
                    echo "<div class='content-left-container'>";
                    echo "<div class='content-left'>";
                    echo "<p><b>ACTIVIDAD:</b> ".$modificacion['descripcion']."</p>";
                    echo "<p><b>MODELOS:</b> ".$modificacion['modelos']."</p>";
                    echo "<p><b>RESPONSABLE:</b> ".$modificacion['responsable']."</p>";
                    echo "</div></div>";
                    echo "<div class='meta-date' id='meta-date-left'>";
                    //transformamos la fecha
                    $inicio=explode("-", $modificacion['inicio']);
                    $fin=explode("-", $modificacion['fin']);
                    $inicio= $inicio[2]."-".$inicio[1]."-".$inicio[0];
                    $fin=$fin[2]."-".$fin[1]."-".$fin[0];
                    //sacamos la informacion
                    echo "<span class='date'>".$fin."</span>";
                    echo "<span class='date'>".$inicio."</span>";
                  }else {
                    //sacamos los datos
                    echo "<div class='timeline-article'>";
                    echo "<div class='content-right-container'>";
                    echo "<div class='content-right'>";
                    echo "<p><b>ACTIVIDAD:</b> ".$modificacion['descripcion']."</p>";
                    echo "<p><b>MODELOS:</b> ".$modificacion['modelos']."</p>";
                    echo "<p><b>RESPONSABLE:</b> ".$modificacion['responsable']."</p>";
                    echo "</div></div>";
                    echo "<div class='meta-date' id='meta-date-right'>";
                    $suelto=explode("-", $modificacion['suelto']);
                    $suelto=$suelto[2]."-".$suelto[1]."-".$suelto[0];
                    echo "<span class='month'>".$suelto."</span>";
                  }
                  echo "</div></div>";
                }
               ?>
            </div>
            <div class="timeline-end">Start</div>
          </section>
        </div>
        <div id="timeline1" class="shown">
          <section id="conference-timeline">
            <div class="timeline-start">End</div>
            <div class="conference-center-line"></div>
            <div class="conference-timeline-content">
              <?php
                $modificaciones=$servicio->dias_recursos($servicioId['id']);
                foreach ($modificaciones as $modificacion) {
                  if ($modificacion['inicio'] != null && $modificacion['inicio'] != '0000-00-00') {
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
                    echo "<div class='meta-date' id='meta-date-left'>";
                    //transformamos la fecha
                    $inicio=explode("-", $modificacion['inicio']);
                    $fin=explode("-", $modificacion['fin']);
                    $inicio= $inicio[2]."-".$inicio[1]."-".$inicio[0];
                    $fin=$fin[2]."-".$fin[1]."-".$fin[0];
                    //sacamos la informacion
                    echo "<span class='date'>".$fin."</span>";
                    echo "<span class='date'>".$inicio."</span>";
                  }else {
                    //sacamos los datos
                    echo "<div class='timeline-article'>";
                    echo "<div class='content-right-container'>";
                    echo "<div class='content-right'>";
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
                    echo "<div class='meta-date' id='meta-date-right'>";
                    //transformar la fecha y sacarla
                    $suelto=explode("-", $modificacion['suelto']);
                    $suelto=$suelto[2]."-".$suelto[1]."-".$suelto[0];
                    echo "<span class='month'>".$suelto."</span>";
                  }
                  echo "</div></div>";
                }
               ?>
            </div>
            <div class="timeline-end">Start</div>
          </section>
        </div>
        <!-- // Vertical Timeline -->
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../../js/menu.js"></script>

</body>
</html>
 <?php } ?>
