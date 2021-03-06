<style media="screen">
  body{
    color:black;
  }
</style>
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
  <title><?php echo __('Nueva actividad', $lang); ?></title>
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
        <a href="../index.php"><?php echo __('Inicio', $lang); ?></a>
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
            echo '<a href="filtroRRHH.php">Selección personal</a>';

          }elseif ($opcion == 23) {
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';

          }elseif ($opcion == 0) {
            echo '<a href="../operativa/nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='../operativa/actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='../operativa/historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='../operativa/resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='../operativa/nuevoCliente.php'>Nuevo cliente/resp.</a>";
            echo '<a href="filtroRRHH.php">Selección personal</a>';
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';
          }
        }
         ?>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <?php
          //transformamos la fecha
          $fechaMostrar=explode("-", $_GET['fecha']);
          $fechaMostrar=$fechaMostrar[2]."-".$fechaMostrar[1]."-".$fechaMostrar[0];
          //sacamos el nombre del servicio para ponerlo de titulo
          $nombre = $servicio -> ServicioId($_GET['id']);
          echo "<h2>".$nombre['descripcion']." </h2><h3> ".$fechaMostrar."</h3>";

         ?>
        <form action="guardarPersonal.php" method="post" id="formulario" enctype="multipart/form-data">
          <?php
            //ponemos la fecha y el servicio en un input hidden pra pasarlos a la siguiente pantalla
            echo "<input type='hidden' name='id' value='".$_GET['id']."'>";
            echo "<input type='hidden' name='dia' value='".$_GET['fecha']."'>";
           ?>
          <?php
            //sacar los recursos para ese dia y ese servicio
            $recurso = $recursos -> ModificacionId($_GET['id'], $_GET['fecha']);
            if ($recurso == null || $recurso == false) {
              $recurso = $recursos -> RecursosId($_GET['id']);
            }
            //sacar los nombres de todos los empleados de la empresa.
            $empleados = $empleado -> listaEmpleadosActivos();
           ?>
           <?php
           //sacamos la fecha del dia anterior para sacar las personas asignadas
           //restar un dia a la fecha elegida
           $nuevafecha = strtotime ( '-1 day' , strtotime ( $_GET['fecha'] ) ) ;
           $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
           //TURNO NOCHE.
           //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
            $personalNoche = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'tn');
            //comprobar si hay alguien asignado.
            if ($personalNoche != null && $personalNoche != false) {
              //sacar el titulo de mañana
              ?>
              <div class="formthird">
                <p><label><i class="fa fa-question-circle"></i>Noche</label></p>
              <?php
              //por cada persona asignada sacaremos un select con el listado de trabajadores
                foreach ($personalNoche as $personalN) {
                  echo "<p><select name='noche[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                  if ($personalN['empleado']=='') {
                    echo "<option value='' selected>Sin asignar</option>";
                  }else {
                    echo "<option value=''>Sin asignar</option>";
                  }
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                    //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                    if ($personalN['empleado'] == $nombre) {
                      echo "<option value='".$nombre."' selected>".$nombre."</option>";
                    }else {
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  }
                  //cerrar el select.
                  echo "</select></p>";
                  $recurso['tn']=$recurso['tn']-1;
                }
                if ($recurso['tn']>0) {
                  for ($i=0; $i < $recurso['tn'] ; $i++) {
                    echo "<p><select name='noche[]' class='test' id='multiple'>";
                    //si la persona estaba sin asignar, ponemos por defecto esta opcion
                      echo "<option value='' selected>Sin asignar</option>";
                    //sacar a los trabajadores con un foreach
                    foreach ($empleados as $trabajador) {
                      //guardar el nombre del trabajador en una variable para poder compararlo
                      if ($trabajador['ett'] == 1) {
                        $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                      }else {
                        $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                      }
                        echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                  echo "</select></p>";
                }
              }
              ?>
                </div>
              <?php
            }else {
              if ($recurso['tn']>0) {
                ?>
                <div class="formthird">
                  <p><label><i class="fa fa-question-circle"></i>Noche</label></p>
                <?php
                for ($i=0; $i < $recurso['tn'] ; $i++) {
                  echo "<p><select name='noche[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                    echo "<option value='' selected>Sin asignar</option>";
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                  echo "</select></p>";
                }
              }
            ?>
          </div>
            <?php
            }
            ?>
      <?php
        //TURNO MAÑANA
        //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
         $personalMorning = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'tm');
         //comprobar si hay alguien asignado.
         if ($personalMorning != null && $personalMorning != false) {
           //sacar el titulo de mañana
           ?>
           <div class="formthird">
             <p><label><i class="fa fa-question-circle"></i>Mañana</label></p>
           <?php
           //por cada persona asignada sacaremos un select con el listado de trabajadores
             foreach ($personalMorning as $personalN) {
               echo "<p><select name='morning[]' class='test' id='multiple'>";
               //si la persona estaba sin asignar, ponemos por defecto esta opcion
               if ($personalN['empleado']=='') {
                 echo "<option value='' selected>Sin asignar</option>";
               }else {
                 echo "<option value=''>Sin asignar</option>";
               }
               //sacar a los trabajadores con un foreach
               foreach ($empleados as $trabajador) {
                 //guardar el nombre del trabajador en una variable para poder compararlo
                 if ($trabajador['ett'] == 1) {
                   $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                 }else {
                   $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                 }
                 //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                 if ($personalN['empleado'] == $nombre) {
                   echo "<option value='".$nombre."' selected>".$nombre."</option>";
                 }else {
                   echo "<option value='".$nombre."'>".$nombre."</option>";
                 }
               }
               //cerrar el select.
               echo "</select></p>";
               $recurso['tm']=$recurso['tm']-1;
             }
             if ($recurso['tm']>0) {
               for ($i=0; $i < $recurso['tm'] ; $i++) {
                 echo "<p><select name='morning[]' class='test' id='multiple'>";
                 //si la persona estaba sin asignar, ponemos por defecto esta opcion
                   echo "<option value='' selected>Sin asignar</option>";
                 //sacar a los trabajadores con un foreach
                 foreach ($empleados as $trabajador) {
                   //guardar el nombre del trabajador en una variable para poder compararlo
                   if ($trabajador['ett'] == 1) {
                     $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                   }else {
                     $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                   }
                     echo "<option value='".$nombre."'>".$nombre."</option>";
               }
               echo "</select></p>";
             }
           }
           ?>
             </div>
           <?php
         }else {
           if ($recurso['tm']>0) {
             ?>
             <div class="formthird">
               <p><label><i class="fa fa-question-circle"></i>Mañana</label></p>
             <?php
             for ($i=0; $i < $recurso['tm'] ; $i++) {
               echo "<p><select name='morning[]' class='test' id='multiple'>";
               //si la persona estaba sin asignar, ponemos por defecto esta opcion
                 echo "<option value='' selected>Sin asignar</option>";
               //sacar a los trabajadores con un foreach
               foreach ($empleados as $trabajador) {
                 //guardar el nombre del trabajador en una variable para poder compararlo
                 if ($trabajador['ett'] == 1) {
                   $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                 }else {
                   $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                 }
                   echo "<option value='".$nombre."'>".$nombre."</option>";
               }
               echo "</select></p>";
             }
           }
         ?>
       </div>
         <?php
         }
         ?>

         <?php
           //TURNO TARDE
           //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
            $personalTarde = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'tt');
            //comprobar si hay alguien asignado.
            if ($personalTarde != null && $personalTarde != false) {
              //sacar el titulo de mañana
              ?>
              <div class="formthird">
                <p><label><i class="fa fa-question-circle"></i>Tarde</label></p>
              <?php
              //por cada persona asignada sacaremos un select con el listado de trabajadores
                foreach ($personalTarde as $personalN) {
                  echo "<p><select name='tarde[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                  if ($personalN['empleado']=='') {
                    echo "<option value='' selected>Sin asignar</option>";
                  }else {
                    echo "<option value=''>Sin asignar</option>";
                  }
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                    //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                    if ($personalN['empleado'] == $nombre) {
                      echo "<option value='".$nombre."' selected>".$nombre."</option>";
                    }else {
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  }
                  //cerrar el select.
                  echo "</select></p>";
                  $recurso['tt']=$recurso['tt']-1;
                }
                if ($recurso['tt']>0) {
                  for ($i=0; $i < $recurso['tt'] ; $i++) {
                    echo "<p><select name='tarde[]' class='test' id='multiple'>";
                    //si la persona estaba sin asignar, ponemos por defecto esta opcion
                      echo "<option value='' selected>Sin asignar</option>";
                    //sacar a los trabajadores con un foreach
                    foreach ($empleados as $trabajador) {
                      //guardar el nombre del trabajador en una variable para poder compararlo
                      if ($trabajador['ett'] == 1) {
                        $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                      }else {
                        $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                      }
                        echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                  echo "</select></p>";
                }
              }
              ?>
                </div>
              <?php
            }else {
              if ($recurso['tt']>0) {
                ?>
                <div class="formthird">
                  <p><label><i class="fa fa-question-circle"></i>Tarde</label></p>
                <?php
                for ($i=0; $i < $recurso['tt'] ; $i++) {
                  echo "<p><select name='tarde[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                    echo "<option value='' selected>Sin asignar</option>";
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                  echo "</select></p>";
                }
              }
            ?>
          </div>
            <?php
            }
            ?>

    <?php
      //TURNO CENTRAL
      //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
       $personalCentral = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'tc');
       //comprobar si hay alguien asignado.
       if ($personalCentral != null && $personalCentral != false) {
         //sacar el titulo de mañana
         ?>
         <div class="formthird">
           <p><label><i class="fa fa-question-circle"></i>Central</label></p>
         <?php
         //por cada persona asignada sacaremos un select con el listado de trabajadores
           foreach ($personalCentral as $personalN) {
             echo "<p><select name='central[]' class='test' id='multiple'>";
             //si la persona estaba sin asignar, ponemos por defecto esta opcion
             if ($personalN['empleado']=='') {
               echo "<option value='' selected>Sin asignar</option>";
             }else {
               echo "<option value=''>Sin asignar</option>";
             }
             //sacar a los trabajadores con un foreach
             foreach ($empleados as $trabajador) {
               //guardar el nombre del trabajador en una variable para poder compararlo
               if ($trabajador['ett'] == 1) {
                 $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
               }else {
                 $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
               }
               //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
               if ($personalN['empleado'] == $nombre) {
                 echo "<option value='".$nombre."' selected>".$nombre."</option>";
               }else {
                 echo "<option value='".$nombre."'>".$nombre."</option>";
               }
             }
             //cerrar el select.
             echo "</select></p>";
             $recurso['tc']=$recurso['tc']-1;
           }
           if ($recurso['tc']>0) {
             for ($i=0; $i < $recurso['tc'] ; $i++) {
               echo "<p><select name='central[]' class='test' id='multiple'>";
               //si la persona estaba sin asignar, ponemos por defecto esta opcion
                 echo "<option value='' selected>Sin asignar</option>";
               //sacar a los trabajadores con un foreach
               foreach ($empleados as $trabajador) {
                 //guardar el nombre del trabajador en una variable para poder compararlo
                 if ($trabajador['ett'] == 1) {
                   $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                 }else {
                   $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                 }
                   echo "<option value='".$nombre."'>".$nombre."</option>";
             }
             echo "</select></p>";
           }
         }
         ?>
           </div>
         <?php
       }else {
         if ($recurso['tc']>0) {
           ?>
           <div class="formthird">
             <p><label><i class="fa fa-question-circle"></i>Central</label></p>
           <?php
           for ($i=0; $i < $recurso['tc'] ; $i++) {
             echo "<p><select name='central[]' class='test' id='multiple'>";
             //si la persona estaba sin asignar, ponemos por defecto esta opcion
               echo "<option value='' selected>Sin asignar</option>";
             //sacar a los trabajadores con un foreach
             foreach ($empleados as $trabajador) {
               //guardar el nombre del trabajador en una variable para poder compararlo
               if ($trabajador['ett'] == 1) {
                 $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
               }else {
                 $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
               }
                 echo "<option value='".$nombre."'>".$nombre."</option>";
             }
             echo "</select></p>";
           }
         }
       ?>
     </div>
       <?php
       }
       ?>

     <?php
       //TURNO ESPECIAL 1
       //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
        $personal1 = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'otro1');
        //comprobar si hay alguien asignado.
        if ($personal1 != null && $personal1 != false) {
          //sacar el titulo de mañana
          ?>
          <div class="formthird">
          <?php
          echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio1']." a ".$recurso['fin1']."</i></label></p>";
          //por cada persona asignada sacaremos un select con el listado de trabajadores
            foreach ($personal1 as $personalN) {
              echo "<p><select name='otro1[]' class='test' id='multiple'>";
              //si la persona estaba sin asignar, ponemos por defecto esta opcion
              if ($personalN['empleado']=='') {
                echo "<option value='' selected>Sin asignar</option>";
              }else {
                echo "<option value=''>Sin asignar</option>";
              }
              //sacar a los trabajadores con un foreach
              foreach ($empleados as $trabajador) {
                //guardar el nombre del trabajador en una variable para poder compararlo
                if ($trabajador['ett'] == 1) {
                  $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                }else {
                  $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                }
                //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                if ($personalN['empleado'] == $nombre) {
                  echo "<option value='".$nombre."' selected>".$nombre."</option>";
                }else {
                  echo "<option value='".$nombre."'>".$nombre."</option>";
                }
              }
              //cerrar el select.
              echo "</select></p>";
              $recurso['otro1']=$recurso['otro1']-1;
            }
            if ($recurso['otro1']>0) {
              for ($i=0; $i < $recurso['otro1'] ; $i++) {
                echo "<p><select name='otro1[]' class='test' id='multiple'>";
                //si la persona estaba sin asignar, ponemos por defecto esta opcion
                  echo "<option value='' selected>Sin asignar</option>";
                //sacar a los trabajadores con un foreach
                foreach ($empleados as $trabajador) {
                  //guardar el nombre del trabajador en una variable para poder compararlo
                  if ($trabajador['ett'] == 1) {
                    $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                  }else {
                    $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                  }
                    echo "<option value='".$nombre."'>".$nombre."</option>";
              }
              echo "</select></p>";
            }
          }
          ?>
            </div>
          <?php
        }else {
          if ($recurso['otro1']>0) {
            ?>
            <div class="formthird">
            <?php
            echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio1']." a ".$recurso['fin1']."</i></label></p>";
            for ($i=0; $i < $recurso['otro1'] ; $i++) {
              echo "<p><select name='otro1[]' class='test' id='multiple'>";
              //si la persona estaba sin asignar, ponemos por defecto esta opcion
                echo "<option value='' selected>Sin asignar</option>";
              //sacar a los trabajadores con un foreach
              foreach ($empleados as $trabajador) {
                //guardar el nombre del trabajador en una variable para poder compararlo
                if ($trabajador['ett'] == 1) {
                  $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                }else {
                  $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                }
                  echo "<option value='".$nombre."'>".$nombre."</option>";
              }
              echo "</select></p>";
            }
          }
        ?>
      </div>
        <?php
        }
        ?>

  <?php
  //TURNO ESPECIAL 2
  //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
   $personal2 = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'otro2');
   //comprobar si hay alguien asignado.
   if ($personal2 != null && $personal2 != false) {
     //sacar el titulo de mañana
     ?>
     <div class="formthird">
     <?php
     echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio2']." a ".$recurso['fin2']."</i></label></p>";
     //por cada persona asignada sacaremos un select con el listado de trabajadores
       foreach ($personal2 as $personalN) {
         echo "<p><select name='otro2[]' class='test' id='multiple'>";
         //si la persona estaba sin asignar, ponemos por defecto esta opcion
         if ($personalN['empleado']=='') {
           echo "<option value='' selected>Sin asignar</option>";
         }else {
           echo "<option value=''>Sin asignar</option>";
         }
         //sacar a los trabajadores con un foreach
         foreach ($empleados as $trabajador) {
           //guardar el nombre del trabajador en una variable para poder compararlo
           if ($trabajador['ett'] == 1) {
             $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
           }else {
             $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
           }
           //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
           if ($personalN['empleado'] == $nombre) {
             echo "<option value='".$nombre."' selected>".$nombre."</option>";
           }else {
             echo "<option value='".$nombre."'>".$nombre."</option>";
           }
         }
         //cerrar el select.
         echo "</select></p>";
         $recurso['otro2']=$recurso['otro2']-1;
       }
       if ($recurso['otro2']>0) {
         for ($i=0; $i < $recurso['otro2'] ; $i++) {
           echo "<p><select name='otro2[]' class='test' id='multiple'>";
           //si la persona estaba sin asignar, ponemos por defecto esta opcion
             echo "<option value='' selected>Sin asignar</option>";
           //sacar a los trabajadores con un foreach
           foreach ($empleados as $trabajador) {
             //guardar el nombre del trabajador en una variable para poder compararlo
             if ($trabajador['ett'] == 1) {
               $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
             }else {
               $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
             }
               echo "<option value='".$nombre."'>".$nombre."</option>";
         }
         echo "</select></p>";
       }
     }
     ?>
       </div>
     <?php
   }else {
     if ($recurso['otro2']>0) {
       ?>
       <div class="formthird">
       <?php
       echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio2']." a ".$recurso['fin2']."</i></label></p>";
       for ($i=0; $i < $recurso['otro2'] ; $i++) {
         echo "<p><select name='otro2[]' class='test' id='multiple'>";
         //si la persona estaba sin asignar, ponemos por defecto esta opcion
           echo "<option value='' selected>Sin asignar</option>";
         //sacar a los trabajadores con un foreach
         foreach ($empleados as $trabajador) {
           //guardar el nombre del trabajador en una variable para poder compararlo
           if ($trabajador['ett'] == 1) {
             $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
           }else {
             $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
           }
             echo "<option value='".$nombre."'>".$nombre."</option>";
         }
         echo "</select></p>";
       }
     }
   ?>
 </div>
   <?php
   }
   ?>

   <?php
     //TURNO ESPECIAL 3
     //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
      $personal3 = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'otro3');
      //comprobar si hay alguien asignado.
      if ($personal3 != null && $personal3 != false) {
        //sacar el titulo de mañana
        ?>
        <div class="formthird">
        <?php
        echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio3']." a ".$recurso['fin3']."</i></label></p>";
        //por cada persona asignada sacaremos un select con el listado de trabajadores
          foreach ($personal3 as $personalN) {
            echo "<p><select name='otro3[]' class='test' id='multiple'>";
            //si la persona estaba sin asignar, ponemos por defecto esta opcion
            if ($personalN['empleado']=='') {
              echo "<option value='' selected>Sin asignar</option>";
            }else {
              echo "<option value=''>Sin asignar</option>";
            }
            //sacar a los trabajadores con un foreach
            foreach ($empleados as $trabajador) {
              //guardar el nombre del trabajador en una variable para poder compararlo
              if ($trabajador['ett'] == 1) {
                $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
              }else {
                $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
              }
              //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
              if ($personalN['empleado'] == $nombre) {
                echo "<option value='".$nombre."' selected>".$nombre."</option>";
              }else {
                echo "<option value='".$nombre."'>".$nombre."</option>";
              }
            }
            //cerrar el select.
            echo "</select></p>";
            $recurso['otro3']=$recurso['otro3']-1;
          }
          if ($recurso['otro3']>0) {
            for ($i=0; $i < $recurso['otro3'] ; $i++) {
              echo "<p><select name='otro3[]' class='test' id='multiple'>";
              //si la persona estaba sin asignar, ponemos por defecto esta opcion
                echo "<option value='' selected>Sin asignar</option>";
              //sacar a los trabajadores con un foreach
              foreach ($empleados as $trabajador) {
                //guardar el nombre del trabajador en una variable para poder compararlo
                if ($trabajador['ett'] == 1) {
                  $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                }else {
                  $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                }
                  echo "<option value='".$nombre."'>".$nombre."</option>";
            }
            echo "</select></p>";
          }
        }
        ?>
          </div>
        <?php
      }else {
        if ($recurso['otro3']>0) {
          ?>
          <div class="formthird">
          <?php
          echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio3']." a ".$recurso['fin3']."</i></label></p>";
          for ($i=0; $i < $recurso['otro3'] ; $i++) {
            echo "<p><select name='otro3[]' class='test' id='multiple'>";
            //si la persona estaba sin asignar, ponemos por defecto esta opcion
              echo "<option value='' selected>Sin asignar</option>";
            //sacar a los trabajadores con un foreach
            foreach ($empleados as $trabajador) {
              //guardar el nombre del trabajador en una variable para poder compararlo
              if ($trabajador['ett'] == 1) {
                $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
              }else {
                $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
              }
                echo "<option value='".$nombre."'>".$nombre."</option>";
            }
            echo "</select></p>";
          }
        }
      ?>
    </div>
      <?php
      }
      ?>

      <?php
        //TURNO ESPECIAL 4
        //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
         $personal4 = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'otro4');
         //comprobar si hay alguien asignado.
         if ($personal4 != null && $personal4 != false) {
           //sacar el titulo de mañana
           ?>
           <div class="formthird">
           <?php
           echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio4']." a ".$recurso['fin4']."</i></label></p>";
           //por cada persona asignada sacaremos un select con el listado de trabajadores
             foreach ($personal4 as $personalN) {
               echo "<p><select name='otro4[]' class='test' id='multiple'>";
               //si la persona estaba sin asignar, ponemos por defecto esta opcion
               if ($personalN['empleado']=='') {
                 echo "<option value='' selected>Sin asignar</option>";
               }else {
                 echo "<option value=''>Sin asignar</option>";
               }
               //sacar a los trabajadores con un foreach
               foreach ($empleados as $trabajador) {
                 //guardar el nombre del trabajador en una variable para poder compararlo
                 if ($trabajador['ett'] == 1) {
                   $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                 }else {
                   $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                 }
                 //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                 if ($personalN['empleado'] == $nombre) {
                   echo "<option value='".$nombre."' selected>".$nombre."</option>";
                 }else {
                   echo "<option value='".$nombre."'>".$nombre."</option>";
                 }
               }
               //cerrar el select.
               echo "</select></p>";
               $recurso['otro4']=$recurso['otro4']-1;
             }
             if ($recurso['otro4']>0) {
               for ($i=0; $i < $recurso['otro4'] ; $i++) {
                 echo "<p><select name='otro4[]' class='test' id='multiple'>";
                 //si la persona estaba sin asignar, ponemos por defecto esta opcion
                   echo "<option value='' selected>Sin asignar</option>";
                 //sacar a los trabajadores con un foreach
                 foreach ($empleados as $trabajador) {
                   //guardar el nombre del trabajador en una variable para poder compararlo
                   if ($trabajador['ett'] == 1) {
                     $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                   }else {
                     $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                   }
                     echo "<option value='".$nombre."'>".$nombre."</option>";
               }
               echo "</select></p>";
             }
           }
           ?>
             </div>
           <?php
         }else {
           if ($recurso['otro4']>0) {
             ?>
             <div class="formthird">
             <?php
             echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio4']." a ".$recurso['fin4']."</i></label></p>";
             for ($i=0; $i < $recurso['otro4'] ; $i++) {
               echo "<p><select name='otro4[]' class='test' id='multiple'>";
               //si la persona estaba sin asignar, ponemos por defecto esta opcion
                 echo "<option value='' selected>Sin asignar</option>";
               //sacar a los trabajadores con un foreach
               foreach ($empleados as $trabajador) {
                 //guardar el nombre del trabajador en una variable para poder compararlo
                 if ($trabajador['ett'] == 1) {
                   $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                 }else {
                   $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                 }
                   echo "<option value='".$nombre."'>".$nombre."</option>";
               }
               echo "</select></p>";
             }
           }
         ?>
       </div>
         <?php
         }
         ?>

         <?php
           //TURNO ESPECIAL 5
           //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
            $personal5 = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'otro5');
            //comprobar si hay alguien asignado.
            if ($personal5 != null && $personal5 != false) {
              //sacar el titulo de mañana
              ?>
              <div class="formthird">
              <?php
              echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio5']." a ".$recurso['fin5']."</i></label></p>";
              //por cada persona asignada sacaremos un select con el listado de trabajadores
                foreach ($personal5 as $personalN) {
                  echo "<p><select name='otro5[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                  if ($personalN['empleado']=='') {
                    echo "<option value='' selected>Sin asignar</option>";
                  }else {
                    echo "<option value=''>Sin asignar</option>";
                  }
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                    //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                    if ($personalN['empleado'] == $nombre) {
                      echo "<option value='".$nombre."' selected>".$nombre."</option>";
                    }else {
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  }
                  //cerrar el select.
                  echo "</select></p>";
                  $recurso['otro5']=$recurso['otro5']-1;
                }
                if ($recurso['otro5']>0) {
                  for ($i=0; $i < $recurso['otro5'] ; $i++) {
                    echo "<p><select name='otro5[]' class='test' id='multiple'>";
                    //si la persona estaba sin asignar, ponemos por defecto esta opcion
                      echo "<option value='' selected>Sin asignar</option>";
                    //sacar a los trabajadores con un foreach
                    foreach ($empleados as $trabajador) {
                      //guardar el nombre del trabajador en una variable para poder compararlo
                      if ($trabajador['ett'] == 1) {
                        $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                      }else {
                        $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                      }
                        echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                  echo "</select></p>";
                }
              }
              ?>
                </div>
              <?php
            }else {
              if ($recurso['otro5']>0) {
                ?>
                <div class="formthird">
                <?php
                echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio5']." a ".$recurso['fin5']."</i></label></p>";
                for ($i=0; $i < $recurso['otro5'] ; $i++) {
                  echo "<p><select name='otro5[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                    echo "<option value='' selected>Sin asignar</option>";
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                  echo "</select></p>";
                }
              }
            ?>
          </div>
            <?php
            }
            ?>

            <?php
              //TURNO ESPECIAL 6
              //buscar el personal que hay asignado para ese dia y ese servicio en el turno de mañana
               $personal6 = $personal -> empleadosServicio($_GET['id'], $nuevafecha, 'otro6');
               //comprobar si hay alguien asignado.
               if ($personal6 != null && $personal6 != false) {
                 //sacar el titulo de mañana
                 ?>
                 <div class="formthird">
                 <?php
                 echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio6']." a ".$recurso['fin6']."</i></label></p>";
                 //por cada persona asignada sacaremos un select con el listado de trabajadores
                   foreach ($personal6 as $personalN) {
                     echo "<p><select name='otro6[]' class='test' id='multiple'>";
                     //si la persona estaba sin asignar, ponemos por defecto esta opcion
                     if ($personalN['empleado']=='') {
                       echo "<option value='' selected>Sin asignar</option>";
                     }else {
                       echo "<option value=''>Sin asignar</option>";
                     }
                     //sacar a los trabajadores con un foreach
                     foreach ($empleados as $trabajador) {
                       //guardar el nombre del trabajador en una variable para poder compararlo
                       if ($trabajador['ett'] == 1) {
                         $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                       }else {
                         $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                       }
                       //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                       if ($personalN['empleado'] == $nombre) {
                         echo "<option value='".$nombre."' selected>".$nombre."</option>";
                       }else {
                         echo "<option value='".$nombre."'>".$nombre."</option>";
                       }
                     }
                     //cerrar el select.
                     echo "</select></p>";
                     $recurso['otro6']=$recurso['otro6']-1;
                   }
                   if ($recurso['otro6']>0) {
                     for ($i=0; $i < $recurso['otro6'] ; $i++) {
                       echo "<p><select name='otro6[]' class='test' id='multiple'>";
                       //si la persona estaba sin asignar, ponemos por defecto esta opcion
                         echo "<option value='' selected>Sin asignar</option>";
                       //sacar a los trabajadores con un foreach
                       foreach ($empleados as $trabajador) {
                         //guardar el nombre del trabajador en una variable para poder compararlo
                         if ($trabajador['ett'] == 1) {
                           $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                         }else {
                           $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                         }
                           echo "<option value='".$nombre."'>".$nombre."</option>";
                     }
                     echo "</select></p>";
                   }
                 }
                 ?>
                   </div>
                 <?php
               }else {
                 if ($recurso['otro6']>0) {
                   ?>
                   <div class="formthird">
                   <?php
                   echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio6']." a ".$recurso['fin6']."</i></label></p>";
                   for ($i=0; $i < $recurso['otro6'] ; $i++) {
                     echo "<p><select name='otro6[]' class='test' id='multiple'>";
                     //si la persona estaba sin asignar, ponemos por defecto esta opcion
                       echo "<option value='' selected>Sin asignar</option>";
                     //sacar a los trabajadores con un foreach
                     foreach ($empleados as $trabajador) {
                       //guardar el nombre del trabajador en una variable para poder compararlo
                       if ($trabajador['ett'] == 1) {
                         $nombre ="ETT-". $trabajador['nombre']." ".$trabajador['apellidos'];
                       }else {
                         $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                       }
                         echo "<option value='".$nombre."'>".$nombre."</option>";
                     }
                     echo "</select></p>";
                   }
                 }
               ?>
             </div>
               <?php
               }
               ?>

           <div class="formthird" style="width: 100%; margin-top: 5%;">
             <textarea name="comentario" rows="8" cols="900" style="width: 100%;" placeholder="Comentario para jefes de turno."></textarea>
           </div>
          <div class="submitbuttons">
              <input class="submithree" style="width: 25%; margin-right: 15%; margin-left: 5%;" type="submit" value="<?php echo __('Enviar', $lang); ?>"/>
              <?php
                echo "<button class='submitwo' type='button' name='button' onclick=window.location='tablaRRHH.php?fecha=".$_GET['fecha']."'>Atras</button>";
               ?>
          </div>
  </form>

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
