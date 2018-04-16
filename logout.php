<?php
  //Reconocimiento idioma
  require('./languages/languages.php');
  	$lang = "es";
  if ( isset($_GET['lang']) ){
  	$lang = $_GET['lang'];
  }
  //incluimos el archivo encargado de las sesiones y creamos el objeto.
  include './ddbb/sesiones.php';
  $sesion= new Sesiones();

  //llamamos a la funcion que se encarga de destruir la sesion.
  $sesion->logOut();
  //una vez cerrada la sesion, te devuelve al formulario de inicio.
  ?>
  <script type="text/javascript">
  	window.location="index.php?lang=<?php echo $lang; ?>";
  </script>
  <?php
  //header("'Location: index.php?lang='.$lang'");
 ?>
