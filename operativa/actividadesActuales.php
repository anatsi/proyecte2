<?php
//Reconocimiento idioma
require('./languages/languages.php');
  $lang = "es";
if ( isset($_GET['lang']) ){
  $lang = $_GET['lang'];
}
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once 'cliente.php';
require_once 'servicio.php';
require_once 'recursos.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();
$recursos=new Recursos();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo __('Actividades actuales', $lang); ?></title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/modificar.css">
    <script type="text/javascript" src="../js/servicioForm.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
    echo "<a><strong>".__('Bienvenido ', $lang).$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../logout.php" id='logout'><?php echo __('Cerrar Sesion', $lang); ?></a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">
    <header class="header">
      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php?lang=<?php echo $lang; ?>" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>
      <nav class="menu">
        <a href="index.php?lang=<?php echo $lang; ?>"><?php echo __('Inicio', $lang); ?></a>
        <a href="nuevoServicio.php?lang=<?php echo $lang; ?>"><?php echo __('Nueva actividad', $lang); ?></a>
        <a href="actividadesActuales.php?lang=<?php echo $lang; ?>"><?php echo __('Actividades actuales', $lang); ?></a>
        <a href="historicoActividades.php?lang=<?php echo $lang; ?>"><?php echo __('Historico actividades', $lang); ?></a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2><?php echo __('Actividades actuales', $lang); ?></h2>
        <table id="tablamod">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod"><?php echo __('Actividad', $lang); ?></th>
            <th scope="col" id="thmod"><?php echo __('Modelos', $lang); ?></th>
            <th scope="col" id="thmod"><?php echo __('Fecha inicio', $lang); ?></th>
            <th scope="col" id="thmod"><?php echo __('Cliente', $lang); ?></th>
            <th scope="col" id="thmod"><?php echo __('Opciones', $lang); ?></th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <?php
            $listamodificar=$servicio->listaServiciosHoy();
            foreach ($listamodificar as $lista) {
              //transformamos la fecha
              $fecha=explode("-", $lista['f_inicio']);
              $fechaHoy=$fecha[2]."-".$fecha[1]."-".$fecha[0];
              $clientes=$cliente->ClienteId($lista['id_cliente']);
              echo "<tr id='trmod'>";
              echo "<td scope='row' data-label='".__('Actividad ', $lang)."' id='tdmod'><a id='timeline' href='timeline.php?servicio=".$lista['id']."'>".$lista['descripcion']."</a></td>";
              echo "<td data-label='".__('Modelos ', $lang)."' id='tdmod'>".$lista['modelos']."</td>";
              echo "<td data-label='".__('Fecha inicio ', $lang)."' id='tdmod'>".$fechaHoy."</td>";
              echo "<td data-label='".__('Cliente ', $lang)."' id='tdmod'>".$clientes['nombre']."</td>";
              echo "  <td data-label='".__('Opciones ', $lang)."' id='tdmod'>
              <a href='modificarRecursos.php?servicio=".$lista['id']."' title='Modificar recursos actividad'><i class='material-icons'>people</i></a>
              <a href='modificarInfo.php?servicio=".$lista['id']."' title='Modificar información actividad'><i class='material-icons'>mode_edit</i></a>
              <a href='finalizarServicio.php?servicio=".$lista['id']."' title='Finalizar actividad'><i class='material-icons'>power_settings_new</i></a></td>";
            }
           ?>
        </tbody>
      </table>
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
