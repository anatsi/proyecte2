<?php
  //incluimos el archivo encargado de las sesiones y creamos el objeto.
  include 'sesiones.php';
  $sesion= new Sesiones();

  //llamamos a la funcion que se encarga de destruir la sesion.
  $sesion->logOut();
  //una vez cerrada la sesion, te devuelve al formulario de inicio.
  header('Location: index.php');
 ?>
