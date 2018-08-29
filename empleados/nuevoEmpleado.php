<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './ddbb/empleados.php';
require_once './ddbb/fechas.php';

$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();
$fechas = new Fechas();
//comprobamos si se ha iniciado la sesion
if (isset($_SESSION['usuario'])==false) {
  //si no se ha iniciado, devolvemos al usuario a la pantalla de iniciar sesion
  header('Location: ../index.php');
}else {
 ?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Nuevo empleado</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="shortcut icon" href="../imagenes/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="../js/timeline.js"></script>
    <link rel="stylesheet" href="../../css/timeline.css">
    </head>
    <body>
      <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      <style>
.alert {
    padding: 20px;
    background-color: #f44336;
    color: white;
}
.hidden{
  display: none;
}
.shown{
  display: block;
}
  .personal{
    color:white;
    background-color:#1F523F;

}
</style>
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
        <a href="listaFechas.php">Fechas</a>
        <a href="materialEmpleado.php">Material</a>
      </nav>

    </header>

    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%; color:black;">
          <a href="../dashboard.php">Inicio</a> >> <a href="index.php">Gestión empleados</a> >> <a href="nuevoEmpleado.php">Nuevo empleado</a>
        </div>

        <!-- Contenido de la pagina. -->
        <h2 align="center">Nuevo empleado</h2>

        <div class="botones">
           <button id="personalTsi" class="personal" onclick="timeline1();">Personal TSI</button>
           <button id="ht" class="personal" onclick="timeline2();">Personal ETT</button>
         </div>
         <div> <p style ="color :red;"> &nbsp &nbsp Los campos con (*) son obligatorios </p></div>
         <div>
        <!-- Editar personal TSI. -->
        <div id="timeline1" class="shown">
        <form action="nuevoEmpleado.php" method="post" onsubmit="return check(this)">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Nombre (*)</label><input name='nombre' type="text" required></p>
              <p><label><i class="fa fa-question-circle"></i>Apellidos (*)</label><input name='apellidos' type="text" required></p>
              <p><label><i class="fa fa-question-circle"></i>Telefono (*)</label><input name='telefono'type="tel" required></p>
              <p><label><i class="fa fa-question-circle"></i>Fecha de nacimiento (*)</label><input  type="date"  name='nacim' required></p>
              <p><label><i class="fa fa-question-circle"></i>Fecha Caducidad DNI (*)</label><input
              type="date" name='cadDni' required></p>
              <p><label><i class="fa fa-question-circle"></i>Permiso de conducir (*)</label><input  type="date" name='cadPerm' required></p>
              <p><label><i class="fa fa-question-circle"></i>Permiso de conducir Ford</label>
              <input type="date" name='cadPermFord'></p>
              <p><label><i class="fa fa-question-circle"></i>Caducidad Pass Ford</label><input
              type="date"  name='cadPassFord' ></p>
              <p><label><i class="fa fa-question-circle"></i>Fecha Revision Medica</label><input
              type="date" name='revMedico' ></p>

        </div>

        <div class="formthird">
          <p><label><i class="fa fa-question-circle"></i>Dar de alta al trabajador</label><input Name='alta' type="checkbox" checked /></p>
        </div>

        <div class="submit">
              <input style = " color:white;background-color: #4CAF50;" type="submit" value="Enviar" />
        </div>

<!-- Editar personal ETT. -->

</form>
       </div><!-- END timeline1 -->
       <div id="timeline2" class="hidden">
          <form action="nuevoEmpleado.php" method="post" onsubmit="return check(this)">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Nombre (*)</label><input name='nombre' type="text" required ></p>
              <p><label><i class="fa fa-question-circle"></i>Apellidos (*)</label><input name='apellidos' type="text" required></p>
             </div>
             <input type="hidden" name="ett" value="1">

        <div class="formthird">
          <p><label><i class="fa fa-question-circle"></i>Dar de alta al trabajador</label><input Name='alta' type="checkbox" checked/></p>
        </div>

        <div class="submit">
              <input style = " color:white;background-color: #4CAF50;" type="submit" value="Enviar" />
        </div>
</form>

 </div>

       </div><!-- END timeline2 -->
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
//comprobamos si se ha rellenado el nombre y el apellido

  if(isset($_POST['ett'])){
     $alta=1;
//si se ha marcado la casilla de empleado alta, marcamos la variable alta como 0
    if (isset($_POST['alta'])){
      $alta=0;
    }
    $temp=1;//variable para diferenciar personal ett y empresa

    $nombre=$_POST['nombre'];
    $apellido=$_POST['apellidos'];
    //Fonction para quitar todo los accentos
    $charset='utf-8';
    $nombre = htmlentities( $nombre, ENT_NOQUOTES, $charset );
    $apellido= htmlentities( $apellido, ENT_NOQUOTES, $charset );
    $nombre= preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $nombre );
    $apellido= preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $apellido );
    $nombre= preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $nombre );
    $apellido= preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $apellido );
    $nombre = preg_replace( '#&[^;]+;#', '', $nombre );
    $apellido= preg_replace( '#&[^;]+;#', '', $apellido);
    //function para cambiar todo en Majuscula y no dejar espaacio
     $nombre =trim (strtoupper($nombre));
     $apellido =trim (strtoupper($apellido));

    $nuevoEmpleado=$empleado->nuevoEmpleadoEtt($nombre,$apellido,$alta,$temp);
    //Si el usurio no existe en la base de dato se pasa a comprobar si la fila se ha insertado correctamente
    if($nuevoEmpleado==null){

      //si no se ha podido insertar, sacamos un mensaje avisando
      ?>
      <script type="text/javascript" class="alert">
        alert('Error al registrar el nuevo empleado');
        window.location = 'index.php';
      </script>
      <?php
    }else{
      //si se inserta con exito, avisamos al usuario y lo devolvemos a inicio
      ?>
        <script type="text/javascript">
          alert('Nuevo empleado registrado con exito.');
          window.location='index.php';
        </script>
      <?php
    }

  }elseif (isset($_POST['nombre']) && isset($_POST['apellidos'])){
    //incializamos la variable alta a 0
      $alta=1;
      $contador=0;
      $temp=0;//variable para diferenciar personal ett y empresa
      $nombre=$_POST['nombre'];
      $apellido=$_POST['apellidos'];
      // Fonction para quitar todo los accentos
      $charset='utf-8';
      $nombre = htmlentities( $nombre, ENT_NOQUOTES, $charset );
      $apellido= htmlentities( $apellido, ENT_NOQUOTES, $charset );
      $nombre= preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $nombre );
      $apellido= preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $apellido );
      $nombre= preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $nombre );
      $apellido= preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $apellido );
      $nombre = preg_replace( '#&[^;]+;#', '', $nombre );
      $apellido= preg_replace( '#&[^;]+;#', '', $apellido);
    //function para cambiar todo en Majuscula y no dejar espaacio
     $nombre =trim (strtoupper($nombre));
     $apellido =trim (strtoupper($apellido));

     //Sacar dos o una letra de los nombres y apellidos
      $rest0 = substr("$nombre", 0, 3);
      $rest1 = substr("$nombre", 0, 2);
      $rest2 = substr("$apellido", 0, 1);
      $rest3 = substr("$nombre", 0, 1);
      $rest4 = substr("$apellido", 0, 2);
      $rest5 = substr("$apellido", 0, 3);
      $rest6 = substr("$apellido",1,2);
      $rest7 = substr("$nombre",1,2);
      $rest8 = substr("$apellido",2,2);


      $user0=$rest1.$rest2;
      $user1=$rest3.$rest4;
      $user2=$rest0;
      $user3=$rest2.$rest1;
      $user4=$rest4.$rest3;
      $user5=$rest5;
      $user6=$rest3.$rest6;
      $user7=$rest3.$rest6;
      $user8=$rest6.$rest3;
      $user9=$rest3.$rest8;
      $user10=$rest8.$rest3;
      $user11=$rest7.$rest2;
      $user12=$rest2.$rest7;

