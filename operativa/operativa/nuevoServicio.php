<?php
//Reconocimiento idioma
require('../languages/languages.php');
  $lang = "es";
if ( isset($_GET['lang']) ){
  $lang = $_GET['lang'];
}
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../../ddbb/sesiones.php';
require_once '../../ddbb/users.php';
require_once '../bbdd/cliente.php';
require_once '../bbdd/servicio.php';
require_once '../bbdd/recursos.php';

$usuario=new User();
$sesion=new Sesiones();
$cliente=new Cliente();
$servicio=new Servicio();
$recursos=new Recursos();

if (isset($_SESSION['usuario'])==false) {
  header('Location: ../../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo __('Nueva actividad', $lang); ?></title>
    <link rel="stylesheet" href="../../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../../css/formulario.css">
    <link rel="shortcut icon" href="../../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
    <script type="text/javascript" src="../../js/servicioForm.js"></script>
    <link href="../../css/fSelect.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/fSelect.js"></script>
    <script>
      (function($) {
          $(function() {
              $('.test').fSelect();
          });
      })(jQuery);
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
    echo "<a><strong>".__('Bienvenido ', $lang).$nombreuser['name']."</strong></a>";
   ?>
  <span class="right"><a href="../../logout.php" id="logout"><?php echo __('Cerrar Sesion', $lang); ?></a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../../dashboard.php?lang=<?php echo $lang; ?>" class="header__logo"><img src="../../imagenes/logo.png" alt=""></a>

      <nav class="menu">
        <a href="../index.php?lang=<?php echo $lang; ?>"><?php echo __('Inicio', $lang); ?></a>
        <?php
        $menu=$usuario->menuDash($_SESSION['usuario']);
        $opciones = explode(",", $menu['menu']);
        foreach ($opciones as $opcion) {
          if ($opcion == 21) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
          }elseif ($opcion == 22) {
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';

          }elseif ($opcion == 23) {
            echo '<a href="../supervisores/filtroSupervisores.php">Supervisores</a>';

          }elseif ($opcion == 0) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente</a>";
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
            echo '<a href="../supervisores/filtroSupervisores.php">Supervisores</a>';
          }
        }
         ?>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2><?php echo __('Nueva actividad', $lang); ?></h2>
        <form action="nuevoServibbdd.php" method="post" id="formulario" enctype="multipart/form-data">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Actividad', $lang); ?> (*)</label><input type="text" name="descripcion" required/></p>
              <p><label><i class="fa fa-question-circle"></i>Descripción actividad</label><textarea name="cdo"></textarea></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Modelos', $lang); ?></label>
                <select name="sel[]" class="test" multiple="multiple" id='multiple'>
                        <option value='MONDEO'>MONDEO</option>
                        <option value='KUGA'>KUGA</option>
                        <option value='TRANSIT CONNECT'>CONNECT</option>
                        <option value='GALAXY'>GALAXY</option>
                        <option value='S-MAX'>S-MAX</option>
                </select></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Fecha inicio', $lang); ?> (*)</label><input type="date" name="finicio" required/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Cliente', $lang); ?></label>
                <select name="cliente">
                  <?php
                    $clientes= $cliente->listaClientes();
                    foreach ($clientes as $cliente) {
                      echo "<option value=".$cliente['id'].">".$cliente['nombre']."</option>";
                    }
                   ?>
                </select></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Responsable', $lang); ?></label><input type="text" name="responsable"/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Tel. responsable', $lang); ?></label><input type="tel" name="telefono"/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Correo responsable', $lang); ?></label><input type="email" name="correo"/></p>
          </div>
          <div class="formthird" id='contenedor'>
            <p><label><i class="fa fa-question-circle"></i><?php echo __('Personas (aprox.)', $lang); ?></label><input type="number" min='0' id="calculo" readonly/></p>
            <p><label><i class="fa fa-question-circle"></i><?php echo __('Ciclo (segundos)', $lang); ?></label><input type="number" min='0'id="segundos"/></p>
            <p><label><i class="fa fa-question-circle"></i><?php echo __('Nº coches', $lang); ?></label><input type="number" min='0'id="coches"/></p>
            <button type="button" name="button" onclick="calculoPersonas();"><?php echo __('Calcular', $lang); ?></button>

              <p><label><i class="fa fa-question-circle"></i><?php echo __('Recursos totales', $lang); ?> (*)</label><input type="number" min='0' name="recursos" id="total" value=0 readonly/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Turno mañana', $lang); ?></label><input type="number" min='0' name="tm" id="tm" value='0' onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Turno tarde', $lang); ?></label><input type="number" min='0' name="tt" id="tt" value='0' onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Turno noche', $lang); ?></label><input type="number" min='0'name="tn" id="tn" value='0' onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Turno central', $lang); ?></label><input type="number" min='0'name="tc" id="tc" value='0' onclick="suma();" onkeyup="suma();"/></p>

              <button type="button" name="button" id="nuevoServicio" onclick="nuevo();"><?php echo __('Añadir otro horario', $lang); ?></button>
              <p id="enviar"></p>
          </div>
          <div class="formthird">
            <p><label><i class="fa fa-question-circle"></i>QPS</label></p>
              <p><input type='file' name='archivo1' value='archivo1' id='archivo1'></p>
              <p><input type='file' name='archivo2' value='archivo2' id='archivo2'></p>
            <p><label><i class="fa fa-question-circle"></i>IMAGENES</label></p>
              <p><input type='file' name='archivo3' value='archivo3' id='archivo3'></p>
              <p><input type='file' name='archivo4' value='archivo4' id='archivo4'></p>
            <p><label><i class="fa fa-question-circle"></i>VIDEOS</label></p>
              <p><input type='file' name='archivo5' value='archivo5' id='archivo5'></p>
              <p><input type='file' name='archivo6' value='archivo6' id='archivo6'></p>

              <p><label><i class="fa fa-question-circle"></i><?php echo __('Comentario supervisor', $lang); ?></label><textarea name="csup"></textarea></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Comentario RRHH.', $lang); ?></label><textarea name="crrhh"></textarea></p>
              <p><label><i class="fa fa-question-circle"></i><?php echo __('Comentario Admin. Financiero', $lang); ?></label><textarea name="caf"></textarea></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit" value="<?php echo __('Enviar', $lang); ?>"/>
          </div>
  </form>

      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../../js/menu.js"></script>

</body>
</html>




 <?php } ?>
