<?php

//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './ddbb/empleados.php';
require_once './ddbb/incapacidad.php';

$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();
$incapacidad = new Incapacidad();

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
                     url: "buscarEmpleados.php",
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
          <a href="../dashboard.php">Inicio</a> >> <a href="index.php">Gestión empleados</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>Gestión de empleados</h2>

        <input type="text" id="busqueda" placeholder='FILTRAR'/><br /><br />

         <div id="resultado">
           <h3><a title='Añadir empleado' href="nuevoEmpleado.php"><i class="material-icons" id="nuevoEmpleado">group_add</i></a>
           <?php
           echo "
           <a href='excelEmpleados.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
           </h3>";
            ?>
        <table id="tablamod">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod">Nombre</th>
            <th scope="col" id="thmod">Apellidos</th>
            <th scope="col" id="thmod">User</th>
            <th scope="col" id="thmod">Telefono</th>
            <th scope="col" id="thmod">RRHH</th>
            <th scope="col" id="thmod">Fecha mod.</th>
            <th scope="col" id="thmod">Alta</th>
            <th scope="col" id="thmod">Vacaciones</th>
            <th scope="col" id="thmod">Incapacidad</th>
            <th scope="col" id="thmod">Opciones</th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <?php
          //llamamos a la consulta de listar todos los empleados
            $listaempleados=$empleado->listaEmpleados();
         //sacamos los empleados por pantalla en una tabla
              foreach ($listaempleados as $empleados) {
                //Sacamos el numero de la incapacidad para pasar la fonction que le converte
                $change=$incapacidad->ConverseIncapacidadId($empleados['incapa_temporal']);
                $newDate = date("d/m/Y H:i:s", strtotime($empleados['fecha_mod'] ));
                  //Al crear un nuevo usuario la fecha se queda en blanco
                $fecha="";

                echo "<tr id='trmod'>";
                 //Cargar los valores de la base de datos sobre la tabla

                echo "<td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$empleados['id']."' title='Editar información del empleado'>".$empleados['nombre']."</a></td>";
                echo "<td data-label='Apellidos' id='tdmod'>".$empleados['apellidos']."</td>";
                echo "<td data-label='User' id='tdmod'>".$empleados['user']."</td>";
                echo "<td data-label='Telefono' id='tdmod'>".$empleados['telefono']."</td>";
                echo "<td data-label='RRHH' id='tdmod'>".$empleados['usuario_mod']."</td>";
                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación
                if($empleados['fecha_mod']!=null){
                echo "<td data-label='Fecha mod.' id='tdmod'>".$newDate."</td>";
                }else{
                echo "<td data-label='Fecha mod.' id='tdmod'>".$fecha."</td>";
                }
                 //Cerrar echo y buscar los valores de alta dónde está 1 o 0 cambiar por clear o done
                if ($empleados['alta']==0){
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>done</i></td>";
                }else{
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>clear</i> </td>";
                }
                //Cerrar echo y buscar los valores de vacacione dónde está 1 o 0 cambiar por clear o done
                if ($empleados['vacaciones']==0 && $empleados['alta']==0){
                echo "<td data-label='Vacaciones' id='tdmod'><i class='material-icons'>clear</i> </td>";
               }else{
                echo "<td data-label='Vacaciones' id='tdmod'><i class='material-icons'>done</i> </td>";
               }
                echo "<td data-label='Incapacidad' id='tdmod'>".$change['tipo']."</td>";


              //Poner icones para cada Opciones // labe=opciones tienen que estar en la primera opcion

              if ($empleados['alta']==0){
                echo "<td data-label='Opciones ' id='tdmod'>";
                echo "<a href='desactivarEmpleado.php?e=".$empleados['id']."'
                title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
                //Poner icono de avion y maleta para vaciones y solo si está dado de alta
                if ($empleados['vacaciones']==0) {
                  echo "&nbsp;&nbsp;<a href='deVacaciones.php?e=".$empleados['id']."' title='Dar vacaciones'><i class='material-icons'>flight</i></a>";
                }else {
                  echo"&nbsp;&nbsp;<a href='deVacaciones.php?e=".$empleados['id']."' title='Poner fin a las Vacaciones '><i class='material-icons'>work</i></a>";
                }
                // poner icono de incapacidad temporal  y solo si está dado de alta
               if ($empleados['incapa_temporal']==0){
                     echo "&nbsp;&nbsp;<a href='darBaja.php?e=".$empleados['id']."' title='Incapacidad Temporal'><i class='material-icons'>healing</i></a>";
               }else{
                     echo"&nbsp;&nbsp;<a href='incapacidad.php?e=".$empleados['id']."' title='Dar de alta'><i class='material-icons'>sentiment_satisfied_alt</i></a></td></tr>";
               }
              }else {
                echo "<td data-label='Opciones ' id='tdmod'>";
                echo"&nbsp;&nbsp;<a href='activarEmpleado.php?e=".$empleados['id']."' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
              }
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
