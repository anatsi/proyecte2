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
    <link rel="stylesheet" href="../css/modificar.css">
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
        <a href="nuevoServicio.php">Nuevo Servicio</a>
        <a href="#">Consultar</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <!-- Contenido de la pagina. -->
        <table id="tablamod">
        <thead id="theadmod">
          <tr id="trmod">
            <th scope="col" id="thmod">Fecha inicio</th>
            <th scope="col" id="thmod">Modelos</th>
            <th scope="col" id="thmod">Actividad</th>
            <th scope="col" id="thmod">Personal</th>
            <th scope="col" id="thmod">Cliente</th>
            <th scope="col" id="thmod">Opciones</th>
          </tr>
        </thead>
        <tbody id="tbodymod">
          <tr id="trmod">
            <td scope="row" data-label="Fecha inicio" id="tdmod">10/10/17</td>
            <td data-label="Modelos" id="tdmod">KUGA+PLAT.CD</td>
            <td data-label="Actividad" id="tdmod">CMP 71-72-72 STOP SHIP TORNILLO BOMBA</td>
            <td data-label="Personal" id="tdmod">2 2 HORAS + 8(4TT + 4TN)</td>
            <td data-label="Cliente" id="tdmod">FORD</td>
            <td data-label="Opciones" id="tdmod">GYEWEWGY</td>
          </tr>
          <tr id="trmod">
            <td scope="row" data-label="Fecha inicio" id="tdmod">11/10/17</td>
            <td data-label="Modelos" id="tdmod">PLATAFORMA CD</td>
            <td data-label="Actividad" id="tdmod">VOLANTE RUIDO BOTONERAS</td>
            <td data-label="Personal" id="tdmod">2 2 HORAS + 8(4TT + 4TN)</td>
            <td data-label="Cliente" id="tdmod">RAER 21</td>
            <td data-label="Opciones" id="tdmod">DBJSCDHB</td>
          </tr>
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
         $_POST['f2'], $_POST['o3'], $_POST['i3'], $_POST['f3'], $_POST['o4'], $_POST['i4'], $_POST['f4'], $_POST['o5'], $_POST['i5'], $_POST['f5']);
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
