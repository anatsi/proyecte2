<?php
//comprobamos si se ha enviado el empleado que se quiere desactivar
  if (isset($_GET['e'])) {
    //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './ddbb/empleados.php';
      $empleado= new Empleados();
      //incuir clases para poder sacar el nombre del usuario.
      require_once '../ddbb/sesiones.php';
      require_once '../ddbb/users.php';
      $usuario=new User();
      $sesion=new Sesiones();
      //sacar el nombre del usuario.
      $nombre = $usuario->nombreUsuario($_SESSION['usuario']);
      //llamamos a la funcion de desactivar el empleado
      $actualizar=$empleado->DesactivarEmpleado($_GET['e'], $nombre['name']);
      if ($actualizar==true) {
        //si se desactiva correctamente, le devolvemos a inicio
        ?>
        <script type="text/javascript">
          window.location='index.php';
        </script>
        <?php
      }else {
        //si no se puede desactivar avisamos del error
        ?>
          <script type="text/javascript">
            alert('Error al editar la informacion');
            window.location='index.php';
          </script>
        <?php
      }
  }else {
    //si no se ha enviado el empleado a desactivar, avisamos al usuario
    ?>
      <script type="text/javascript">
        window.location='index.php';
      </script>
    <?php
  }
 ?>
