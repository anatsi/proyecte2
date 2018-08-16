<?php
//header("Refresh: 30; URL='movimientosJockeys.php'");

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './ddbb/ticket.php';

$usuario=new User();
$sesion=new Sesiones();
$ticket = new Ticket();


if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>TICKETS</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/modificar.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style media="screen">
      tr:nth-child(even) {
        background-color: #CAC6C5;
      }
    </style>
    <!--  script para el filtrado en la tabla  -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <!--Script para fijar la cabecera de las tablas-->
    <script type="text/javascript" src="../js/jquery.stickytableheaders.min.js"></script>
    <script type="text/javascript">
      $(function() {
        $("table").stickyTableHeaders();
      });
    </script>
</head>
<body>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
</head>

<div class="codrops-top clearfix">
  <?php
    //llamamos a la función para devolver el nombre de usuario.
    $nombreuser=$usuario->nombreUsuario($_SESSION['usuario']);
    //sacamos el nombre de usuario por su id
    echo "<a><strong>Bienvenido ".$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../logout.php" id="logout">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="index.php">Inicio</a>
      </nav>

    </header>


    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%;">
          <a href="../dashboard.php">INICIO</a> >> <a href="index.php">TICKETS</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>TICKETS</h2>
        <div id="resultado">
        <!--tabla-->
        <?php
        $lista= $ticket->listaTickets();

          echo "
          <h3>
          <img onclick='location.reload(true);' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGuSURBVEhL7dfNKwVRHMbxq7xHUhaWKCsWpKxF2SkrG4WFkGRth4WFyEIUKYoi/gFRZGFlJdlQyMJKyUbJ+/eZ26lpmmvOuXfMylOfmrndOb+5p/M7MzcVc/rRkj5MNie4QKF3llCq8YlvzOiDv0geVKgOtSjGKFRU3hHblFdgDMd4gSlivAbOL1GEnDKEJ/gHtpH1lJdiD2GD2tCUt8IpWpmHCBvQhfOUr8Fc/IEjTKEPPRjBAs7hLxTGesq7oAueMQ+t3N9yh2AxQzfchsjk4xq7UMtEpR7BYpohXe/UUt3oTR9aZRymoNpsGepv57hudwd4xDSq9EFSGYDa7j+xRlOq/brdO0sgWjST0CLS6lUL2aYRepo5RRvHIvxPI20WthnESvrQLk3YgTZ5U9BYRVRqsI83WM2OHujqy2AxvwlkSjO2YG5Ye7t1lhAsFnSFTWgfn8M2buD/jvboAlinDLfwD+LqDHpjcU4HvhA2aBStgRJkHa3GsIEzOUUnck457uEffBbr0OuQHntqtWG49LVV9AtMUb1pJJoNqLB2rkRTiQc0eGcJJ+Y/YqnUD0m40+Em89vZAAAAAElFTkSuQmCC'>
          </h3>
            <table id='tablamod'>
            <thead id='theadmod'>
              <tr id='trmod'>
                <th scope='col' id='thmod'>Nº</th>
                <th scope='col' id='thmod'>ASUNTO </th>
                <th scope='col' id='thmod'>MENSAJE </th>
                <th scope='col' id='thmod'>USUARIO</th>
                <th scope='col' id='thmod'>FECHA</th>
                <th scope='col' id='thmod'>HORA</th>
                <th scope='col' id='thmod'>RESUELTO</th>
                <th scope='col' id='thmod'>COMENTARIO</th>
              </tr>
            </thead><tbody id='tbodymod'>

            "; foreach ($lista as $tickets) {
              //transformar fechas
              $fecha=explode("-", $tickets['fecha']);
              $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
              //arreglamos el campo resuelto.
              if ($tickets['resuelto'] != '' && $tickets['resuelto']!= null) {
                $resuelto = $tickets['resuelto'];
              }else {
                $resuelto = "<a href='resolver.php?id=".$tickets['id']."'>Pendiente</a>";
              }
               echo "
                  <tr id='trmod'>
                    <td data-label='Nº' id='tdmod'>".$tickets['id']."</td>
                    <td data-label='ASUNTO' id='tdmod'>".$tickets['asunto']."</td>
                    <td data-label='MENSAJE' id='tdmod'>".$tickets['mensaje']."</td>
                    <td data-label='USUARIO' id='tdmod'>".$tickets['usuario']."</td>
                    <td data-label='FECHA' id='tdmod'>".$fecha."</td>
                    <td data-label='HORA' id='tdmod'>".$tickets['hora']."</td>
                    <td data-label='RESUELTO' id='tdmod'>".$resuelto."</td>
                    <td data-label='COMENTARIO' id='tdmod'>".$tickets['comentario']."</td>
                  </tr>

            ";} echo "</tbody></table></div>";
         ?>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!--ORDENAR TABLA
<script type="text/javascript" src="../js/jquery.min.js"></script> -->
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
<script>
$(function(){
  $("#tablamod").tablesorter();
});
</script>

<!-- Scripts para que el menu en versión movil funcione
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script  src="../js/menu.js"></script>

</body>
</html>
 <?php } ?>
