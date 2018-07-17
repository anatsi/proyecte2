<?php
//comprobamos si se ha enviado el empleado que se quiere desactivar
  if (isset($_GET['e'])) {
    //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './ddbb/empleados.php';
      $empleado= new Empleados();
      //llamamos a la funcion de desactivar el empleado
      $actualizar=$empleado->DesactivarEmpleado($_GET['e']);
      if ($actualizar==true) {
        //si se desactiva correctamente, le devolvemos a inicio
        header('Location: index.php');
      }else {
        //si no se puede desactivar avisamos del error
        echo "ERROR AL DESACTIVAR EL EMPLEADO";
      }
  }else {
    //si no se ha enviado el empleado a desactivar, avisamos al usuario
    echo "ERROR";
  }
 ?>
