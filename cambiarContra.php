<?php
  //incluimos el archivo encargado de los usuarios.
  require_once './ddbb/sesiones.php';
  require_once './ddbb/users.php';

  $usuario = new User();
  $sesion = new Sesiones();

//comprobamos que se ha rellenado los dos campos del formulario
  if (isset($_POST['form-username']) && isset($_POST['form-password'])) {
    //encriptar la contraseña
    $salt='$tsi$/';
    $contra = sha1(md5($salt . $_POST['form-password']));
    //cambiar la contraseña llamando a la funcion del archivo user.php
    $nuevaPass = $usuario -> cambiarContra($contra, $_SESSION['usuario']);
    if ($nuevaPass == null || $nuevaPass == false) {
      //si no se ha cambiado bien, redirigimos al formulario de inicio de sesion
      ?>
      <script type="text/javascript">
        window.location = 'index.php';
      </script>
      <?php
    }else {
      //si se ha cambiado bien, vams a la pagina principal
      ?>
      <script type="text/javascript">
        window.location = 'dashboard.php';
      </script>
      <?php
    }
  }

 ?>
