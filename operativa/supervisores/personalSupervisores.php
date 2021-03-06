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
require_once '../bbdd/comentarios.php';

$usuario=new User();
$sesion=new Sesiones();
$servicio=new Servicio();
$recursos=new Recursos();
$empleado = new Empleados();
$personal = new Personal();
$comentario = new Comentarios();

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
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente/resp.</a>";
          }elseif ($opcion == 22) {
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
          }elseif ($opcion == 23) {
            echo '<a href="filtroSupervisores.php">Jefe de turno</a>';

          }elseif ($opcion == 0) {
            echo '<a href="../operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='../operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='../operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='../operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente/resp.</a>";
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
      echo "<h3><a href='../PDF/pdfSupervisor.php?fecha=".$_POST['fecha']."&turno=".$_POST['turno']."&u=".$_SESSION['usuario']."&x=1' title='Visualizar personal' target='_blank'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAASzSURBVGhD7VlbixxFFB4RjYhGErwHgghCkBgiC2Z3qmYnxFXXqZ7EBJaARhQfFC8vPqiBQPISCMEfIMlDYi4++CTeEFRIQkyIribkIjEXlSw70725aaIkMbfNd3pOT1fN1Gx6prtnRtgPPna7zulzzleX7qqeTDMoOfl7y45c6Sr5k+uIs64jx1umkv+VldztFcUzHL49KBfksrIj/rYWFZNlJTYeHRycwqnSA3r/bVsByVLsGHm2dzqnTB5uQc5Dj12tTYy2P/D351aIKXUef414RMTcXx6U93HqZIE1sc1IqORvmNe9bG4Jo4XsXMS9bsStxhe/esV5D7BrMhhRvTP0hPj/XMkRM9kcC9T7hgCNWIsHTg8+NZVd46NcEEtqEqxlU2wg3td67Fpi+n2R2AMAhb9hJCjmXmVTbCD2V0ZsC+Gzy13cdz/f0jrQK2/qgT1HvMim2IgixCfWTOwHQFcIAeF7IJaYbhFChP+ektNzJ9/eHLpJCBFPzc/Hh4Zu5RDR0W1CiBDzLoeIjlSFKLFBjx2VqOl80+slTSElp28WYu6zbX9uRtrAcphoSFNIFIwN5e/CVMpjQ3lEr8MtiA/YJRo6LSQA6nhdrwPClrMpGrpFCOXV65gUErDrhdDGDEX3WLhGD4CFt8LiE4unFmYf5jIaIpIQakSB9kNOu6jkh1yOFZGEoFeqx05fEO02HfEdEW3DuP4rDFD1Oxj4MPeg/Uytn08lT9T4+kTc7YEPariGkbmbS6pDxBHRHeQwN1cxnsncUnLkC0h+NPArqWyOzQY8J9uHJDu0eGDjhYmY1a8yE508mxei5E5uroO7KP8IAlwkPySV3FyHQ0OP326K6bAQDP07uOEbT8lHuYkS03SrCqm8ecWPmBqf0AcF3wnwnu9/IowbJuWY63wqsVlfm6kJQXH+E8sr5hZxExXyGbUFQs4ODNwT3itO0Wj4jgBiHa7YwqQQ3XDHm/aIbPkzn7+Dm6gQl/ysQsCTqv8x3xEIROtJadQwwgNEdJSx7UhNSC3KKlsI/BoKeS73kO8MoNBPK+2WpAAVrt+buhB6atH0gu104GcTgtG6op/ekIwXfJiU1tQJJacRK084zgumJoSKpcWNni0ZPqBViBJf+jcCdLbGvf9WbGHSjqyR+ptDBkLoJYbrfeAm/dSGp9Fbof//QIgNXlHOQcHazw8dESJ+4OYM3t4vGTadxdwCdquCNp7oiPcxxf4x/cOkuG74mTS2EKwBT3O4hF4L9kO/mzfrFCchejv5kXjEOGb3A5UY8eMp8T2EXrD6YMcw0XfeSEJKSiyGcdR0bCf9TnmNy7EikpCJgJ6ePCEmiUkhASaFJIzEheAR+gqb2or6d1ruPTZFg+tklxoBlFjNprYCW55Veh24fplN0WCe9HyeGSvkH2RzW0B7OMyMMb2O0YX9T7I5OvA25pMeU4m9ruqbzeZUwV/sh/X8GI3jbG4OddOLgil5jQRp25nEifi/2H5yoPXCpTUPBPi4NmCHuJVLag3jPT23YYp9ZAncRor1+seNWMBwz8e0+hbz9LI9WbLEFLtC08xz5NNcQrKgszcteEoQfBVJlIhL8Sf6jGoik7kBPxfOVGbDVUsAAAAASUVORK5CYII='></a></h3>";
     ?>
     <?php
       //transformar los turnos.
       //convertimos el turno que nos han enviado.
       if ($_POST['turno'] == 'Mañana') {
         $turno = 'tm';
       }elseif ($_POST['turno'] == 'Tarde') {
         $turno = 'tt';
       }elseif ( $_POST['turno'] == 'Noche') {
         $turno = 'tn';
       }
       //Sacar cartel de si se habia confirmado el turno o no
       $supervisor = $personal->nombreSuper($_POST['fecha'], $turno);
       if ($supervisor == null || $supervisor == false || $supervisor == '') {
         echo "<h6 style='color:green; margin-left:9%;'>Todavia no se ha confirmado el personal</h6>";
       }else {
         echo "<h6 style='color:red; margin-left:9%;'>".$supervisor['usuario']." ya ha confirmado el personal</h6>";
       }

      ?>

    <form method="post" id="formulario" action="guardarSupervisores.php" method="post">

      <?php

        //sacamos las actividades que hay para el dia seleccionado.
        $actividadesHoy = $servicio -> listaRRHH($_POST['fecha']);
        $personalHoy = $personal -> personalHoy($_POST['fecha'], $turno);
        //recorremos las actividades para ver si tienen comentarios de operativa
        echo "<div class='formthird' style='width: 100%; margin-bottom: 2%; border: 1px solid black;'>";
        echo "<h5 style='color:red'>COMENTARIOS OPERATIVA:</h5>";
        foreach ($actividadesHoy as $act) {
          //sacamos los recursos de la actividad para ese dia.
          $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
          if ($recurso == null || $recurso == false) {
            $recurso = $recursos -> RecursosId($act['id']);
          }
          //comprobar si la actividad tiene recursos para ese dia y ese turno
          if ($recurso[$turno]>0) {
            //sacamos si hay modificaciones para coger el comentario de estas
           $comentariosMod=$recursos -> ModificacionId($act['id'], $_POST['fecha']);
           $comentarios=$servicio->ServicioId($act['id']);
           if ($comentariosMod['com_supervisor'] != null && $comentariosMod['com_supervisor'] != false) {
             echo "<p><b>".$comentarios['descripcion']."</b>: ".$comentariosMod['com_supervisor']."</p>";
           }else {
             if ($comentarios['com_supervisor'] != null && $comentarios['com_supervisor'] != false) {
               echo "<p><b>".$comentarios['descripcion']."</b>: ".$comentarios['com_supervisor']."</p>";
             }
           }
         }else {
           // si no tiene, comprbamos si tiene algun turno especial
           if ($recurso['tc']>0 || $recurso['otro1']>0 || $recurso['otro2']>0 || $recurso['otro3']>0 || $recurso['otro4']>0 || $recurso['otro5']>0 || $recurso['otro6']>0) {
             //sacamos si hay modificaciones para coger el comentario de estas
            $comentariosMod=$recursos -> ModificacionId($act['id'], $_POST['fecha']);
            $comentarios=$servicio->ServicioId($act['id']);
            if ($comentariosMod['com_supervisor'] != null && $comentariosMod['com_supervisor'] != false) {
              echo "<p><b>".$comentarios['descripcion']."</b>: ".$comentariosMod['com_supervisor']."</p>";
            }else {
              if ($comentarios['com_supervisor'] != null && $comentarios['com_supervisor'] != false) {
                echo "<p><b>".$comentarios['descripcion']."</b>: ".$comentarios['com_supervisor']."</p>";
              }
            }
           }
         }

        }
        echo "</div>";
       ?>
       <?php
        //comentarios de rrhh a jefes de turno.
        //recorremos las actividades para ver si tienen comentarios de rrhh
        echo "<div class='formthird' style='width: 100%; margin-bottom: 2%; border: 1px solid black;'>";
        echo "<h5 style='color:red'>COMENTARIOS RRHH:</h5>";
        foreach ($actividadesHoy as $act) {
          //sacamos los recursos de la actividad para ese dia.
          $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
          if ($recurso == null || $recurso == false) {
            $recurso = $recursos -> RecursosId($act['id']);
          }
          //comprobar si la actividad tiene recursos para ese dia y ese turno
          if ($recurso[$turno]>0) {
            $comentarios=$comentario->sacarComentario($act['id'], $_POST['fecha']);
            if ($comentarios != null && $comentarios!= false) {
              //sacamos si hay modificaciones para coger el comentario de estas
              echo "<p><b>".$act['descripcion']."</b>: ".$comentarios['comentario']."</p>";
            }
         }else {
           // si no tiene, comprbamos si tiene algun turno especial
           if ($recurso['tc']>0 || $recurso['otro1']>0 || $recurso['otro2']>0 || $recurso['otro3']>0 || $recurso['otro4']>0 || $recurso['otro5']>0 || $recurso['otro6']>0) {
             $comentarios=$comentario->sacarComentario($act['id'], $_POST['fecha']);
             if ($comentarios != null && $comentarios!= false) {
               //sacamos si hay modificaciones para coger el comentario de estas
               echo "<p><b>".$act['descripcion']."</b>: ".$comentarios['comentario']."</p>";
             }
           }
         }

        }
        echo "</div>";
        ?>

    <?php
    $recursosNormales=0;
    $recursosRaros=0;
    $c=0;
      //actividad de conductores.
      $actCon = $servicio -> actConductores();
      if ($actCon != null && $actCon!=false) {
        $personalCon = $personal->empleadosServicio($actCon['id'], $_POST['fecha'], $turno);
        //sacamos los recursos de la actividad para ese dia.
        $recursoCon = $recursos -> ModificacionId($actCon['id'], $_POST['fecha']);
        if ($recursoCon == null || $recursoCon == false) {
          $recursoCon = $recursos -> RecursosId($actCon['id']);
        }
        //echo "<div class='formthird'>";
        if ($personalCon != false && $personalCon != null) {
          echo "<p><label><i class='fa fa-question-circle'></i>".$actCon['descripcion']." - ".$recursoCon[$turno]." </label></p>";
          $recursosNormales = $recursosNormales + $recursoCon[$turno];
          echo "<input type='hidden' name='conID' value='".$actCon['id']."'>";
          //sacar los selects con la gente seleccionada
          foreach ($personalCon as $persona) {
            echo "<p><select name='con".$c."[]' class='test' id='multiple'>";
            echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
            if ($persona['empleado'] == '') {
              echo "<option value='' selected>SIN ASIGNAR</option>";
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
            echo "</select>";
            echo "<select name='furgo".$c."[]' class='test' id='multiple'>";
            ?>
              <option value="CARGAS">CARGAS</option>
              <option value="VARIOS">VARIOS</option>
              <option value="CANOPY">CANOPY</option>
              <option value="P9-UBICACION">P9-UBICACION</option>
            </select>
            <?php
            echo "</p>";
          }
          $c++;
        }else {
          if ($recursoCon[$turno]>0) {
            //si la actividad esta para hoy, pero no hay nadie asignado, comprobamos cuantos recursos deberia de tener.
              echo "<p><label><i class='fa fa-question-circle'></i>".$actCon['descripcion']." - ".$recursoCon[$turno]." </label></p>";
              $recursosNormales = $recursosNormales + $recursoCon[$turno];
              echo "<input type='hidden' name='conID' value='".$actCon['id']."'>";
              //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
              for ($r=0; $r < $recursoCon[$turno] ; $r++) {
                echo "<p><select name='con".$c."[]' class='test' id='multiple'>";
                echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                echo "<option value='' selected>SIN ASIGNAR</option>";
                //sacamos la lista de personal
                foreach ($personalHoy as $disponibles) {
                  //comparamos para sacar como seleccionado el que ja estaba
                  echo "<option value='".$disponibles['empleado']."'>".$disponibles['empleado']."</option>";
                }
                echo "</select>";
                echo "<select name='furgo".$c."[]' class='test' id='multiple'>";
                ?>
                  <option value="CARGAS">CARGAS</option>
                  <option value="VARIOS">VARIOS</option>
                  <option value="CANOPY">CANOPY</option>
                  <option value="P9-UBICACION">P9-UBICACION</option>
                </select>
                <?php
                echo "</p>";
              }
              $c++;
          }
        //echo "</div>";
        }
      }

     ?>

      <?php
      $i=0;
      //guardar la fecha en un input para pasarla a la siguiente pantalla
      echo "<input type='hidden' name='conductores' value='".$c."'>";
      echo "<input type='hidden' name='fecha' value='".$_POST['fecha']."'>";
      echo "<input type='hidden' name='turno' value='".$turno."'>";

        //recorremos cada una de las actividades.
        foreach ($actividadesHoy as $act) {
          if ($act['descripcion']!='Conductores') {
            //sacamos los recursos de la actividad para hoy
            $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
            if ($recurso == null || $recurso == false) {
              $recurso = $recursos -> RecursosId($act['id']);
            }
            //sacamos el nombre de la actividad
            echo "<div class='formthird'>";
            //sacar los nombres asignados a esa actividad para ese dia.
            $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], $turno);
            if ($asignados != null && $asignados != false) {
              echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso[$turno]." </label></p>";
              $recursosNormales = $recursosNormales + $recurso[$turno];
              echo "<input type='hidden' name='act".$i."[]' value='".$act['id']."'>";
              //sacamos tantos selects como personas asignadas habia para ese dia.
              foreach ($asignados as $persona) {
                echo "<p><select name='select".$i."[]' class='test' id='multiple'>";
                echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                if ($persona['empleado'] == '') {
                  echo "<option value='' selected>SIN ASIGNAR</option>";
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
              if ($recurso[$turno]>0) {
                echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso[$turno]." </label></p>";
                $recursosNormales = $recursosNormales + $recurso[$turno];
                echo "<input type='hidden' name='act".$i."[]' value='".$act['id']."'>";

                //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
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

            echo "<input type='hidden' name='normal' value='".$i."'>";
          echo "</div>";
          }
          }

      ?>
      <br><br>
      <hr style="width:100%;">
      <br>
      <?php
      $i=0;
        //TURNO CENTRAL
        $personalHoy = $personal -> personalHoy($_POST['fecha'], 'tc');

        //recorremos cada una de las actividades.
        foreach ($actividadesHoy as $act) {
          //sacamos el numero de recursos que deberia de tener la actividad.
          $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
          if ($recurso == null || $recurso == false) {
            $recurso = $recursos -> RecursosId($act['id']);
          }
          //sacamos el nombre de la actividad
          echo "<div class='formthird'>";
          //sacar los nombres asignados a esa actividad para ese dia.
          $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'tc');
          if ($asignados != null && $asignados != false) {
            echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['tc']."per.</label></p>";
            $recursosRaros = $recursosRaros + $recurso['tc'];
            echo "<p><label><i class='fa fa-question-circle'></i>TURNO CENTRAL</label></p>";
            echo "<input type='hidden' name='tc".$i."[]' value='".$act['id']."'>";
            //sacamos tantos selects como personas asignadas habia para ese dia.
            foreach ($asignados as $persona) {
              echo "<p><select name='selectTC".$i."[]' class='test' id='multiple'>";
              echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
              if ($persona['empleado'] == '') {
                echo "<option value='' selected>SIN ASIGNAR</option>";
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

            if ($recurso['tc']>0) {
              echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['tc']." </label></p>";
              $recursosRaros = $recursosRaros + $recurso['tc'];
              echo "<p><label><i class='fa fa-question-circle'></i>TURNO CENTRAL</label></p>";
              echo "<input type='hidden' name='tc".$i."[]' value='".$act['id']."'>";

              //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
              for ($r=0; $r < $recurso['tc'] ; $r++) {
                echo "<p><select name='selectTC".$i."[]' class='test' id='multiple'>";
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

          echo "<input type='hidden' name='tc' value='".$i."'>";

        echo "</div>";
        }
       ?>

       <?php
       $i=0;
         //TURNO ESPECIAL 1
         $personalHoy = $personal -> personalHoy($_POST['fecha'], '%');

         //recorremos cada una de las actividades.
         foreach ($actividadesHoy as $act) {
           //sacamos el numero de recursos que deberia de tener la actividad.
           $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
           if ($recurso == null || $recurso == false) {
             $recurso = $recursos -> RecursosId($act['id']);
           }
           //sacamos el nombre de la actividad
           echo "<div class='formthird'>";
           //sacar los nombres asignados a esa actividad para ese dia.
           $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro1');
           if ($asignados != null && $asignados != false) {
             echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro1']." </label></p>";
             $recursosRaros = $recursosRaros + $recurso['otro1'];
             echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio1']." HASTA ".$recurso['fin1']."</label></p>";
             echo "<input type='hidden' name='otro1".$i."[]' value='".$act['id']."'>";
             //sacamos tantos selects como personas asignadas habia para ese dia.
             foreach ($asignados as $persona) {
               echo "<p><select name='selectO1".$i."[]' class='test' id='multiple'>";
               echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
               if ($persona['empleado'] == '') {
                 echo "<option value='' selected>SIN ASIGNAR</option>";
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

             if ($recurso['otro1']>0) {
               echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro1']." </label></p>";
               $recursosRaros = $recursosRaros + $recurso['otro1'];
               echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio1']." HASTA ".$recurso['fin1']."</label></p>";
               echo "<input type='hidden' name='otro1".$i."[]' value='".$act['id']."'>";

               //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
               for ($r=0; $r < $recurso['otro1'] ; $r++) {
                 echo "<p><select name='selectO1".$i."[]' class='test' id='multiple'>";
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

           echo "<input type='hidden' name='otro1' value='".$i."'>";

         echo "</div>";
         }
        ?>

        <?php
        $i=0;
          //TURNO ESPECIAL 2
          $personalHoy = $personal -> personalHoy($_POST['fecha'], '%');

          //recorremos cada una de las actividades.
          foreach ($actividadesHoy as $act) {
            //sacamos el numero de recursos que deberia de tener la actividad.
            $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
            if ($recurso == null || $recurso == false) {
              $recurso = $recursos -> RecursosId($act['id']);
            }
            //sacamos el nombre de la actividad
            echo "<div class='formthird'>";
            //sacar los nombres asignados a esa actividad para ese dia.
            $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro2');
            if ($asignados != null && $asignados != false) {
              echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro2']." </label></p>";
              $recursosRaros = $recursosRaros + $recurso['otro2'];
              echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio2']." HASTA ".$recurso['fin2']."</label></p>";
              echo "<input type='hidden' name='otro2".$i."[]' value='".$act['id']."'>";
              //sacamos tantos selects como personas asignadas habia para ese dia.
              foreach ($asignados as $persona) {
                echo "<p><select name='selectO2".$i."[]' class='test' id='multiple'>";
                echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                if ($persona['empleado'] == '') {
                  echo "<option value='' selected>SIN ASIGNAR</option>";
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

              if ($recurso['otro2']>0) {
                echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro2']." </label></p>";
                $recursosRaros = $recursosRaros + $recurso['otro2'];
                echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio2']." HASTA ".$recurso['fin2']."</label></p>";
                echo "<input type='hidden' name='otro2".$i."[]' value='".$act['id']."'>";

                //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
                for ($r=0; $r < $recurso['otro2'] ; $r++) {
                  echo "<p><select name='selectO2".$i."[]' class='test' id='multiple'>";
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
            echo "<input type='hidden' name='otro2' value='".$i."'>";


          echo "</div>";
          }
         ?>

         <?php
         $i=0;
           //TURNO ESPECIAL 3
           $personalHoy = $personal -> personalHoy($_POST['fecha'], '%');

           //recorremos cada una de las actividades.
           foreach ($actividadesHoy as $act) {
             //sacamos el numero de recursos que deberia de tener la actividad.
             $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
             if ($recurso == null || $recurso == false) {
               $recurso = $recursos -> RecursosId($act['id']);
             }
             //sacamos el nombre de la actividad
             echo "<div class='formthird'>";
             //sacar los nombres asignados a esa actividad para ese dia.
             $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro3');
             if ($asignados != null && $asignados != false) {
               echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro3']." </label></p>";
               $recursosRaros = $recursosRaros + $recurso['otro3'];
               echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio3']." HASTA ".$recurso['fin3']."</label></p>";
               echo "<input type='hidden' name='otro3".$i."[]' value='".$act['id']."'>";
               //sacamos tantos selects como personas asignadas habia para ese dia.
               foreach ($asignados as $persona) {
                 echo "<p><select name='selectO3".$i."[]' class='test' id='multiple'>";
                 echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                 if ($persona['empleado'] == '') {
                   echo "<option value='' selected>SIN ASIGNAR</option>";
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

               if ($recurso['otro3']>0) {
                 echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro3']." </label></p>";
                 $recursosRaros = $recursosRaros + $recurso['otro3'];
                 echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio3']." HASTA ".$recurso['fin3']."</label></p>";
                 echo "<input type='hidden' name='otro3".$i."[]' value='".$act['id']."'>";

                 //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
                 for ($r=0; $r < $recurso['otro3'] ; $r++) {
                   echo "<p><select name='selectO3".$i."[]' class='test' id='multiple'>";
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

             echo "<input type='hidden' name='otro3' value='".$i."'>";

           echo "</div>";
           }
          ?>

          <?php
          $i=0;
            //TURNO ESPECIAL 4
            $personalHoy = $personal -> personalHoy($_POST['fecha'], '%');

            //recorremos cada una de las actividades.
            foreach ($actividadesHoy as $act) {
              //sacamos el numero de recursos que deberia de tener la actividad.
              $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
              if ($recurso == null || $recurso == false) {
                $recurso = $recursos -> RecursosId($act['id']);
              }
              //sacamos el nombre de la actividad
              echo "<div class='formthird'>";
              //sacar los nombres asignados a esa actividad para ese dia.
              $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro4');
              if ($asignados != null && $asignados != false) {
                echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro4']." </label></p>";
                $recursosRaros = $recursosRaros + $recurso['otro4'];
                echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio4']." HASTA ".$recurso['fin4']."</label></p>";
                echo "<input type='hidden' name='otro4".$i."[]' value='".$act['id']."'>";
                //sacamos tantos selects como personas asignadas habia para ese dia.
                foreach ($asignados as $persona) {
                  echo "<p><select name='selectO4".$i."[]' class='test' id='multiple'>";
                  echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                  if ($persona['empleado'] == '') {
                    echo "<option value='' selected>SIN ASIGNAR</option>";
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

                if ($recurso['otro4']>0) {
                  echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro4']." </label></p>";
                  $recursosRaros = $recursosRaros + $recurso['otro4'];
                  echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio4']." HASTA ".$recurso['fin4']."</label></p>";
                  echo "<input type='hidden' name='otro4".$i."[]' value='".$act['id']."'>";

                  //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
                  for ($r=0; $r < $recurso['otro4'] ; $r++) {
                    echo "<p><select name='selectO4".$i."[]' class='test' id='multiple'>";
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

              echo "<input type='hidden' name='otro4' value='".$i."'>";

            echo "</div>";
            }
           ?>

           <?php
           $i=0;
             //TURNO ESPECIAL 5
             $personalHoy = $personal -> personalHoy($_POST['fecha'], '%');

             //recorremos cada una de las actividades.
             foreach ($actividadesHoy as $act) {
               //sacamos el numero de recursos que deberia de tener la actividad.
               $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
               if ($recurso == null || $recurso == false) {
                 $recurso = $recursos -> RecursosId($act['id']);
               }
               //sacamos el nombre de la actividad
               echo "<div class='formthird'>";
               //sacar los nombres asignados a esa actividad para ese dia.
               $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro5');
               if ($asignados != null && $asignados != false) {
                 echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro5']." </label></p>";
                 $recursosRaros = $recursosRaros + $recurso['otro5'];
                 echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio5']." HASTA ".$recurso['fin5']."</label></p>";
                 echo "<input type='hidden' name='otro5".$i."[]' value='".$act['id']."'>";
                 //sacamos tantos selects como personas asignadas habia para ese dia.
                 foreach ($asignados as $persona) {
                   echo "<p><select name='selectO5".$i."[]' class='test' id='multiple'>";
                   echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                   if ($persona['empleado'] == '') {
                     echo "<option value='' selected>SIN ASIGNAR</option>";
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

                 if ($recurso['otro5']>0) {
                   echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro5']." </label></p>";
                   $recursosRaros = $recursosRaros + $recurso['otro5'];
                   echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio5']." HASTA ".$recurso['fin5']."</label></p>";
                   echo "<input type='hidden' name='otro5".$i."[]' value='".$act['id']."'>";

                   //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
                   for ($r=0; $r < $recurso['otro5'] ; $r++) {
                     echo "<p><select name='selectO5".$i."[]' class='test' id='multiple'>";
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

               echo "<input type='hidden' name='otro5' value='".$i."'>";

             echo "</div>";
             }
            ?>

            <?php
            $i=0;
              //TURNO ESPECIAL 6
              $personalHoy = $personal -> personalHoy($_POST['fecha'], '%');

              //recorremos cada una de las actividades.
              foreach ($actividadesHoy as $act) {
                //sacamos el numero de recursos que deberia de tener la actividad.
                $recurso = $recursos -> ModificacionId($act['id'], $_POST['fecha']);
                if ($recurso == null || $recurso == false) {
                  $recurso = $recursos -> RecursosId($act['id']);
                }
                //sacamos el nombre de la actividad
                echo "<div class='formthird'>";
                //sacar los nombres asignados a esa actividad para ese dia.
                $asignados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro6');
                if ($asignados != null && $asignados != false) {
                  echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro6']." </label></p>";
                  $recursosRaros = $recursosRaros + $recurso['otro6'];
                  echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio6']." HASTA ".$recurso['fin6']."</label></p>";
                  echo "<input type='hidden' name='otro6".$i."[]' value='".$act['id']."'>";
                  //sacamos tantos selects como personas asignadas habia para ese dia.
                  foreach ($asignados as $persona) {
                    echo "<p><select name='selectO6".$i."[]' class='test' id='multiple'>";
                    echo "<option value='NO DISPONIBLE'>NO DISPONIBLE</option>";
                    if ($persona['empleado'] == '') {
                      echo "<option value='' selected>SIN ASIGNAR</option>";
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

                  if ($recurso['otro6']>0) {
                    echo "<p><label><i class='fa fa-question-circle'></i>".$act['descripcion']." - ".$recurso['otro6']." </label></p>";
                    $recursosRaros = $recursosRaros + $recurso['otro6'];
                    echo "<p><label><i class='fa fa-question-circle'></i>DE ".$recurso['inicio6']." HASTA ".$recurso['fin6']."</label></p>";
                    echo "<input type='hidden' name='otro6".$i."[]' value='".$act['id']."'>";

                    //si tenia recursos asignados, sacamos el select con la opcion SIN ASIGNAR seleccionada
                    for ($r=0; $r < $recurso['otro6'] ; $r++) {
                      echo "<p><select name='selectO6".$i."[]' class='test' id='multiple'>";
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

                echo "<input type='hidden' name='otro6' value='".$i."'>";

              echo "</div>";
              }
             ?>
             <?php
              echo "<input type='hidden' name='totalNormales' value='".$recursosNormales."'>";
              echo "<input type='hidden' name='totalRaros' value='".$recursosRaros."'>";
              ?>
      <div class="formthird" style="width: 100%; margin-top: 5%;">
        <textarea name="comentario" rows="8" cols="900" style="width: 100%;" placeholder="Explicaciones relativas a cambios de personal entre actividades."></textarea>
      </div>
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
