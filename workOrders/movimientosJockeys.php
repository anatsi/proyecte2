<?php
//header("Refresh: 30; URL='movimientosJockeys.php'");

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './bbdd/movimientos.php';

$usuario=new User();
$sesion=new Sesiones();
$movimientos = new Movimientos();



if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>WORK ORDERS</title>
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
    <script src="../js/jquery.min.js"></script>
    <!--  script para el filtrado en la tabla  -->
    <script>
        $(document).ready(function(){
            var consulta;
            //hacemos focus al campo de busqueda
            $("#busqueda").focus();
            //comprobamos si se pulsa una tecla
            $("#busqueda").keyup(function(e){
              //obtenemos el texto introducido en el campo de busqueda
              consulta = $("#busqueda").val();
               //hace la busqueda
                 $.ajax({
                     type: "POST",
                     url: "buscarJockeys.php",
                     data: "b="+consulta,
                     dataType: "html",
                    /* beforeSend: function(){
                          //imagen de carga
                         $("#resultado").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                     },*/
                     error: function(){
                         console.log("Error en peticion");
                     },
                    success: function(data){
                      $("#resultado").empty();
                      $("#resultado").append(data);
                    }
                });
            });
        });

    </script>
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
        <a href="filtroMovimientos.php">Movimientos</a>
        <a href="registroWrap.php">Wrap Guard</a>
        <a href="filtroRoles.php">Roles</a>
        <a href="registroReallocation.php">Reallocation</a>
        <a href="registroRadio.php">Llaves</a>
        <a href="registroAullido.php">Aullido</a>
        <a href="registroClatter.php">Clatter</a>
        <a href="movimientosJockeys.php">Work orders</a>


      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>MOVIMIENTOS</h2>
       <input type="text" id="busqueda" placeholder='FILTRAR'/><br/><br/>

        <div id="resultado">
        <!--tabla-->
        <?php
        $lista= $movimientos->listaMovimientos();
        $recuentos= $movimientos -> cuentaListaMovimientos();

          echo "
          <h3>
          <a href='excelWorkOrders.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
          <img onclick='location.reload(true);' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGuSURBVEhL7dfNKwVRHMbxq7xHUhaWKCsWpKxF2SkrG4WFkGRth4WFyEIUKYoi/gFRZGFlJdlQyMJKyUbJ+/eZ26lpmmvOuXfMylOfmrndOb+5p/M7MzcVc/rRkj5MNie4QKF3llCq8YlvzOiDv0geVKgOtSjGKFRU3hHblFdgDMd4gSlivAbOL1GEnDKEJ/gHtpH1lJdiD2GD2tCUt8IpWpmHCBvQhfOUr8Fc/IEjTKEPPRjBAs7hLxTGesq7oAueMQ+t3N9yh2AxQzfchsjk4xq7UMtEpR7BYpohXe/UUt3oTR9aZRymoNpsGepv57hudwd4xDSq9EFSGYDa7j+xRlOq/brdO0sgWjST0CLS6lUL2aYRepo5RRvHIvxPI20WthnESvrQLk3YgTZ5U9BYRVRqsI83WM2OHujqy2AxvwlkSjO2YG5Ye7t1lhAsFnSFTWgfn8M2buD/jvboAlinDLfwD+LqDHpjcU4HvhA2aBStgRJkHa3GsIEzOUUnck457uEffBbr0OuQHntqtWG49LVV9AtMUb1pJJoNqLB2rkRTiQc0eGcJJ+Y/YqnUD0m40+Em89vZAAAAAElFTkSuQmCC'>
          </h3>

            <br>TOTAL MOVIMIENTOS: " .$recuentos[0]['recuento']."
            <table id='tablamod' class='zebra'>
            <thead id='theadmod'>
              <tr id='trmod'>
                <th scope='col' id='thmod' class='bastidor'>VIN</th>
                <th scope='col' id='thmod'>ORIGEN</th>
                <th scope='col' id='thmod'>F.ORIGEN</th>
                <th scope='col' id='thmod'>H.ORIGEN</th>
                <th scope='col' id='thmod'>DESTINO</th>
                <th scope='col' id='thmod'>F.DESTINO</th>
                <th scope='col' id='thmod'>H.DESTINO</th>
                <th scope='col' id='thmod'>PROD.</th>
                <th scope='col' id='thmod'>NO PROD.</th>
                <th scope='col' id='thmod'>CICLO</th>
                <th scope='col' id='thmod'>USER</th>
                <th scope='col' id ='thmod'>ROL</th>
                <th scope='col' id='thmod'>ERROR</th>
                <th scope='col' id='thmod'>LAUNCH</th>
              </tr>

            </thead><tbody id='tbodymod'>

            ";
              $cicloTotal = '00:00:00';
              $noprodTotal = '00:00:00';
              $prodTotal = '00:00:00';
            foreach ($lista as $movimiento) {
              $diferencia = $movimientos -> RestarHoras($movimiento['hora_origen'], $movimiento['hora_destino']);
              $siguienteMovimiento = $movimientos -> UltimoMovimiento($movimiento['usuario'], $movimiento['id']);
              if ($siguienteMovimiento != null && $siguienteMovimiento != false && $movimiento['error'] == 0) {
                $noproductivo = $movimientos -> RestarHoras($movimiento['hora_destino'], $siguienteMovimiento['hora_origen']);
                $ciclo = $movimientos -> SumarHoras($diferencia, $noproductivo);
                if ($noproductivo > '05:00:00') {
                  $noproductivo = '00:00:00';
                  $ciclo = '00:00:00';
                }else {
                  if ($cicloTotal != '00:00:00') {
                    $cicloTotal = $movimientos -> SumarHoras($ciclo, $cicloTotal);
                  }else {
                    $cicloTotal = $ciclo;
                  }
                  if ($prodTotal != '00:00:00') {
                    $prodTotal = $movimientos -> SumarHoras($prodTotal, $diferencia);
                  }else {
                    $prodTotal = $diferencia;
                  }
                  if ($noprodTotal != '00:00:00') {
                    $noprodTotal = $movimientos -> SumarHoras($noprodTotal, $noproductivo);
                  }else {
                    $noprodTotal = $noproductivo;
                  }
                }
              }else {
                $noproductivo = "";
                $ciclo = "";
              }

              //transformar fechas
              $inicio=explode("-", $movimiento['fecha_origen']);
              $inicio=$inicio[2]."-".$inicio[1]."-".$inicio[0];
              $fin=explode("-", $movimiento['fecha_destino']);
              $fin=$fin[2]."-".$fin[1]."-".$fin[0];
              //sacar si hay error o no
              if ($movimiento['error']==1) {
                $error = 'SI';
                $noproductivo = '00:00:00';
                $ciclo = '00:00:00';
              }else {
                $error = 'NO';
              }
               echo "
                  <tr id='trmod'>
                    <td data-label='VIN' id='tdmod' class='bastidor'>".$movimiento['bastidor']."</td>
                    <td data-label='ORIGEN' id='tdmod'>".$movimiento['origen']."</td>
                    <td data-label='FECHA ORIGEN' id='tdmod'>".$inicio."</td>
                    <td data-label='HORA ORIGEN' id='tdmod'>".$movimiento['hora_origen']."</td>
                    <td data-label='DESTINO' id='tdmod'>".$movimiento['destino']."</td>
                    <td data-label='FECHA DESTINO' id='tdmod'>".$fin."</td>
                    <td data-label='HORA DESTIO' id='tdmod'>".$movimiento['hora_destino']."</td>
                    <td data-label='PRODUCTIVO' id='tdmod'>".$diferencia."</td>
                    <td data-label='NO PORDUCTIVO' id='tdmod'>".$noproductivo."</td>
                    <td data-label='CICLO' id='tdmod'>".$ciclo."</td>

                    <td data-label='USUARIO' id='tdmod'>".$movimiento['usuario']."</td>
                    <td data-label='ROL' id='tdmod'>".$movimiento['rol']."</td>
                    <td data-label='ERROR' id='tdmod'>".$error."</td>
                    <td data-label='LANZAMIENTO' id='tdmod'>".$movimiento['lanzamiento']."</td>
                  </tr>";

                }
                echo "<tfoot>
                <tr id='trmod'>
                <th scope='col' id='thmod' class='bastidor'>TOTALES</th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'>".$prodTotal."</th>
                <th scope='col' id='thmod'>".$noprodTotal."</th>
                <th scope='col' id='thmod'>".$cicloTotal."</th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                <th scope='col' id='thmod'></th>
                </tr>
                </tfoot>";
                echo "</tbody></table></div>";
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
