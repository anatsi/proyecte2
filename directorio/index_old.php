<!DOCTYPE html>
<!--
#######################################################
#         TSI - Employment Directory. Version 1       #
#                                                     #
# Author: Vicente Catala                              #
# Date: 06/11/2017                                    #
#                                                     #
#######################################################
-->
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Directorio empleados</title>
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
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans'>
  <link rel="stylesheet" href="../css/tabla.css">
</head>

<body>
  <h1>DIRECTORIO EMPLEADOS</h1><br>
    Buscar: <input type="text" id="busqueda"/><br /><br />
    <div id="resultado">

<?php
  //MySQLi
  require_once '../db2.php';
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
          <th scope='col' id='thmod'>EXT.MOVIL</th>
          <th scope='col' id='thmod'>TLF FIJO</th>
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
