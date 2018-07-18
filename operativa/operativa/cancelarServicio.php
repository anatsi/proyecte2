<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Cancelar servicio</title>
  </head>
  <body>
    <?php
    $fecha=date('Y-m-d');

      include '../bbdd/servicio.php';
      $servicio= new Servicio();
      $cancelarServicio= $servicio->CancelarActividad($_GET['servicio'], $fecha);
      if ($cancelarServicio==true) {

        ?>
          <script type="text/javascript">
            window.location='actividadesActuales.php';
          </script>
        <?php
      }else {
        ?>
        <script type="text/javascript">
          alert('Ocurrio un error al cancelar el servicio.');
          window.location='actividadesActuales.php';
        </script>
        <?php
      }
     ?>
  </body>
</html>
