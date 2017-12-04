<?php
  if (isset($_GET['e'])) {
      require_once 'empleados.php';
      $empleado= new Empleados();

      $actualizar=$empleado->DesactivarEmpleado($_GET['e']);
      if ($actualizar==true) {
        header('Location: gestionEmpleados.php');
      }else {
        echo "ERROR AL DESACTIVAR EL EMPLEADO";
      }
  }else {
    echo "ERROR";
  }
 ?>
