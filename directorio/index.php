<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
$sesion= new Sesiones();
$usuario= new User();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Directorio empleados</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="../css/modificar.css">
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
               //hace la bÃºsqueda
                 $.ajax({
                     type: "POST",
                     url: "buscar.php",
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
  <span class="right"><a href="../logout.php" id="logout">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="../dashboard.php">Inicio</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%;">
          <a href="../dashboard.php">INICIO</a> >> <a href="index.php">DIRECTORIO EMPLEADOS</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>Directorio empleados</h2>
       <input type="text" id="busqueda" placeholder="FILTRAR"/><br /><br />
        <div id="resultado">
        <!--tabla-->
        <?php
        require_once '../ddbb/db2.php';
        $db= new db2();

          $consulta = "SELECT name, surname, email, tlf, movil, extMovil FROM users";
          $resultado = $db -> realizarConsulta($consulta);
          echo "
            <table id='tablamod'>
            <thead id='theadmod'>
              <tr id='trmod'>
                <th scope='col' id='thmod'>NOMBRE</th>
                <th scope='col' id='thmod'>CORREO</th>
                <th scope='col' id='thmod'>MOVIL</th>
                <th scope='col' id='thmod'>EXT. MOVIL</th>
                <th scope='col' id='thmod'>TLF. FIJO</th>
              </tr>
            </thead><tbody id='tbodymod'>

            "; while($fila = $resultado -> fetch_row()) {
              $nombre = $fila[0];
              $surname = $fila[1];
              $nombre = $nombre.' '.$surname;
              $email = $fila[2];
              $fijo = $fila[3];
              $movil = $fila[4];
              $ext_movil = $fila[5];
               echo "
                  <tr id='trmod'>
                    <td data-label='NOMBRE' id='tdmod'>".$nombre."</td>
                    <td data-label='CORREO' id='tdmod'>".$email."</td>
                    <td data-label='MOVIL' id='tdmod'><a href='tel:".$movil."'>".$movil."</a></td>
                    <td data-label='MOVIL' id='tdmod'><a href='tel:".$ext_movil."'>".$ext_movil."</a></td>
                    <td data-label='TLF FIJO' id='tdmod'><a href='tel:".$fijo."'>".$fijo."</a></td>
                  </tr>

            ";} echo "</tbody></table></div></body></html>";
         ?>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!--ORDENAR TABLA -->
<script type="text/javascript" src="../js/jquery.min.js"></script>
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
