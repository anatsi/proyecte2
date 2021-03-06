<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../../ddbb/sesiones.php';
require_once '../../ddbb/users.php';
require_once '../bbdd/cliente.php';
require_once '../bbdd/servicio.php';
require_once '../bbdd/responsable.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();
$responsable=new Responsable();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Modificar información actividad</title>
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
     ?>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%; color:black;">
          <a href="../../dashboard.php">Inicio</a> >> <a href="../index.php">Gestión Actividades</a> >> <a href="actividadesActuales.php">Actividades actuales</a> >> <a href="">Modificar actividad</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>Modificar informacion de la actividad</h2>
        <h3><?=$infoservicio['descripcion']?></h3>
        <h4><a style="color: red;" href='cancelarServicio.php?servicio=<?=$infoservicio['id']?>'>Cancelar actividad</a></h4>
        <form action="modificarInfo.php" method="post" id="formulario">
          <div class="formthird" id='contenedor'>
            <input type="hidden" value=<?=$infoservicio['id']?> name="id">
            <p><label>SELECCIONAR DIAS</label></p>
            <p><label><i class="fa fa-question-circle"></i>Dia suelto</label><input type="date" name="suelto" min= <?php echo date('Y-m-d');?> id="suelto"/></p>
            <p><label>Más de un dia</label></p>
            <p><label><i class="fa fa-question-circle"></i>Inicio</label><input type="date" name="inicio" min= <?php echo date('Y-m-d');?> id="inicio"/></p>
            <p><label><i class="fa fa-question-circle"></i>Fin</label><input type="date" name="fin" min= <?php echo date('Y-m-d');?> id="fin"/></p>
          </div>
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
                  if ($modelo==" Mondeo" || $modelo=="Mondeo") {
                    $mondeo=1;
                  }
                  if ($modelo==" Galaxy" || $modelo=="Galaxy") {
                    $galaxy=1;
                  }
                  if ($modelo==" S-Max" || $modelo=="S-Max") {
                    $smax=1;
                  }
                  if ($modelo==" Transit connect" || $modelo=="Transit connect") {
                    $transit=1;
                  }
                  if ($modelo==" Kuga" || $modelo=="Kuga") {
                    $kuga=1;
                  }
                }
                //sacamos por pantalla todos deoendiendo de si estan checked o no
                if ($mondeo==1) {
                  echo "<option value='Mondeo' selected>Mondeo</option>";
                }else {
                  echo "<option value='Mondeo'>Mondeo</option>";
                }
                if ($galaxy==1) {
                  echo "<option value='Galaxy' selected>Galaxy</option>";
                }else {
                  echo "<option value='Galaxy'>Galaxy</option>";
                }
                if ($smax==1) {
                    echo "<option value='S-Max' selected>S-Max</option>";
                }else {
                  echo "<option value='S-Max'>S-Max</option>";
                }
                if ($transit==1) {
                  echo "<option value='Transit connect' selected>Connect</option>";
                }else {
                  echo "<option value='Transit connect'>Connect</option>";
                }
                if ($kuga==1) {
                  echo "<option value='Kuga' selected>Kuga</option>";
                }else {
                  echo "<option value='Kuga'>Kuga</option>";
                }

               ?>
               </select></p>
               <?php
                $resp=$responsable->responsableId($infoservicio['responsable']);
                ?>
               <p><label><i class="fa fa-question-circle"></i>Responsable</label>
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
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Com. Jefe Turno</label><textarea name="csup"><?=$infoservicio['com_supervisor']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Com. RRHH</label><textarea name="crrhh"><?=$infoservicio['com_rrhh']?></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Com. Admin. Financiero</label><textarea name="caf"><?=$infoservicio['com_admin_fin']?></textarea></p>
          </div>
          <div class="submitbuttons">
              <input class="submithree" style="width: 25%; margin-right: 15%; margin-left: 5%;" type="submit" value="Modificar" name="submit"/>
              <button class='submitwo' type='button' name='button' onclick=window.location='actividadesActuales.php'>Atras</button>
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

if (isset($_POST['submit'])) {
  if (isset($_POST['id']) && empty($_POST['inicio'])==false || isset($_POST['id']) && empty($_POST['suelto'])==false) {
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
    //llamamos a la funcion de modificar la informacion
    $modificacion=$servicio->modificarInfo($_POST['id'], $_POST['inicio'], $_POST['fin'], $_POST['suelto'], $_POST['descripcion'], $modelos, $_POST['responsable'], $_POST['tel'], $_POST['correo']);
    $modComentarios= $servicio->ActualizarComentarios($_POST['id'], $_POST['csup'], $_POST['crrhh'], $_POST['caf'], $_POST['cdo']);
      if ($modificacion==null || $modComentarios==null) {
        ?>
          <script type="text/javascript">
            alert('ERROR AL ACTUALIZAR LA ACTIVIDAD. INTENTELO DE NUEVO.');
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
  }else {
    ?>
      <script type="text/javascript">
        alert('ELEGIR LAS FECHAS ANTES DE CONTINUAR.');
        window.location='actividadesActuales.php';
      </script>
    <?php
  }
}


 ?>
 <?php } ?>
