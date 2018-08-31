<?php
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
  <title>Finalizar servicio</title>
  <link rel="stylesheet" href="../../css/menu.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="shortcut icon" href="../../imagenes/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../../css/dashboard.css" />
  <script type="text/javascript" src="../../js/servicioForm.js"></script>
</head>
<style media="screen">
html, body {
  font-family: 'Roboto thin';
  font-size: 1em;
  line-height: 1.4;
  height: 100%;
  margin: 0;
  padding: 0;
}
#contactForm {
      margin: 0 auto;
}

  #contactForm input, textarea {
      letter-spacing: 2px;
      color: black;
      font-weight: bold;
      background-color: rgb(186, 191, 193);
      outline: none; border: none;
      display:block;
      margin: 0 auto;
      padding: 1em;
      width: 90%;
      max-width: 400px;
 }
textarea::placeholder{
  color: rgb(96, 96, 90);
}
#contactForm textarea {
  height: 150px;
  color: black;
}
</style>
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
        <a href="../index.php">Inicio</a>
        <?php
        $menu=$usuario->menuDash($_SESSION['usuario']);
        $opciones = explode(",", $menu['menu']);
        foreach ($opciones as $opcion) {
          if ($opcion == 21) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente/resp.</a>";
          }elseif ($opcion == 22) {
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';

          }elseif ($opcion == 23) {
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';

          }elseif ($opcion == 0) {
            echo '<a href="nuevoServicio.php">Nueva actividad </a>';
            echo "<a href='actividadesActuales.php'>Actividades actuales</a>";
            echo "<a href='historicoActividades.php'>Histórico actividades</a>";
            echo "<a href='resumen.php'>Búsqueda por fechas</a>";
            echo "<a href='nuevoCliente.php'>Nuevo cliente/resp.</a>";
            echo '<a href="../rrhh/filtroRRHH.php">Selección personal</a>';
            echo '<a href="../supervisores/filtroSupervisores.php">Jefe de turno</a>';
          }
        }
         ?>
      </nav>

    </header>

    <?php
      //sacamos la informacion del servicio para no dejar finalizarlo antes de empezarlo.
      $info = $servicio -> ServicioId($_GET['servicio']);
     ?>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%; color:black;">
          <a href="../../dashboard.php">Inicio</a> >> <a href="../index.php">Gestión actividades</a> >> <a href="actividadesActuales.php">Actividades actuales</a> >> <a href="">Finalizar actividad</a>
        </div>
        <!-- Contenido de la pagina. -->
        <h2>Finalizar servicio</h2>
        <form id="contactForm" action="finalizarServicio.php" method="post">
          <input type="hidden" name="fin" value="<?=$_GET['servicio']?>">
          <input type="date" name="fecha" value=""  min="<?=$info['f_inicio']?>">
          <br>
          <textarea class="formInput" name="comentario" id="message" placeholder="Comentario operativa"></textarea>
          <br>
          <textarea class="formInput" name="finanzas" id="message" placeholder="Comentario finanzas"></textarea>
          <br>
          <input class="submitForm" type="submit" value="Finalizar Servicio"/>
         </form>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../../js/menu.js"></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>
</html>
<?php
  if (isset($_POST['fin']) && isset($_POST['fecha'])) {
    if (isset($_POST['fecha'])==false || $_POST['fecha'] == '0000-00-00' || $_POST['fecha'] == '') {
      ?>
        <script type="text/javascript">
          alert('ERROR. Elegir una fecha antes de continuar.');
          window.location = 'actividadesActuales.php'
        </script>
      <?php
    }else {
      $finalizado=$servicio->FinalizarActividad($_POST['fin'], $_POST['fecha'], $_POST['comentario']);
      if ($finalizado == true) {
        //enviamos un correo avisando de que se ha finalizado la actividad
        //transformamos la fecha
        $fecha=explode("-", $_POST['fecha']);
        $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
        //sacar la información de la actividad
        $actividad = $servicio -> ServicioId($_POST['fin']);
        //si sale bien, enviamos un correo a los responsables para avisarles.
        // Enviar el email
        $mail = "robot@tsiberia.es";

        $header = 'From: ' . $mail . " \r\n";
        $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
        $header .= "Mime-Version: 1.0 \r\n";
        //$header .= "Content-Type: text/plain";
        $header .= "Content-Type: text/html; charset=utf-8";

        $mensaje = '<html>' . '<head><title>Email</title>
        <style type="text/css">
        h2 {
            color: black;
            font-family: Impact;
          }
        </style>
        </head>' .
        '<body>
          <h4>
            <b>La actividad "'.$actividad['descripcion'].'" finaliza el dia '.$fecha.'</b>
          </h4><br />' .
          'Por favor, no responda a este correo lo envia un robot automáticamente.'.
          '<br />Enviado el ' . date('d/m/Y', time()) .
        '</body></html>';

        $para = 'rrhh@tsiberia.es, acosinga@tsiberia.es, acosinga@ford.com, pablo.moreno.g@ts-iberica.com';
        $asunto = 'Fin de la actividad "'.$actividad['descripcion'].'"';

        mail($para, $asunto, $mensaje, $header);

        if (isset($_POST['finanzas']) && $_POST['finanzas']!=null && $_POST['finanzas']!=false) {
          //si hay comentario para Pilar, se lo enviamos en el correo.
          $mail = "robot@tsiberia.es";

          $header = 'From: ' . $mail . " \r\n";
          $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
          $header .= "Mime-Version: 1.0 \r\n";
          //$header .= "Content-Type: text/plain";
          $header .= "Content-Type: text/html; charset=utf-8";

          $mensaje = '<html>' . '<head><title>Email</title>
          <style type="text/css">
          h2 {
              color: black;
              font-family: Impact;
            }
          </style>
          </head>' .
          '<body>
            <h4>
              La actividad "'.$actividad['descripcion'].'" finaliza el dia '.$fecha.'
              <br>
              </h4>
              Comentario por parte de operativa: '.$_POST['finanzas'].'
              Enviado por: '.$nombreuser['name'].'
            <br />' .
            'Por favor, no responda a este correo lo envia un robot automáticamente.'.
            '<br />Enviado el ' . date('d/m/Y', time()) .
          '</body></html>';

          $para = 'mariap.urbina.t@ts-iberica.com';
          $asunto = 'Fin de la actividad "'.$actividad['descripcion'].'"';

          mail($para, $asunto, $mensaje, $header);
        }
        ?>
          <script type="text/javascript">
            window.location='actividadesActuales.php';
          </script>
        <?php
      }else {
        echo "Error al finalizar el movimiento";
      }
    }
  }
?>
<?php } ?>
