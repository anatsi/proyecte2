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
require_once './bbdd/servicio.php';
require_once './bbdd/recursos.php';
require_once './bbdd/empleados.php';

$usuario=new User();
$sesion=new Sesiones();
$servicio=new Servicio();
$recursos=new Recursos();
$empleado = new Empleados();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo __('Nueva actividad', $lang); ?></title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <script type="text/javascript" src="../js/servicioForm.js"></script>
    <link href="../css/fSelect.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/fSelect.js"></script>
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
          if ($opcion == 21) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
          }elseif ($opcion == 22) {
            echo '<a href="filtroRRHH.php">Selección personal</a>';

          }elseif ($opcion == 23) {
            echo '<a href="filtroSupervisores.php">Supervisores</a>';

          }elseif ($opcion == 0) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
            echo '<a href="filtroRRHH.php">Selección personal</a>';
            echo '<a href="filtroSupervisores.php">Supervisores</a>';
          }
        }
         ?>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <?php
          //sacamos el nombre del servicio para ponerlo de titulo
          $nombre = $servicio -> ServicioId($_GET['id']);
          echo "<h2>".$nombre['descripcion']."</h2>";
         ?>
        <form action="guardarPersonal.php" method="post" id="formulario" enctype="multipart/form-data">
          <?php
            //ponemos la fecha y el servicio en un input hidden pra pasarlos a la siguiente pantalla
            echo "<input type='hidden' name='id' value='".$_GET['id']."'>";
            echo "<input type='hidden' name='dia' value='".$_GET['fecha']."'>";
           ?>
          <?php
            //sacar los recursos para ese dia y ese servicio-
            $recurso = $recursos -> ModificacionId($_GET['id'], $_GET['fecha']);
            if ($recurso == null || $recurso == false) {
              $recurso = $recursos -> RecursosId($_GET['id']);
            }
            //sacar los nombres de todos los empleados de la empresa.
            $empleados = $empleado -> listaEmpleados();
           ?>
           <?php
            if ($recurso['tn']>0) {
              ?>
              <div class="formthird">
                <p><label><i class="fa fa-question-circle"></i>Noche</label></p>
              <?php
              for ($i=0; $i < $recurso['tn']; $i++) {
                echo "<p><select name='noche[]' class='test' id='multiple'>";
                echo "<option value='' selected>Sin asignar</option>";
                  foreach ($empleados as $trabajador) {
                    echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                  }
                echo "</select></p>";
              }
              ?>
                </div>
              <?php
            }
            ?>
            <?php
             if ($recurso['tm']>0) {
               ?>
               <div class="formthird">
                 <p><label><i class="fa fa-question-circle"></i>Mañana</label></p>
               <?php
               for ($i=0; $i < $recurso['tm']; $i++) {
                 echo "<p><select name='morning[]' class='test' id='multiple'>";
                 echo "<option value='' selected>Sin asignar</option>";
                   foreach ($empleados as $trabajador) {
                     echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                   }
                 echo "</select></p>";
               }
               ?>
                 </div>
               <?php
             }
             ?>
             <?php
              if ($recurso['tt']>0) {
                ?>
                <div class="formthird">
                  <p><label><i class="fa fa-question-circle"></i>Tarde</label></p>
                <?php
                for ($i=0; $i < $recurso['tt']; $i++) {
                  echo "<p><select name='tarde[]' class='test' id='multiple'>";
                  echo "<option value='' selected>Sin asignar</option>";
                    foreach ($empleados as $trabajador) {
                      echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                    }
                  echo "</select></p>";
                }
                ?>
                  </div>
                <?php
              }
              ?>
              <?php
               if ($recurso['tc']>0) {
                 ?>
                 <div class="formthird">
                   <p><label><i class="fa fa-question-circle"></i>Central</label></p>
                 <?php
                 for ($i=0; $i < $recurso['tc']; $i++) {
                   echo "<p><select name='central[]' class='test' id='multiple'>";
                   echo "<option value='' selected>Sin asignar</option>";
                     foreach ($empleados as $trabajador) {
                       echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                     }
                   echo "</select></p>";
                 }
                 ?>
                   </div>
                 <?php
               }
               ?>
               <?php
                if ($recurso['otro1']>0) {
                  ?>
                  <div class="formthird">
                  <?php
                  echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio1']." a ".$recurso['fin1']."</i></label></p>";
                  for ($i=0; $i < $recurso['otro1']; $i++) {
                    echo "<p><select name='otro1[]' class='test' id='multiple'>";
                    echo "<option value='' selected>Sin asignar</option>";
                      foreach ($empleados as $trabajador) {
                        echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                      }
                    echo "</select></p>";
                  }
                  ?>
                    </div>
                  <?php
                }
                ?>
                <?php
                 if ($recurso['otro2']>0) {
                   ?>
                   <div class="formthird">
                   <?php
                   echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio2']." a ".$recurso['fin2']."</i></label></p>";
                   for ($i=0; $i < $recurso['otro2']; $i++) {
                     echo "<p><select name='otro2[]' class='test' id='multiple'>";
                     echo "<option value='' selected>Sin asignar</option>";
                       foreach ($empleados as $trabajador) {
                         echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                       }
                     echo "</select></p>";
                   }
                   ?>
                     </div>
                   <?php
                 }
                 ?>
                 <?php
                  if ($recurso['otro3']>0) {
                    ?>
                    <div class="formthird">
                    <?php
                    echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio3']." a ".$recurso['fin3']."</i></label></p>";
                    for ($i=0; $i < $recurso['otro3']; $i++) {
                      echo "<p><select name='otro3[]' class='test' id='multiple'>";
                      echo "<option value='' selected>Sin asignar</option>";
                        foreach ($empleados as $trabajador) {
                          echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                        }
                      echo "</select></p>";
                    }
                    ?>
                      </div>
                    <?php
                  }
                  ?>
                  <?php
                   if ($recurso['otro4']>0) {
                     ?>
                     <div class="formthird">
                     <?php
                     echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio4']." a ".$recurso['fin4']."</i></label></p>";
                     for ($i=0; $i < $recurso['otro4']; $i++) {
                       echo "<p><select name='otro4[]' class='test' id='multiple'>";
                       echo "<option value='' selected>Sin asignar</option>";
                         foreach ($empleados as $trabajador) {
                           echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                         }
                       echo "</select></p>";
                     }
                     ?>
                       </div>
                     <?php
                   }
                   ?>
                   <?php
                    if ($recurso['otro5']>0) {
                      ?>
                      <div class="formthird">
                      <?php
                      echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio5']." a ".$recurso['fin5']."</i></label></p>";
                      for ($i=0; $i < $recurso['otro5']; $i++) {
                        echo "<p><select name='otro5[]' class='test' id='multiple'>";
                        echo "<option value='' selected>Sin asignar</option>";
                          foreach ($empleados as $trabajador) {
                            echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                          }
                        echo "</select></p>";
                      }
                      ?>
                        </div>
                      <?php
                    }
                    ?>
                    <?php
                     if ($recurso['otro6']>0) {
                       ?>
                       <div class="formthird">
                       <?php
                       echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio6']." a ".$recurso['fin6']."</i></label></p>";
                       for ($i=0; $i < $recurso['otro6']; $i++) {
                         echo "<p><select name='otro6[]' class='test' id='multiple'>";
                         echo "<option value='' selected>Sin asignar</option>";
                           foreach ($empleados as $trabajador) {
                             echo "<option value='".$trabajador['nombre']." ".$trabajador['apellidos']."'>".$trabajador['nombre']." ".$trabajador['apellidos']."</option>";
                           }
                         echo "</select></p>";
                       }
                       ?>
                         </div>
                       <?php
                     }
                     ?>
          <div class="submitbuttons">
              <input class="submitone" type="submit" value="<?php echo __('Enviar', $lang); ?>"/>
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