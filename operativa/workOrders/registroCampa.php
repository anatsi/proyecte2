<?php
header("Refresh: 30; URL='movimientosJockeys.php'");

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../../sesiones.php';
require_once '../../users.php';
require_once 'campa.php';

$usuario=new User();
$sesion=new Sesiones();
$campa = new Campa();


if (isset($_SESSION['usuario'])==false) {
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>CAMPA</title>
    <link rel="stylesheet" href="../../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../../css/formulario.css">
    <link rel="shortcut icon" href="../../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
    <link rel="stylesheet" href="../../css/modificar.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--  script para el filtrado en la tabla  -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            var consulta;
            //hacemos focus al campo de busqueda
            $("#busqueda").focus();
            //comprobamos si se pulsa una tecla
            $("#busqueda").keyup(function(e){
              //obtenemos el texto introducido en el campo de busqueda
              consulta = $("#busqueda").val();
               //hace la bÃºsqueda
                 $.ajax({
                     type: "POST",
                     url: "buscarCampa.php",
                     data: "b="+consulta,
                     dataType: "html",
                    /* beforeSend: function(){
                          //imagen de carga
                         $("#resultado").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                     },*/
                     error: function(){
                         alert("Error en peticion");
                     },
                    success: function(data){
                      $("#resultado").empty();
                      $("#resultado").append(data);
                    }
                });
            });
        });

    </script>
    <!-- jquery para alternar el color de las filas de la tabla -->
    <script type="text/javascript">
      $(document).ready(function(){
        $("tr:odd").css("background-color", "#CAC6C5"); // filas pares
        $("tr:even").css("background-color", "#f8f8f8"); // filas impares
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
  <span class="right"><a href="../../logout.php" id="logout">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../../dashboard.php" class="header__logo"><img src="../../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="movimientosJockeys.php">Work order</a>
        <a href="registroCampa.php">Campa</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>CAMPA</h2>
       <input type="text" id="busqueda" placeholder='FILTRAR'/><br/><br/>
        <div id="resultado">
        <!--tabla-->
        <?php
        $lista= $campa->listaCampa();

          echo "
          <h3><a href='excelCampa.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a></h3>
            <table id='tablamod'>
            <thead id='theadmod'>
              <tr id='trmod'>
                <th scope='col' id='thmod'>BASTIDOR</th>
                <th scope='col' id='thmod'>FECHA </th>
                <th scope='col' id='thmod'>HORA </th>
                <th scope='col' id='thmod'>USUARIO</th>
              </tr>
            </thead><tbody id='tbodymod'>

            "; foreach ($lista as $campas) {
              //transformar fechas
              $inicio=explode("-", $campas['fecha']);
              $inicio=$inicio[2]."-".$inicio[1]."-".$inicio[0];
               echo "
                  <tr id='trmod'>
                    <td data-label='BASTIDOR' id='tdmod' class='bastidor'>".$campas['bastidor']."</td>
                    <td data-label='FECHA ORIGEN' id='tdmod'>".$inicio."</td>
                    <td data-label='HORA ORIGEN' id='tdmod'>".$campas['hora']."</td>
                    <td data-label='USUARIO' id='tdmod'>".$campas['usuario']."</td>
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
<script  src="../../js/menu.js"></script>

</body>
</html>
 <?php } ?>
