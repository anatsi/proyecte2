<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './ddbb/ticket.php';

$usuario=new User();
$sesion=new Sesiones();
$ticket= new Ticket();

//si la sesion no esta iniciada, devolvemos al usuario a la pagina de inicio de sesion
if (isset($_SESSION['usuario'])==false) {
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Resolver ticket</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
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
      </nav>

    </header>
    <?php
    //sacamos el empleado que se quiere editar por su id
      $seleccionado=$ticket->TicketId($_GET['id']);
     ?>
    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <h2>Resolver ticket</h2>
        <form action="resolver.php" method="post">
          <input type="hidden" name="id" value="<?=$seleccionado['id']?>">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>RESUELTO?</label></p>
              <p>  <input name='resuelto' value='SI' type="radio">SI</p>
              <br>
              <p>  <input name='resuelto' value='NO' type="radio">NO</p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>COMENTARIO</label>
                <textarea name="comentario" rows="20" cols="200" maxlength="800"></textarea>
              </p>
          </div>
          <br><br><br><br><br><br><br><br><br>
          <div class="submitbuttons">
              <input class="submitone" type="submit" name="enviar"/>
          </div>
  </form>
      </div> <!-- END container -->
    </div> <!-- END site-content -->
  </div> <!-- END site-pusher -->
</div> <!-- END site-container -->

<!-- Scripts para que el menu en versión movil funcione -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script  src="../js/menu.js"></script>

</body>
</html>
<?php
  if (isset($_POST['enviar'])) {
    if (isset($_POST['resuelto']) && isset($_POST['comentario'])) {
      $ticketResuelto = $ticket -> Resolver($_POST['id'], $_POST['resuelto'], $_POST['comentario']);
      if ($ticketResuelto == false || $ticketResuelto == null) {
        ?>
          <script type="text/javascript">
            alert('No se pudo resolver el ticket');
            window.location = 'index.php';
          </script>
        <?php
      }else {
        ?>
        <script type="text/javascript">
          window.location= 'index.php';
        </script>
        <?php
      }
    }else {
      ?>
        <script type="text/javascript">
          alert('No se pudo resolver el ticket');
          window.location = 'index.php';
        </script>
      <?php
    }

  }
?>

 <?php } ?>
