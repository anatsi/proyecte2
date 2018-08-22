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
require '../bbdd/personal.php';

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
    <style media="screen">
      body{
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
        <!-- Contenido de la pagina. -->
        <?php
          //transformamos la fecha
          $fechaMostrar=explode("-", $_GET['fecha']);
          $fechaMostrar=$fechaMostrar[2]."-".$fechaMostrar[1]."-".$fechaMostrar[0];
          //sacamos el nombre del servicio para ponerlo de titulo
          $nombre = $servicio -> ServicioId($_GET['id']);
          echo "<h2>".$nombre['descripcion']." </h2><h3> ".$fechaMostrar."</h3>";

         ?>
        <form action="guardarPersonal.php?e=1" method="post" id="formulario" enctype="multipart/form-data">
          <div class="formthird">
            <p><input Name='avisar' type="checkbox"/>Avisar de la modificación</p>
          </div>
          <br><br><br>
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
            $empleados = $empleado -> listaEmpleadosActivos();
           ?>
           <?php
           //TURNO NOCHE.
           //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
            $personalNoche = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'tn');
            //comprobar si hay alguien asignado.
            if ($personalNoche != null && $personalNoche != false) {
              $recuentoPersonal = count($personalNoche);
              if ($recuentoPersonal>$recurso['tn']) {
                echo "<div class='formthird'>";
                echo "<p><label><i class='fa fa-question-circle'></i>Noche</label></p>";
                for ($i=0; $i < $recurso['tn']; $i++) {
                  echo "<p><select name='noche[]' class='test' id='multiple'>";
                  echo "<option value='' selected>Sin asignar</option>";
                    foreach ($empleados as $trabajador) {
                      if ($trabajador['ett'] == 1) {
                        $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }else {
                        $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  echo "</select></p>";
                }
                echo "</div>";
              }else {
                //sacar el titulo de noche
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
                        $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
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
                  if ($recurso['tn'] > 0) {
                    for ($i=0; $i < $recurso['tn']; $i++) {
                      echo "<p><select name='noche[]' class='test' id='multiple'>";
                      echo "<option value='' selected>Sin asignar</option>";
                        foreach ($empleados as $trabajador) {
                          if ($trabajador['ett'] == 1) {
                            $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                          }else {
                            $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
            }elseif ($recurso['tn']>0 && $recurso['tn'] != null && $recurso['tn'] != false) {
              echo "<div class='formthird'>";
              echo "<p><label><i class='fa fa-question-circle'></i>Noche</label></p>";
              for ($i=0; $i < $recurso['tn']; $i++) {
                echo "<p><select name='noche[]' class='test' id='multiple'>";
                echo "<option value='' selected>Sin asignar</option>";
                  foreach ($empleados as $trabajador) {
                    if ($trabajador['ett'] == 1) {
                      $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                    }else {
                      $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                    }
                    echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                echo "</select></p>";
              }
              echo "</div>";
            }
            ?>


        <?php
        //TURNO MANAÑA.
        //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
         $personalMorning = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'tm');
         //comprobar si hay alguien asignado.
         if ($personalMorning != null && $personalMorning != false) {
           $recuentoPersonal = count($personalMorning);
           if ($recuentoPersonal>$recurso['tm']) {
             echo "<div class='formthird'>";
             echo "<p><label><i class='fa fa-question-circle'></i>Mañana</label></p>";
             for ($i=0; $i < $recurso['tm']; $i++) {
               echo "<p><select name='morning[]' class='test' id='multiple'>";
               echo "<option value='' selected>Sin asignar</option>";
                 foreach ($empleados as $trabajador) {
                   if ($trabajador['ett'] == 1) {
                     $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                   }else {
                     $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                   }
                   echo "<option value='".$nombre."'>".$nombre."</option>";
                 }
               echo "</select></p>";
             }
             echo "</div>";
           }else {
             //sacar el titulo de noche
             ?>
             <div class="formthird">
               <p><label><i class="fa fa-question-circle"></i>Mañana</label></p>
             <?php
             //por cada persona asignada sacaremos un select con el listado de trabajadores
               foreach ($personalMorning as $personalM) {
                 echo "<p><select name='morning[]' class='test' id='multiple'>";
                 //si la persona estaba sin asignar, ponemos por defecto esta opcion
                 if ($personalM['empleado']=='') {
                   echo "<option value='' selected>Sin asignar</option>";
                 }else {
                   echo "<option value=''>Sin asignar</option>";
                 }
                 //sacar a los trabajadores con un foreach
                 foreach ($empleados as $trabajador) {
                   //guardar el nombre del trabajador en una variable para poder compararlo
                   if ($trabajador['ett'] == 1) {
                     $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                   }else {
                     $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                   }
                   //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                   if ($personalM['empleado'] == $nombre) {
                     echo "<option value='".$nombre."' selected>".$nombre."</option>";
                   }else {
                     echo "<option value='".$nombre."'>".$nombre."</option>";
                   }
                 }
                 //cerrar el select.
                 echo "</select></p>";
                 $recurso['tm']=$recurso['tm']-1;
               }
               if ($recurso['tm'] > 0) {
                 for ($i=0; $i < $recurso['tm']; $i++) {
                   echo "<p><select name='morning[]' class='test' id='multiple'>";
                   echo "<option value='' selected>Sin asignar</option>";
                     foreach ($empleados as $trabajador) {
                       if ($trabajador['ett'] == 1) {
                         $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                       }else {
                         $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
         }elseif ($recurso['tm']>0 && $recurso['tm'] != null && $recurso['tm'] != false) {
           echo "<div class='formthird'>";
           echo "<p><label><i class='fa fa-question-circle'></i>Mañana</label></p>";
           for ($i=0; $i < $recurso['tm']; $i++) {
             echo "<p><select name='morning[]' class='test' id='multiple'>";
             echo "<option value='' selected>Sin asignar</option>";
               foreach ($empleados as $trabajador) {
                 if ($trabajador['ett'] == 1) {
                   $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                 }else {
                   $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                 }
                 echo "<option value='".$nombre."'>".$nombre."</option>";
               }
             echo "</select></p>";
           }
           echo "</div>";
         }
         ?>

       <?php
       //TURNO TARDE.
       //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
        $personalTarde = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'tt');
        //comprobar si hay alguien asignado.
        if ($personalTarde != null && $personalTarde != false) {
          $recuentoPersonal = count($personalTarde);
          if ($recuentoPersonal>$recurso['tt']) {
            echo "<div class='formthird'>";
            echo "<p><label><i class='fa fa-question-circle'></i>Tarde</label></p>";
            for ($i=0; $i < $recurso['tt']; $i++) {
              echo "<p><select name='tarde[]' class='test' id='multiple'>";
              echo "<option value='' selected>Sin asignar</option>";
                foreach ($empleados as $trabajador) {
                  if ($trabajador['ett'] == 1) {
                    $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                  }else {
                    $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                  }
                  echo "<option value='".$nombre."'>".$nombre."</option>";
                }
              echo "</select></p>";
            }
            echo "</div>";
          }else {
            //sacar el titulo de noche
            ?>
            <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Tarde</label></p>
            <?php
            //por cada persona asignada sacaremos un select con el listado de trabajadores
              foreach ($personalTarde as $personalT) {
                echo "<p><select name='tarde[]' class='test' id='multiple'>";
                //si la persona estaba sin asignar, ponemos por defecto esta opcion
                if ($personalT['empleado']=='') {
                  echo "<option value='' selected>Sin asignar</option>";
                }else {
                  echo "<option value=''>Sin asignar</option>";
                }
                //sacar a los trabajadores con un foreach
                foreach ($empleados as $trabajador) {
                  //guardar el nombre del trabajador en una variable para poder compararlo
                  if ($trabajador['ett'] == 1) {
                    $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                  }else {
                    $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                  }
                  //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                  if ($personalT['empleado'] == $nombre) {
                    echo "<option value='".$nombre."' selected>".$nombre."</option>";
                  }else {
                    echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                }
                //cerrar el select.
                echo "</select></p>";
                $recurso['tt']=$recurso['tt']-1;
              }
              if ($recurso['tt'] > 0) {
                for ($i=0; $i < $recurso['tt']; $i++) {
                  echo "<p><select name='tarde[]' class='test' id='multiple'>";
                  echo "<option value='' selected>Sin asignar</option>";
                    foreach ($empleados as $trabajador) {
                      if ($trabajador['ett'] == 1) {
                        $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }else {
                        $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
        }elseif ($recurso['tt']>0 && $recurso['tt'] != null && $recurso['tt'] != false) {
          echo "<div class='formthird'>";
          echo "<p><label><i class='fa fa-question-circle'></i>Tarde</label></p>";
          for ($i=0; $i < $recurso['tt']; $i++) {
            echo "<p><select name='tarde[]' class='test' id='multiple'>";
            echo "<option value='' selected>Sin asignar</option>";
              foreach ($empleados as $trabajador) {
                if ($trabajador['ett'] == 1) {
                  $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                }else {
                  $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                }
                echo "<option value='".$nombre."'>".$nombre."</option>";
              }
            echo "</select></p>";
          }
          echo "</div>";
        }
        ?>

        <?php
        //TURNO CENTRAL.
        //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
         $personalCentral = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'tc');
         //comprobar si hay alguien asignado.
         if ($personalCentral != null && $personalCentral != false) {
           $recuentoPersonal = count($personalCentral);
           if ($recuentoPersonal>$recurso['tc']) {
             echo "<div class='formthird'>";
             echo "<p><label><i class='fa fa-question-circle'></i>Central</label></p>";
             for ($i=0; $i < $recurso['tc']; $i++) {
               echo "<p><select name='central[]' class='test' id='multiple'>";
               echo "<option value='' selected>Sin asignar</option>";
                 foreach ($empleados as $trabajador) {
                   if ($trabajador['ett'] == 1) {
                     $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                   }else {
                     $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                   }
                   echo "<option value='".$nombre."'>".$nombre."</option>";
                 }
               echo "</select></p>";
             }
             echo "</div>";
           }else {
             //sacar el titulo de noche
             ?>
             <div class="formthird">
               <p><label><i class="fa fa-question-circle"></i>Central</label></p>
             <?php
             //por cada persona asignada sacaremos un select con el listado de trabajadores
               foreach ($personalCentral as $personalC) {
                 echo "<p><select name='central[]' class='test' id='multiple'>";
                 //si la persona estaba sin asignar, ponemos por defecto esta opcion
                 if ($personalC['empleado']=='') {
                   echo "<option value='' selected>Sin asignar</option>";
                 }else {
                   echo "<option value=''>Sin asignar</option>";
                 }
                 //sacar a los trabajadores con un foreach
                 foreach ($empleados as $trabajador) {
                   //guardar el nombre del trabajador en una variable para poder compararlo
                   if ($trabajador['ett'] == 1) {
                     $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                   }else {
                     $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                   }
                   //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                   if ($personalC['empleado'] == $nombre) {
                     echo "<option value='".$nombre."' selected>".$nombre."</option>";
                   }else {
                     echo "<option value='".$nombre."'>".$nombre."</option>";
                   }
                 }
                 //cerrar el select.
                 echo "</select></p>";
                 $recurso['tc']=$recurso['tc']-1;
               }
               if ($recurso['tc'] > 0) {
                 for ($i=0; $i < $recurso['tc']; $i++) {
                   echo "<p><select name='central[]' class='test' id='multiple'>";
                   echo "<option value='' selected>Sin asignar</option>";
                     foreach ($empleados as $trabajador) {
                       if ($trabajador['ett'] == 1) {
                         $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                       }else {
                         $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
         }elseif ($recurso['tc']>0 && $recurso['tc'] != null && $recurso['tc'] != false) {
           echo "<div class='formthird'>";
           echo "<p><label><i class='fa fa-question-circle'></i>Central</label></p>";
           for ($i=0; $i < $recurso['tc']; $i++) {
             echo "<p><select name='central[]' class='test' id='multiple'>";
             echo "<option value='' selected>Sin asignar</option>";
               foreach ($empleados as $trabajador) {
                 if ($trabajador['ett'] == 1) {
                   $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                 }else {
                   $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                 }
                 echo "<option value='".$nombre."'>".$nombre."</option>";
               }
             echo "</select></p>";
           }
           echo "</div>";
         }
         ?>

         <?php
         //TURNO ESPECIAL 1.
         //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
          $personal1 = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'otro1');
          //comprobar si hay alguien asignado.
          if ($personal1 != null && $personal1 != false) {
            $recuentoPersonal = count($personal1);
            if ($recuentoPersonal>$recurso['otro1']) {
              echo "<div class='formthird'>";
              echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio1']." a ".$recurso['fin1']."</i></label></p>";
              echo "<input type='hidden' name='hora1' value='DE ".$recurso['inicio1']." A ".$recurso['fin1']."'>";
              for ($i=0; $i < $recurso['otro1']; $i++) {
                echo "<p><select name='otro1[]' class='test' id='multiple'>";
                echo "<option value='' selected>Sin asignar</option>";
                  foreach ($empleados as $trabajador) {
                    if ($trabajador['ett'] == 1) {
                      $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                    }else {
                      $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                    }
                    echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                echo "</select></p>";
              }
              echo "</div>";
            }else {
              //sacar el titulo de noche
              ?>
              <div class="formthird">
              <?php
              echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio1']." a ".$recurso['fin1']."</i></label></p>";
              echo "<input type='hidden' name='hora1' value='DE ".$recurso['inicio1']." A ".$recurso['fin1']."'>";
              //por cada persona asignada sacaremos un select con el listado de trabajadores
                foreach ($personal1 as $personalE1) {
                  echo "<p><select name='otro1[]' class='test' id='multiple'>";
                  //si la persona estaba sin asignar, ponemos por defecto esta opcion
                  if ($personalE1['empleado']=='') {
                    echo "<option value='' selected>Sin asignar</option>";
                  }else {
                    echo "<option value=''>Sin asignar</option>";
                  }
                  //sacar a los trabajadores con un foreach
                  foreach ($empleados as $trabajador) {
                    //guardar el nombre del trabajador en una variable para poder compararlo
                    if ($trabajador['ett'] == 1) {
                      $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                    }else {
                      $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                    }
                    //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                    if ($personalE1['empleado'] == $nombre) {
                      echo "<option value='".$nombre."' selected>".$nombre."</option>";
                    }else {
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  }
                  //cerrar el select.
                  echo "</select></p>";
                  $recurso['otro1']=$recurso['otro1']-1;
                }
                if ($recurso['otro1'] > 0) {
                  for ($i=0; $i < $recurso['otro1']; $i++) {
                    echo "<p><select name='otro1[]' class='test' id='multiple'>";
                    echo "<option value='' selected>Sin asignar</option>";
                      foreach ($empleados as $trabajador) {
                        if ($trabajador['ett'] == 1) {
                          $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                        }else {
                          $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
          }elseif ($recurso['otro1']>0 && $recurso['otro1'] != null && $recurso['otro1'] != false) {
            echo "<div class='formthird'>";
            echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio1']." a ".$recurso['fin1']."</i></label></p>";
            echo "<input type='hidden' name='hora1' value='DE ".$recurso['inicio1']." A ".$recurso['fin1']."'>";
            for ($i=0; $i < $recurso['otro1']; $i++) {
              echo "<p><select name='otro1[]' class='test' id='multiple'>";
              echo "<option value='' selected>Sin asignar</option>";
                foreach ($empleados as $trabajador) {
                  if ($trabajador['ett'] == 1) {
                    $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                  }else {
                    $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                  }
                  echo "<option value='".$nombre."'>".$nombre."</option>";
                }
              echo "</select></p>";
            }
            echo "</div>";
          }
          ?>

          <?php
          //TURNO ESPECIAL 2.
          //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
           $personal2 = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'otro2');
           //comprobar si hay alguien asignado.
           if ($personal2 != null && $personal2 != false) {
             $recuentoPersonal = count($personal2);
             if ($recuentoPersonal>$recurso['otro2']) {
               echo "<div class='formthird'>";
               echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio2']." a ".$recurso['fin2']."</i></label></p>";
               echo "<input type='hidden' name='hora2' value='DE ".$recurso['inicio2']." A ".$recurso['fin2']."'>";
               for ($i=0; $i < $recurso['otro2']; $i++) {
                 echo "<p><select name='otro2[]' class='test' id='multiple'>";
                 echo "<option value='' selected>Sin asignar</option>";
                   foreach ($empleados as $trabajador) {
                     if ($trabajador['ett'] == 1) {
                       $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                     }else {
                       $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                     }
                     echo "<option value='".$nombre."'>".$nombre."</option>";
                   }
                 echo "</select></p>";
               }
               echo "</div>";
             }else {
               //sacar el titulo de noche
               ?>
               <div class="formthird">
               <?php
               echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio2']." a ".$recurso['fin2']."</i></label></p>";
               echo "<input type='hidden' name='hora2' value='DE ".$recurso['inicio2']." A ".$recurso['fin2']."'>";
               //por cada persona asignada sacaremos un select con el listado de trabajadores
                 foreach ($personal2 as $personalE2) {
                   echo "<p><select name='otro2[]' class='test' id='multiple'>";
                   //si la persona estaba sin asignar, ponemos por defecto esta opcion
                   if ($personalE2['empleado']=='') {
                     echo "<option value='' selected>Sin asignar</option>";
                   }else {
                     echo "<option value=''>Sin asignar</option>";
                   }
                   //sacar a los trabajadores con un foreach
                   foreach ($empleados as $trabajador) {
                     //guardar el nombre del trabajador en una variable para poder compararlo
                     if ($trabajador['ett'] == 1) {
                       $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                     }else {
                       $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                     }
                     //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                     if ($personalE2['empleado'] == $nombre) {
                       echo "<option value='".$nombre."' selected>".$nombre."</option>";
                     }else {
                       echo "<option value='".$nombre."'>".$nombre."</option>";
                     }
                   }
                   //cerrar el select.
                   echo "</select></p>";
                   $recurso['otro2']=$recurso['otro2']-1;
                 }
                 if ($recurso['otro2'] > 0) {
                   for ($i=0; $i < $recurso['otro2']; $i++) {
                     echo "<p><select name='otro2[]' class='test' id='multiple'>";
                     echo "<option value='' selected>Sin asignar</option>";
                       foreach ($empleados as $trabajador) {
                         if ($trabajador['ett'] == 1) {
                           $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                         }else {
                           $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
           }elseif ($recurso['otro2']>0 && $recurso['otro2'] != null && $recurso['otro2'] != false) {
             echo "<div class='formthird'>";
             echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio2']." a ".$recurso['fin2']."</i></label></p>";
             echo "<input type='hidden' name='hora2' value='DE ".$recurso['inicio2']." A ".$recurso['fin2']."'>";
             for ($i=0; $i < $recurso['otro2']; $i++) {
               echo "<p><select name='otro2[]' class='test' id='multiple'>";
               echo "<option value='' selected>Sin asignar</option>";
                 foreach ($empleados as $trabajador) {
                   if ($trabajador['ett'] == 1) {
                     $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                   }else {
                     $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                   }
                   echo "<option value='".$nombre."'>".$nombre."</option>";
                 }
               echo "</select></p>";
             }
             echo "</div>";
           }
           ?>


           <?php
           //TURNO ESPECIAL 3.
           //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
            $personal3 = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'otro3');
            //comprobar si hay alguien asignado.
            if ($personal3 != null && $personal3 != false) {
              $recuentoPersonal = count($personal3);
              if ($recuentoPersonal>$recurso['otro3']) {
                echo "<div class='formthird'>";
                echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio3']." a ".$recurso['fin3']."</i></label></p>";
                echo "<input type='hidden' name='hora3' value='DE ".$recurso['inicio3']." A ".$recurso['fin3']."'>";
                for ($i=0; $i < $recurso['otro3']; $i++) {
                  echo "<p><select name='otro3[]' class='test' id='multiple'>";
                  echo "<option value='' selected>Sin asignar</option>";
                    foreach ($empleados as $trabajador) {
                      if ($trabajador['ett'] == 1) {
                        $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }else {
                        $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  echo "</select></p>";
                }
                echo "</div>";
              }else {
                //sacar el titulo de noche
                ?>
                <div class="formthird">
                <?php
                echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio3']." a ".$recurso['fin3']."</i></label></p>";
                echo "<input type='hidden' name='hora3' value='DE ".$recurso['inicio3']." A ".$recurso['fin3']."'>";
                //por cada persona asignada sacaremos un select con el listado de trabajadores
                  foreach ($personal3 as $personalE3) {
                    echo "<p><select name='otro3[]' class='test' id='multiple'>";
                    //si la persona estaba sin asignar, ponemos por defecto esta opcion
                    if ($personalE3['empleado']=='') {
                      echo "<option value='' selected>Sin asignar</option>";
                    }else {
                      echo "<option value=''>Sin asignar</option>";
                    }
                    //sacar a los trabajadores con un foreach
                    foreach ($empleados as $trabajador) {
                      //guardar el nombre del trabajador en una variable para poder compararlo
                      if ($trabajador['ett'] == 1) {
                        $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                      }else {
                        $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                      }
                      //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                      if ($personalE3['empleado'] == $nombre) {
                        echo "<option value='".$nombre."' selected>".$nombre."</option>";
                      }else {
                        echo "<option value='".$nombre."'>".$nombre."</option>";
                      }
                    }
                    //cerrar el select.
                    echo "</select></p>";
                    $recurso['otro3']=$recurso['otro3']-1;
                  }
                  if ($recurso['otro3'] > 0) {
                    for ($i=0; $i < $recurso['otro3']; $i++) {
                      echo "<p><select name='otro3[]' class='test' id='multiple'>";
                      echo "<option value='' selected>Sin asignar</option>";
                        foreach ($empleados as $trabajador) {
                          if ($trabajador['ett'] == 1) {
                            $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                          }else {
                            $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
            }elseif ($recurso['otro3']>0 && $recurso['otro3'] != null && $recurso['otro3'] != false) {
              echo "<div class='formthird'>";
              echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio3']." a ".$recurso['fin3']."</i></label></p>";
              echo "<input type='hidden' name='hora3' value='DE ".$recurso['inicio3']." A ".$recurso['fin3']."'>";
              for ($i=0; $i < $recurso['otro3']; $i++) {
                echo "<p><select name='otro3[]' class='test' id='multiple'>";
                echo "<option value='' selected>Sin asignar</option>";
                  foreach ($empleados as $trabajador) {
                    if ($trabajador['ett'] == 1) {
                      $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                    }else {
                      $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                    }
                    echo "<option value='".$nombre."'>".$nombre."</option>";
                  }
                echo "</select></p>";
              }
              echo "</div>";
            }
            ?>


            <?php
            //TURNO ESPECIAL 4.
            //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
             $personal4 = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'otro4');
             //comprobar si hay alguien asignado.
             if ($personal4 != null && $personal4 != false) {
               $recuentoPersonal = count($personal4);
               if ($recuentoPersonal>$recurso['otro4']) {
                 echo "<div class='formthird'>";
                 echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio4']." a ".$recurso['fin4']."</i></label></p>";
                 echo "<input type='hidden' name='hora4' value='DE ".$recurso['inicio4']." A ".$recurso['fin4']."'>";
                 for ($i=0; $i < $recurso['otro4']; $i++) {
                   echo "<p><select name='otro4[]' class='test' id='multiple'>";
                   echo "<option value='' selected>Sin asignar</option>";
                     foreach ($empleados as $trabajador) {
                       if ($trabajador['ett'] == 1) {
                         $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                       }else {
                         $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                       }
                       echo "<option value='".$nombre."'>".$nombre."</option>";
                     }
                   echo "</select></p>";
                 }
                 echo "</div>";
               }else {
                 //sacar el titulo de noche
                 ?>
                 <div class="formthird">
                 <?php
                 echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio4']." a ".$recurso['fin4']."</i></label></p>";
                 echo "<input type='hidden' name='hora4' value='DE ".$recurso['inicio4']." A ".$recurso['fin4']."'>";
                 //por cada persona asignada sacaremos un select con el listado de trabajadores
                   foreach ($personal4 as $personalE4) {
                     echo "<p><select name='otro4[]' class='test' id='multiple'>";
                     //si la persona estaba sin asignar, ponemos por defecto esta opcion
                     if ($personalE1['empleado']=='') {
                       echo "<option value='' selected>Sin asignar</option>";
                     }else {
                       echo "<option value=''>Sin asignar</option>";
                     }
                     //sacar a los trabajadores con un foreach
                     foreach ($empleados as $trabajador) {
                       //guardar el nombre del trabajador en una variable para poder compararlo
                       if ($trabajador['ett'] == 1) {
                         $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                       }else {
                         $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                       }
                       //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                       if ($personalE4['empleado'] == $nombre) {
                         echo "<option value='".$nombre."' selected>".$nombre."</option>";
                       }else {
                         echo "<option value='".$nombre."'>".$nombre."</option>";
                       }
                     }
                     //cerrar el select.
                     echo "</select></p>";
                     $recurso['otro4']=$recurso['otro4']-1;
                   }
                   if ($recurso['otro4'] > 0) {
                     for ($i=0; $i < $recurso['otro4']; $i++) {
                       echo "<p><select name='otro4[]' class='test' id='multiple'>";
                       echo "<option value='' selected>Sin asignar</option>";
                         foreach ($empleados as $trabajador) {
                           if ($trabajador['ett'] == 1) {
                             $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                           }else {
                             $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
             }elseif ($recurso['otro4']>0 && $recurso['otro4'] != null && $recurso['otro4'] != false) {
               echo "<div class='formthird'>";
               echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio4']." a ".$recurso['fin4']."</i></label></p>";
               echo "<input type='hidden' name='hora4' value='DE ".$recurso['inicio4']." A ".$recurso['fin4']."'>";
               for ($i=0; $i < $recurso['otro4']; $i++) {
                 echo "<p><select name='otro4[]' class='test' id='multiple'>";
                 echo "<option value='' selected>Sin asignar</option>";
                   foreach ($empleados as $trabajador) {
                     if ($trabajador['ett'] == 1) {
                       $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                     }else {
                       $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                     }
                     echo "<option value='".$nombre."'>".$nombre."</option>";
                   }
                 echo "</select></p>";
               }
               echo "</div>";
             }
             ?>

             <?php
             //TURNO ESPECIAL 5.
             //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
              $personal5 = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'otro5');
              //comprobar si hay alguien asignado.
              if ($personal5 != null && $personal5 != false) {
                $recuentoPersonal = count($personal5);
                if ($recuentoPersonal>$recurso['otro5']) {
                  echo "<div class='formthird'>";
                  echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio5']." a ".$recurso['fin5']."</i></label></p>";
                  echo "<input type='hidden' name='hora5' value='DE ".$recurso['inicio5']." A ".$recurso['fin5']."'>";
                  for ($i=0; $i < $recurso['otro5']; $i++) {
                    echo "<p><select name='otro5[]' class='test' id='multiple'>";
                    echo "<option value='' selected>Sin asignar</option>";
                      foreach ($empleados as $trabajador) {
                        if ($trabajador['ett'] == 1) {
                          $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                        }else {
                          $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                        }
                        echo "<option value='".$nombre."'>".$nombre."</option>";
                      }
                    echo "</select></p>";
                  }
                  echo "</div>";
                }else {
                  //sacar el titulo de noche
                  ?>
                  <div class="formthird">
                  <?php
                  echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio5']." a ".$recurso['fin5']."</i></label></p>";
                  echo "<input type='hidden' name='hora5' value='DE ".$recurso['inicio5']." A ".$recurso['fin5']."'>";
                  //por cada persona asignada sacaremos un select con el listado de trabajadores
                    foreach ($personal5 as $personalE5) {
                      echo "<p><select name='otro5[]' class='test' id='multiple'>";
                      //si la persona estaba sin asignar, ponemos por defecto esta opcion
                      if ($personalE5['empleado']=='') {
                        echo "<option value='' selected>Sin asignar</option>";
                      }else {
                        echo "<option value=''>Sin asignar</option>";
                      }
                      //sacar a los trabajadores con un foreach
                      foreach ($empleados as $trabajador) {
                        //guardar el nombre del trabajador en una variable para poder compararlo
                        if ($trabajador['ett'] == 1) {
                          $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                        }else {
                          $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                        }
                        //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                        if ($personalE5['empleado'] == $nombre) {
                          echo "<option value='".$nombre."' selected>".$nombre."</option>";
                        }else {
                          echo "<option value='".$nombre."'>".$nombre."</option>";
                        }
                      }
                      //cerrar el select.
                      echo "</select></p>";
                      $recurso['otro5']=$recurso['otro5']-1;
                    }
                    if ($recurso['otro5'] > 0) {
                      for ($i=0; $i < $recurso['otro5']; $i++) {
                        echo "<p><select name='otro5[]' class='test' id='multiple'>";
                        echo "<option value='' selected>Sin asignar</option>";
                          foreach ($empleados as $trabajador) {
                            if ($trabajador['ett'] == 1) {
                              $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                            }else {
                              $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
              }elseif ($recurso['otro5']>0 && $recurso['otro5'] != null && $recurso['otro5'] != false) {
                echo "<div class='formthird'>";
                echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio5']." a ".$recurso['fin5']."</i></label></p>";
                echo "<input type='hidden' name='hora5' value='DE ".$recurso['inicio5']." A ".$recurso['fin5']."'>";
                for ($i=0; $i < $recurso['otro5']; $i++) {
                  echo "<p><select name='otro5[]' class='test' id='multiple'>";
                  echo "<option value='' selected>Sin asignar</option>";
                    foreach ($empleados as $trabajador) {
                      if ($trabajador['ett'] == 1) {
                        $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }else {
                        $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                      }
                      echo "<option value='".$nombre."'>".$nombre."</option>";
                    }
                  echo "</select></p>";
                }
                echo "</div>";
              }
              ?>


              <?php
              //TURNO ESPECIAL 6.
              //buscar el personal que hay asignado para ese dia y ese servicio en el turno de noche
               $personal6 = $personal -> empleadosServicio($_GET['id'], $_GET['fecha'], 'otro6');
               //comprobar si hay alguien asignado.
               if ($personal6 != null && $personal6 != false) {
                 $recuentoPersonal = count($personal6);
                 if ($recuentoPersonal>$recurso['otro6']) {
                   echo "<div class='formthird'>";
                   echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio6']." a ".$recurso['fin6']."</i></label></p>";
                   echo "<input type='hidden' name='hora6' value='DE ".$recurso['inicio6']." A ".$recurso['fin6']."'>";
                   for ($i=0; $i < $recurso['otro6']; $i++) {
                     echo "<p><select name='otro6[]' class='test' id='multiple'>";
                     echo "<option value='' selected>Sin asignar</option>";
                       foreach ($empleados as $trabajador) {
                         if ($trabajador['ett'] == 1) {
                           $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                         }else {
                           $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                         }
                         echo "<option value='".$nombre."'>".$nombre."</option>";
                       }
                     echo "</select></p>";
                   }
                   echo "</div>";
                 }else {
                   //sacar el titulo de noche
                   ?>
                   <div class="formthird">
                   <?php
                   echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio6']." a ".$recurso['fin6']."</i></label></p>";
                   echo "<input type='hidden' name='hora6' value='DE ".$recurso['inicio6']." A ".$recurso['fin6']."'>";
                   //por cada persona asignada sacaremos un select con el listado de trabajadores
                     foreach ($personal6 as $personalE6) {
                       echo "<p><select name='otro6[]' class='test' id='multiple'>";
                       //si la persona estaba sin asignar, ponemos por defecto esta opcion
                       if ($personalE6['empleado']=='') {
                         echo "<option value='' selected>Sin asignar</option>";
                       }else {
                         echo "<option value=''>Sin asignar</option>";
                       }
                       //sacar a los trabajadores con un foreach
                       foreach ($empleados as $trabajador) {
                         //guardar el nombre del trabajador en una variable para poder compararlo
                         if ($trabajador['ett'] == 1) {
                           $nombre = "ETT-".$trabajador['nombre']." ".$trabajador['apellidos'];
                         }else {
                           $nombre = $trabajador['nombre']." ".$trabajador['apellidos'];
                         }
                         //si el nombre del trabajador y el asignado es el mismo, lo marcamos por defecto, sinos lo sacamos en la lista normal
                         if ($personalE6['empleado'] == $nombre) {
                           echo "<option value='".$nombre."' selected>".$nombre."</option>";
                         }else {
                           echo "<option value='".$nombre."'>".$nombre."</option>";
                         }
                       }
                       //cerrar el select.
                       echo "</select></p>";
                       $recurso['otro6']=$recurso['otro6']-1;
                     }
                     if ($recurso['otro6'] > 0) {
                       for ($i=0; $i < $recurso['otro6']; $i++) {
                         echo "<p><select name='otro6[]' class='test' id='multiple'>";
                         echo "<option value='' selected>Sin asignar</option>";
                           foreach ($empleados as $trabajador) {
                             if ($trabajador['ett'] == 1) {
                               $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                             }else {
                               $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
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
               }elseif ($recurso['otro6']>0 && $recurso['otro6'] != null && $recurso['otro6'] != false) {
                 echo "<div class='formthird'>";
                 echo "<p><label><i class='fa fa-question-circle'>De ".$recurso['inicio6']." a ".$recurso['fin6']."</i></label></p>";
                 echo "<input type='hidden' name='hora6' value='DE ".$recurso['inicio6']." A ".$recurso['fin6']."'>";
                 for ($i=0; $i < $recurso['otro6']; $i++) {
                   echo "<p><select name='otro6[]' class='test' id='multiple'>";
                   echo "<option value='' selected>Sin asignar</option>";
                     foreach ($empleados as $trabajador) {
                       if ($trabajador['ett'] == 1) {
                         $nombre = "ETT-".$trabajador['nombre'] ." ".$trabajador['apellidos'];
                       }else {
                         $nombre=$trabajador['nombre'] ." ".$trabajador['apellidos'];
                       }
                       echo "<option value='".$nombre."'>".$nombre."</option>";
                     }
                   echo "</select></p>";
                 }
                 echo "</div>";
               }
               ?>

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
