<?php
require_once '../bbdd/personal.php';
$personal = new Personal();
//incuir clases para poder sacar el nombre del usuario.
require_once '../../ddbb/sesiones.php';
require_once '../../ddbb/users.php';
$usuario=new User();
$sesion=new Sesiones();
//sacar el nombre del usuario.
$nombreUsuario = $usuario->nombreUsuario($_SESSION['usuario']);

$error=0;

if ($_POST['turno'] == 'tm') {
  $turno = 'Mañana';
}elseif ($_POST['turno'] == 'tt') {
  $turno = 'Tarde';
}elseif ( $_POST['turno'] == 'tn') {
  $turno = 'Noche';
}

//guardar conductores
//borramos los datos anteriores.
$borrarCon = $personal -> eliminarPersonalTurno($_POST['conID'], $_POST['fecha'], $_POST['turno']);
if ($borrarCon != null && $borrarCon != false) {
  $recuento = count($_POST['con0']);
  for ($i=0; $i < $recuento; $i++) {
    $nuevo = $personal ->nuevoConductor($_POST['conID'], $_POST['con0'][$i], $_POST['turno'], $_POST['fecha'], $nombreUsuario['name'], $_POST['furgo0'][$i]);
    if ($nuevo == false || $nuevo == null) {
      $error =1;
    }
  }
}else {
  $error=1;
}

//guardar turnos normales
$recuento = ($_POST['normal']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['act'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], $_POST['turno']);
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['select'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, $_POST['turno'], $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }

}

//guardar turno central
$recuento = ($_POST['tc']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['tc'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'tc');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectTC'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'tc', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}

//guardar turno especial 1
$recuento = ($_POST['otro1']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['otro1'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'otro1');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectO1'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'otro1', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}

//guardar turno especial2
$recuento = ($_POST['otro2']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['otro2'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'otro2');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectO2'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'otro2', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}

//guardar turno especial 3
$recuento = ($_POST['otro3']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['otro3'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'otro3');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectO3'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'otro3', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}

//guardar turno especial 4
$recuento = ($_POST['otro4']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['otro4'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'otro4');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectO4'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'otro4', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}

//guardar turno especial 5
$recuento = ($_POST['otro5']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['otro5'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'otro5');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectO5'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'otro5', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}

//guardar turno especial 6
$recuento = ($_POST['otro6']);
for ($i=0; $i < $recuento ; $i++) {
  $act = $_POST['otro6'.$i];
  $id = $act[0];
    //borramos las entradas de esa actividad para ese dia y ese turno.
    $borrar = $personal -> eliminarPersonalTurno($id, $_POST['fecha'], 'otro6');
    if ($borrar != null || $borrar != false) {
      $nombres = ($_POST['selectO6'.$i]);
      foreach ($nombres as $nombre) {
        $nuevaEntrada = $personal -> nuevoPersonal($id, $nombre, 'otro6', $_POST['fecha'], $nombreUsuario['name']);
        if ($nuevaEntrada == false || $nuevaEntrada == null) {
          $error = 1;
        }
      }
    }else {
      $error = 1;
    }
}


if ($error==0) {
  $com = base64_encode($_POST['comentario']);
/*  echo $_POST['comentario'];
  $arreglar = str_replace('\n', '<br>', $_POST['comentario']);

  echo "<br>";
  echo $arreglar;
  echo "<br>";*/

  ?>
    <script type="text/javascript">
      alert('Personal confirmado');
      window.location = '../PDF/pdfSupervisor.php?fecha=<?php echo $_POST['fecha']; ?>&turno=<?php echo $turno; ?>&com=<?php echo $com ?>&u=<?php echo $_SESSION['usuario']; ?>';
    </script>
  <?php
}else {
  ?>
    <script type="text/javascript">
      alert('Algo salio mal');
      window.location = 'filtroSupervisores.php';
    </script>
  <?php
}
 ?>
