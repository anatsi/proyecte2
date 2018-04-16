<?php
//comprobamos si se ha enviado el empleado que se quiere activar
  if (isset($_GET['e'])) {
    //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './bbdd/empleados.php';
      $empleado= new Empleados();
      //llamamos a la consulta de activar el empleado
      $actualizar=$empleado->ActivarEmpleado($_GET['e']);
      if ($actualizar==true) {
        //si se activa correctamente, lo devolvemos a index
        header('Location: index.php');
      }else {
        //si se activa mal, le avisamos
        echo "ERROR AL ACTIVAR EL EMPLEADO";
      }
  }else {
    //si no se ha enviado el empleado que se quiere activar, avisamos
    echo "ERROR";
  }
 ?>