$lista = array("$user0","$user1","$user2","$user3","$user4","$user5","$user6","$user7","$user8","$user9"
  ,"$user10","$user11","$user12");



    //si se ha marcado la casilla de empleado alta, marcamos la variable alta como 0
    if (isset($_POST['alta'])){
      $alta=0;
    }
    //llamamos a la funcion de insertar un nuevo usuario en la ddbb

  for( $i=0;$i<=13;$i++){
   // echo $lista[$i];

       $nuevoUsuario=$empleado->nuevoUsuario($lista[$i]);

    if($nuevoUsuario==null || $nuevoUsuario==false){

      //encryptar la contraseña
      $salt='$tsi$/';
      $contra = sha1(md5($salt .$lista[$i]));

      //Insertar los datos en la base de datos
      $nuevoEmpleado=$empleado->nuevoEmpleado($nombre,$apellido,$lista[$i],$alta,$_POST['telefono'],$contra);
      //sacar el ultimo empleado que se ha insertado
      $empleados=$empleado->ultimoId();
      //Inserta los datos en la base de datos
      $nuevoEmpleado=$fechas->insertarFechas($empleados['id'],$_POST['cadPassFord'],$_POST['cadDni'],$_POST['cadPerm'],$_POST['cadPermFord'],$_POST['revMedico'],$_POST['nacim']);

// Enviar correo a los responsables de IT con los datos del nuevo usuario

//enviar correo para avisar de que el personal ya esta disponible.

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
      <h4> <b>Se ha creado un usuario en la base de datos. <br>
      Nombre: '.$nombre.'</b><br>
        <b>Apellidos: '.$apellido.'</b><br>
        <b>User: '.$lista[$i].'</b>
      </h4><br>' .

      'Por favor, no responda a este correo lo envia un robot automáticamente.'.
      '<br />Enviado el ' . date('d/m/Y', time()) .
    '</body></html>';

    $para = 'it@tsiberia.es';
    $asunto = 'Nuevo usuario creado';

    mail($para, $asunto, $mensaje, $header);

 break;
  }else{
    //contar los usuarios que tienen mismo nombre y appelidos
    $contador++;

   }

  }//Bucle de for

     //Si el usurio no existe en la base de dato se pasa a comprobar si la fila se ha insertado correctamente
    if($nuevoEmpleado==null){

      //si no se ha podido insertar, sacamos un mensaje avisando
      ?>
      <script type="text/javascript" class="alert">
        alert('Error al registrar el nuevo empleado');
        window.location = 'index.php';
      </script>
      <?php
    }else{
      //si se inserta con exito, avisamos al usuario y lo devolvemos a inicio
      ?>
        <script type="text/javascript">
          alert('Nuevo empleado registrado con exito.');
          window.location='index.php';
        </script>
      <?php
    }

  }

 ?>
 <?php } ?>
