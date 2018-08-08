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

$recuento = (count($_POST)-1)/2;
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
          ?>
            <script type="text/javascript">
              alert('Algo salio mal.');
              window.location = 'filtroSupervisores.php';
            </script>
          <?php
        }else {
          ?>
            <script type="text/javascript">
              alert('Personal confirmado');
              window.location = 'filtroSupervisores.php';
            </script>
          <?php
        }
      }
    }else {
      ?>
        <script type="text/javascript">
          alert('Algo salio mal al borrar.');
          window.location = 'filtroSupervisores.php';
        </script>
      <?php
    }

}

 ?>
