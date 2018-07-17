<?php
//incluimos los archivos necesarios e inicializamos los objetos
  require_once './bbdd/empleados.php';
  $empleados = new Empleados();

  //comprobamos si la variable e existe, para saber si viene del archivo de editar o de insertar
  if ($_GET['e'] && $_GET['e'] == 1) {
    //si viene del archivo de editar, borramos promero el personal de ese dia para esa actividad
    $borrarPersonal = $empleados -> eliminarPersonal($_POST['id'], $_POST['dia']);
    //si nos da error avisamos de que vuelvan a empezar
    if ($borrarPersonal == null || $borrarPersonal == false) {
      ?>
        <script type="text/javascript">
          alert('Algo salio mal, intentalo más tarde');
          window.location = 'filtroRRHH.php';
        </script>
      <?php
    }
  }

//inicializamos variables para saber si algun insert nos da error
  $error = 0;

//para cada persona seleccionada en los selects, hacemos un insert a la bbdd
//si alguna de las personas da error, se pondran la variable de arriba que toque a 1
  if ($_POST['noche']) {
    foreach ($_POST['noche'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'tn', $_POST['dia']);
    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['morning']) {
    foreach ($_POST['morning'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'tm', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['tarde']) {
    foreach ($_POST['tarde'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'tt', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['central']) {
    foreach ($_POST['central'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'tc', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['otro1']) {
    foreach ($_POST['otro1'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'otro1', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['otro2']) {
    foreach ($_POST['otro2'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'otro2', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['otro3']) {
    foreach ($_POST['otro3'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'otro3', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['otro4']) {
    foreach ($_POST['otro4'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'otro4', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['otro5']) {
    foreach ($_POST['otro5'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'otro5', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  if ($_POST['otro6']) {
    foreach ($_POST['otro6'] as $personalNoche) {
      $nuevoEmpleado = $empleados -> nuevoPersonal($_POST['id'], $personalNoche, 'otro6', $_POST['dia']);

    }
    if ($nuevoEmpleado == false || $nuevoEmpleado == null) {
      $error = 1;
    }
  }

  //si alguna de las variables estava puesto a 1, avisamos de que no se ha insertado bien
  if ($error == 1) {
    ?>
      <script type="text/javascript">
        alert('Algo salio mal, intentalo más tarde');
        window.location = 'filtroRRHH.php';
      </script>
    <?php
    //si todo esta bien, le devolvemos a la pantalla con las actividades del dia seleccionado
  }else {
    echo "<script type='text/javascript'>";
    echo "window.location='tablaRRHH.php?fecha=".$_POST['dia']."'";
    echo "</script>";
  }
 ?>