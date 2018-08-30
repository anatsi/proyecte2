<?php
  require_once '../bbdd/responsable.php';
  $responsable = new Responsable();

  $nuevo = $responsable->nuevoResponsable($_POST['nombre'], $_POST['telefono'], $_POST['correo']);

  if ($nuevo==false || $nuevo==null) {
    ?>
      <script type="text/javascript">
        alert('Error al crear el responsable');
        window.location='nuevoCliente.php';
      </script>
    <?php
  }else {
    ?>
      <script type="text/javascript">
        window.location ='nuevoCliente.php';
      </script>
    <?php
  }

 ?>
