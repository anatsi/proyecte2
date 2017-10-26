<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../sesiones.php';
require_once '../users.php';
require_once '../cliente.php';
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
  <title>Nuevo servicio</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <script type="text/javascript" src="../js/servicioForm.js"></script>
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
  <span class="right"><a href="../logout.php">Cerrar Sesion</a></span>
</div><!--/ Codrops top bar -->

<div class="site-container">
  <div class="site-pusher">

    <header class="header">

      <a href="#" class="header__icon" id="header__icon"></a>
      <a href="../dashboard.php" class="header__logo"><img src="../imagenes/logo.png" alt=""></a>

      <nav class="menu">
        <a href="index.php">Inicio</a>
        <a href="modificarServicio.php">Modificar</a>
        <a href="#">Consultar</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <form action="nuevoServicio.php" method="post" id="formulario">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Descripción servicio (*)</label><input type="text" name="descripcion" required/></p>
              <p><label><i class="fa fa-question-circle"></i>Modelos (*)</label><input type="text" name="modelos" /></p>
              <p><label><i class="fa fa-question-circle"></i>Fecha inicio (*)</label><input type="date" name="finicio" required/></p>
              <p><label><i class="fa fa-question-circle"></i>Cliente (*)</label>
                <select name="cliente" required>
                  <option>----Choose---</option>
                  <?php
                    $clientes= $cliente->listaClientes();
                    foreach ($clientes as $cliente) {
                      echo "<option value=".$cliente['id'].">".$cliente['nombre']."</option>";
                    }
                   ?>
                </select></p>
              <p><label><i class="fa fa-question-circle"></i>Responsable (*)</label><input type="text" name="responsable" required/></p>
              <p><label><i class="fa fa-question-circle"></i>Telefono responsable (*)</label><input type="text" name="telefono" required/></p>
              <p><label><i class="fa fa-question-circle"></i>Correo responsable (*)</label><input type="mail" name="correo"/></p>
          </div>
          <div class="formthird" id='contenedor'>
              <p><label><i class="fa fa-question-circle"></i>Recursos totales (*)</label><input type="number" min='0' name="recursos" id="total" readonly/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno mañana</label><input type="number" min='0' name="tm" id="tm" value='0' onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno tarde</label><input type="number" min='0' name="tt" id="tt" value='0' onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno noche</label><input type="number" min='0'name="tn" id="tn" value='0' onclick="suma();" onkeyup="suma();"/></p>
              <p><label><i class="fa fa-question-circle"></i>Turno central</label><input type="number" min='0'name="tc" id="tc" value='0' onclick="suma();" onkeyup="suma();"/></p>

              <button type="button" name="button" id="nuevoServicio" onclick="nuevo();">Añadir otro horario</button>
              <p id="enviar"></p>
          </div>
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Comentario supervisor</label><textarea name="csup"></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario RRHH</label><textarea name="crrhh"></textarea></p>
              <p><label><i class="fa fa-question-circle"></i>Comentario Admin. Financiero</label><textarea name="caf"></textarea></p>
          </div>
          <div class="submitbuttons">
              <input class="submitone" type="submit" />
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
//comprbamos que realmente haya rellenado algunos campos
  if (isset($_POST['descripcion'])) {
    //si los ha rellenado, llamamos a la función de insertar el servicio y le pasamos los datos.
    $nuevoServicio=$servicio->nuevoServicio($_POST['descripcion'], $_POST['modelos'], $_POST['recursos'], $_POST['finicio'], $_POST['cliente'], $_POST['responsable'], $_POST['telefono'], $_POST['correo'], $_POST['csup'],
     $_POST['crrhh'], $_POST['caf']);
    //comprobamos que se haya registrado.
    if ($nuevoServicio==null) {
      //si no se ha registrado le saca un mensaje avisandole
      ?>
        <script type="text/javascript">
          alert("Error al registrar el servicio.");
        </script>
      <?php
    }else {
      //si se ha registrado el servicio cogemos su id para regstrar los recursos
      $ultimo= $servicio-> ultimoServicio();
      foreach ($ultimo as $servicio) {
        $nuevorecurso=$recursos->nuevoRecurso($servicio['id'], $_POST['recursos'], $_POST['tm'], $_POST['tt'], $_POST['tn'], $_POST['tc'], $_POST['o1'], $_POST['i1'], $_POST['f1'], $_POST['o2'], $_POST['i2'],
         $_POST['f2'], $_POST['o3'], $_POST['i3'], $_POST['f3'], $_POST['o4'], $_POST['i4'], $_POST['f4'], $_POST['o5'], $_POST['i5'], $_POST['f5'],
          $_POST['o6'], $_POST['i6'], $_POST['f6']);
      }
       if ($nuevorecurso==null) {
         //si no se ha registrado le saca un mensaje avisandole
         ?>
           <script type="text/javascript">
             alert("Error al registrar el servicio.");
           </script>
         <?php
       }else {
         //si se ha registrado le saca un mensaje avisandole
         ?>
           <script type="text/javascript">
             alert("Nuevo servicio registrado.");
           </script>
         <?php
       }
    }
  }
 ?>



 <?php } ?>
