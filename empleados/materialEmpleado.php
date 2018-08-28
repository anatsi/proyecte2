<?php

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './ddbb/empleados.php';
require_once './ddbb/incapacidad.php';
require_once './ddbb/fechas.php';
require_once './ddbb/material.php';


$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();
$incapacidad = new Incapacidad();
$fecha = new Fechas();
$material= new Material();


// Codigo para realimentción de la pagina

//comprobamos si la sesion esta iniciada
if (isset($_SESSION['usuario'])==false) {
  //si no esta iniciada, llevamos al usuario a la pagina de inicio de sesion
  header('Location: ../index.php');
}else{
 ?>
<!DOCTYPE html>

<html >
<head>
  <meta charset="UTF-8">
  <title>Gestión empleados</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/modificar.css">
    <!-- CSS para alternar los colores de la tabla -->
    <style media="screen">
      tr:nth-child(even) {
        background-color: #CAC6C5;
      }
    </style>

    <!--  Libreria iconos  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--  script para el filtrado en la tabla  -->
    <script src="../js/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            var consulta;
            //hacemos focus al campo de busqueda
            $("#busqueda").focus();
            //comprobamos si se pulsa una tecla
            $("#busqueda").keyup(function(e){
              //obtenemos el texto introducido en el campo de bÃºsqueda
              consulta = $("#busqueda").val();
               //hace la busqueda
                 $.ajax({
                     type: "POST",
                     url: "buscarMaterial.php",
                     data: "b="+consulta,
                     dataType: "html",
                     beforeSend: function(){
                          //imagen de carga
                         $("#resultado").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                     },
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
    <!--Script para fijar la cabecera de las tablas-->
    <script type="text/javascript" src="../js/jquery.stickytableheaders.min.js"></script>
    <script type="text/javascript">
      $(function() {
        $("table").stickyTableHeaders();
      });
    </script>
    <!--ORDENAR TABLA-->
    <!--<script type="text/javascript" src="../js/jquery.min.js"></script>-->
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
    <script>
    $(function(){
      $("#tablamod").tablesorter();
    });
</script>

</head>

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
  <span class="right"><a href="../logout.php" id='logout'>Cerrar sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="index.php">Inicio</a>
        <a href="listaFechas.php">Fechas</a>
        <a href="materialEmpleado.php">Material</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%;">
          <a href="../dashboard.php">INICIO</a> >> <a href="index.php">GESTIÓN EMPLEADOS</a>
          >> <a href="fechas.php">FECHAS</a> >> <a href="materialEmpleado.php">MATERIAL ENTREGADO</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>Consultas de material </h2>
         <h3><a href="excelGestion.php" title="Exportar todo a excel"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=="></a></h3>
        <input type="text" id="busqueda" placeholder='FILTRAR POR NOMBRE'/><br /><br />
        <div id="resultado">
        <table id="tablamod">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod">Nombre</th>
            <th scope="col" id="thmod">Material</th>
            <th scope="col" id="thmod">Fecha de entrega</th>
            <th scope="col" id="thmod">Talla</th>
            <th scope="col" id="thmod">Cantidad</th>


      </tr>
        </thead>
        <tbody id="tbodymod">
          <?php
           //llamamos a la consulta de listar todas las fechas
            //$listaFecha=$material->sacar();
          //llamamos a la consulta de listar material
            $listarMaterial= $material->listaMaterial();
         //sacamos los empleados por pantalla en una tabla
              foreach ($listarMaterial as $mater){
                 //Sacamos el numero del material para pasar la fonction que le convierte
                $change=$material->ConverseMaterial($mater['materiales']);

                  //Sacamos el id del empleado para sacar su nombre
                $changeEmpleo=$empleado->EmpleadoId($mater['empleado']);

                $Date = date("d/m/Y", strtotime($mater['fecha_entrega'] ));
                //Al crear material la fecha se queda en blanco
                $fecha="";


                echo "<tr id='trmod'>";
                 //Cargar los valores de la base de datos sobre la tabla
                 //Cambiar el id al empleado corresondete y concater nombre y apellidos
                echo "<td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$mater['empleado']."' title='Editar información del empleado'>".$changeEmpleo['nombre']." ".$changeEmpleo['apellidos']."</a></td>";
                 //Cambiar de numero al material correspondete
                echo "<td data-label='telefono' id='tdmod'>".$change['tipo_material']."</td>";
                echo "<td data-label='cadPassFord' id='tdmod'>".$Date."</td>";
                //Sacar de material  talla y cantidad
                echo "<td data-label='revMedico' id='tdmod'>".$mater['talla']."</td>";
                echo "<td data-label='cadPerm' id='tdmod'>".$mater['cantidad']."</td>";


                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación


           }

        ?>
        </tbody>
      </table>
    </div><!--END resultado -->
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->


<!-- Scripts para que el menu en versión movil funcione
<script src="../js/jquery.min.js"></script>-->
<script  src="../js/menu.js"></script>

</body>
</html>
 <?php }?>
