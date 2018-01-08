<?php
  if (isset($_GET['e'])) {
      require_once 'empleados.php';
      $empleado= new Empleados();

      $actualizar=$empleado->ActivarEmpleado($_GET['e']);
      if ($actualizar==true) {
        header('Location: index.php');
      }else {
        echo "ERROR AL ACTIVAR EL EMPLEADO";
      }
  }else {
    echo "ERROR";
  }
 ?>
