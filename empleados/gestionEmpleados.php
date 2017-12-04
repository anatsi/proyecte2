<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once 'empleados.php';

$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
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
    <script type="text/javascript" src="../js/servicioForm.js"></script>
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
        <a href="gestionEmpleados.php">Inicio</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Gestión de empleados</h2>
        <h3><a title='Añadir empleado' href="#"><i class="material-icons" id="nuevoEmpleado">group_add</i></a></h3>
        <input type="text" id="busqueda" placeholder='FILTRAR'/><br /><br />
         <div id="resultado">
        <table id="tablamod">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod">Nombre</th>
            <th scope="col" id="thmod">Apellidos</th>
            <th scope="col" id="thmod">Activo</th>
            <th scope="col" id="thmod">Opciones</th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <?php
            $listaempleados=$empleado->listaEmpleados();
            foreach ($listaempleados as $empleados) {
              echo "<tr id='trmod'>";
              echo "<td scope='row' data-label='Nombre' id='tdmod'>".$empleados['nombre']."</td>";
              echo "<td data-label='Apellidos' id='tdmod'>".$empleados['apellidos']."</td>";
              if ($empleados['activo']==1) {
                echo "<td data-label='Activo' id='tdmod'><i class='material-icons'>done</i></td>";
                echo "<td data-label='Opciones' id='tdmod'>";
                echo "<a href='desactivarEmpleado.php?e=".$empleados['id']."' title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
              }else {
                echo "<td data-label='Activo' id='tdmod'></td>";
                echo "<td data-label='Opciones' id='tdmod'>";
                echo"<a href='activarEmpleado.php?e=".$empleados['id']."' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
              }
              echo "<a href='' title='Editar información'><i class='material-icons'>mode_edit</i></a></td></tr>";
            }
           ?>
        </tbody>
      </table>
    </div><!--END resultado -->
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../js/menu.js"></script>

</body>
</html>
 <?php } ?>
