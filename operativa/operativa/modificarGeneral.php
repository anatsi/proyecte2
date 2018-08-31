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
  <title>Modificar actividad</title>
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

<div class="codrops-top clearfix">
  <?php
    //llamamos a la función para devolver el nombre de usuario.
    $nombreuser=$usuario->nombreUsuario($_SESSION['usuario']);
    //sacamos el nombre de usuario por su id
    echo "<a><strong>Bienvenido ".$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../../logout.php" id="logout">Cerrar Sesion</a></span>
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

    <?php
      $infoservicio=$servicio->ServicioId($_GET['servicio']);
      $inforecurso=$recursos->RecursosId($_GET['servicio']);
     ?>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%; color:black;">
          <a href="../../dashboard.php">Inicio</a> >> <a href="../index.php">Gestión Actividades</a> >> <a href="actividadesActuales.php">Actividades actuales</a> >> <a href="">Modificar actividad</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>Modificar actividad</h2>
        <h3><?=$infoservicio['descripcion']?></h3>
        <form action="modificarGeneral.php" method="post" id="formulario">
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>Actividad</label><input type="text" value='<?=$infoservicio['descripcion']?>' name="descripcion"/></p>
            <p><label><i class="fa fa-question-circle"></i>Descripción</label><textarea name="cdo"><?=$infoservicio['com_depto']?></textarea></p>
            <p><label><i class="fa fa-question-circle"></i>Modelos (*)</label>
            <select name="sel[]" class="test" multiple="multiple" id='multiple'>
              <?php
              //comprobamos que modelos estan seleccionados para asi ponerlos checkeados en el formulario
                $mondeo=0;
                $galaxy=0;
                $smax=0;
                $transit=0;
                $kuga=0;
                $todos=0;

                //sacamos los modelos de la bbdd y los separamos
                $modelos=explode(",", $infoservicio['modelos']);
                foreach ($modelos as $modelo) {
                  if ($modelo==" MONDEO" || $modelo=="MONDEO") {
                    $mondeo=1;
                  }
                  if ($modelo==" GALAXY" || $modelo=="GALAXY") {
                    $galaxy=1;
                  }
                  if ($modelo==" S-MAX" || $modelo=="S-MAX") {
                    $smax=1;
                  }
                  if ($modelo==" TRANSIT CONNECT" || $modelo=="TRANSIT CONNECT") {
                    $transit=1;
                  }
                  if ($modelo==" KUGA" || $modelo=="KUGA") {
                    $kuga=1;
                  }
                }
                //sacamos por pantalla todos deoendiendo de si estan checked o no
                if ($mondeo==1) {
                  echo "<option value='MONDEO' selected>MONDEO</option>";
                }else {
                  echo "<option value='MONDEO'>MONDEO</option>";
                }
                if ($galaxy==1) {
                  echo "<option value='GALAXY' selected>GALAXY</option>";
                }else {
                  echo "<option value='GALAXY'>GALAXY</option>";
                }
                if ($smax==1) {
                    echo "<option value='S-MAX' selected>S-MAX</option>";
                }else {
                  echo "<option value='S-MAX'>S-MAX</option>";
                }
                if ($transit==1) {
                  echo "<option value='TRANSIT CONNECT' selected>CONNECT</option>";
                }else {
                  echo "<option value='TRANSIT CONNECT'>CONNECT</option>";
                }
                if ($kuga==1) {
                  echo "<option value='KUGA' selected>KUGA</option>";
                }else {
                  echo "<option value='KUGA'>KUGA</option>";
                }

               ?>
               </select></p>
               <?php
                $resp=$responsable->responsableId($infoservicio['responsable']);
                ?>
               <p><label>Responsable</label>
                 <select name="responsable" value='<?php echo $resp['nombre']; ?>'>
                   <?php
                     $responsables= $responsable->listaResponsables();
                     foreach ($responsables as $persona) {
                       if ($persona['id']==$resp['id']) {
                         echo "<option value=".$persona['id']." selected>".$persona['nombre']."</option>";

                       }else {
                         echo "<option value=".$persona['id'].">".$persona['nombre']."</option>";
                       }
                     }
                    ?>
                 </select></p>

       </div>
          <div class="formthird" id='contenedor'>
            <input type="hidden" value=<?=$infoservicio['id']?> name="id">

              <p><label><i class="fa fa-question-circle"></i>Recursos totales</label><input type="number" min='0' value=<?=$inforecurso['total']?> name="recursos" id="total" readonly/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno noche</label><input type="number" min='0'name="tn" id="tn" value=<?=$inforecurso['tn']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno mañana</label><input type="number" min='0' name="tm" id="tm" value=<?=$inforecurso['tm']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno tarde</label><input type="number" min='0' name="tt" id="tt" value=<?=$inforecurso['tt']?> onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno central</label><input type="number" min='0'name="tc" id="tc" value=<?=$inforecurso['tc']?> onclick="suma();" onkeyup="suma();"/></p>
          </div>
          <div class="formthird">
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i1'value='<?=$inforecurso['inicio1']?>'/>
              <input class='threeinputs2' type='time' name='f1' id="f1" value='<?=$inforecurso['fin1']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro1']?> min='0' id='in1' onclick='suma();' onkeyup='suma();' name='o1'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i2'value='<?=$inforecurso['inicio2']?>'/>
              <input class='threeinputs2' type='time' name='f2'id="f2" value='<?=$inforecurso['fin2']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro2']?> min='0' id='in2' onclick='suma();' onkeyup='suma();' name='o2'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i3'value='<?=$inforecurso['inicio3']?>'/>
              <input class='threeinputs2' type='time' name='f3' id="f3"value='<?=$inforecurso['fin3']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro3']?> min='0' id='in3' onclick='suma();' onkeyup='suma();' name='o3'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i4' value="<?=$inforecurso['inicio4']?>"/>
              <input class='threeinputs2' type='time' name='f4' id="f4"value='<?=$inforecurso['fin4']?>'/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro4']?> min='0' id='in4' onclick='suma();' onkeyup='suma();' name='o4'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i5' value="<?=$inforecurso['inicio5']?>"/>
              <input class='threeinputs2' type='time' name='f5' id="f5"value="<?=$inforecurso['fin5']?>"/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro5']?> min='0' id='in5' onclick='suma();' onkeyup='suma();' name='o5'/>
            </p>
            <p>
              <label><i class='fa fa-qestion-circle'></i>Otro turno</label>
              <input class='threeinputs' type='time' name='i6' value="<?=$inforecurso['inicio6']?>"/>
              <input class='threeinputs2' type='time' name='f6' id="f6" value="<?=$inforecurso['fin6']?>"/>
              <input class='threeinputs1' type='number' value=<?=$inforecurso['otro6']?> min='0' id='in6' onclick='suma();' onkeyup='suma();' name='o6'/>
            </p>
          </div>

          <div class="submitbuttons">
              <input class="submitone" type="submit" value="Modificar" name="submit"/>
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
<?php
if (isset($_POST['submit']) && isset($_POST['id']) && isset($_POST['recursos'])) {
  //juntamos los modelos en una variable
  $modelos="";
  $arrayModelos=$_POST['sel'];
  for ($i=0; $i < count($arrayModelos); $i++) {
    if ($i==0) {
      $modelos=$arrayModelos[$i];
    }else {
      $modelos= $modelos .", ".$arrayModelos[$i];
    }
  }
  //arreglamos los inicios y los finales
  if (empty($_POST['i1'])) {
    $_POST['i1']='';
  }
  if (empty($_POST['f1'])) {
    $_POST['f1']='';
  }
  if (empty($_POST['i2'])) {
    $_POST['i2']='';
  }
  if (empty($_POST['f2'])) {
    $_POST['f2']='';
  }
  if (empty($_POST['i3'])) {
    $_POST['i3']='';
  }
  if (empty($_POST['f3'])) {
    $_POST['f3']='';
  }
  if (empty($_POST['i4'])) {
    $_POST['i4']='';
  }
  if (empty($_POST['f4'])) {
    $_POST['f4']='';
  }
  if (empty($_POST['i5'])) {
    $_POST['i5']='';
  }
  if (empty($_POST['f5'])) {
    $_POST['f5']='';
  }
  if (empty($_POST['i6'])) {
    $_POST['i6']='';
  }
  if (empty($_POST['f6'])) {
    $_POST['f6']='';
  }
  //consultas para guardar los datos
  $modificacion=$servicio->ActualizarActividad($_POST['id'], $_POST['descripcion'], $modelos, $_POST['recursos'], $_POST['responsable'], $_POST['tel'], $_POST['correo'], $_POST['cdo']);
    $nuevorecurso=$recursos->ActualizarRecursosActividad($_POST['id'], $_POST['recursos'], $_POST['tm'], $_POST['tt'], $_POST['tn'], $_POST['tc'], $_POST['o1'], $_POST['i1'], $_POST['f1'],
     $_POST['o2'], $_POST['i2'],
     $_POST['f2'], $_POST['o3'], $_POST['i3'], $_POST['f3'], $_POST['o4'], $_POST['i4'], $_POST['f4'], $_POST['o5'], $_POST['i5'], $_POST['f5'],
      $_POST['o6'], $_POST['i6'], $_POST['f6']);
      if ($nuevorecurso==null || $modificacion==null) {
        ?>
          <script type="text/javascript">
            alert('ERROR AL ACTUALIZAR LA ACTIVIDAD. INTENTELO DE NUEVO');
            window.location='actividadesActuales.php';
          </script>
        <?php
      }else {
        ?>
          <script type="text/javascript">
            alert('Actividad actualizada con exito');
            window.location='actividadesActuales.php';
          </script>
        <?php
      }

}

 ?>
 <?php } ?>
