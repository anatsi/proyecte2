<?php
//comprobamos si se ha enviado el empleado que se quiere activar
  if (isset($_GET['e'])) {
    //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './ddbb/empleados.php';
      $empleado= new Empleados();
      //llamamos a la consulta de activar el empleado
      $actualizar=$empleado->ActivarEmpleado($_GET['e']);
      if ($actualizar==true) {
        //si se activa correctamente, lo devolvemos a index
        header('Location: index.php');
      }else {
        //si se activa mal, le avisamos
        ?>
          <script type="text/javascript">
            alert('Error al activar el empleado!');
            window.location='index.php';
          </script>
        <?php
      }
  }else {
    //si no se ha enviado el empleado que se quiere activar, lo devolvemos a index.
    ?>
      <script type="text/javascript">
        window.location='index.php';
      </script>
    <?php
  }
 ?>
