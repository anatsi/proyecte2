
<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
require_once './ddbb/empleados.php';
require_once './ddbb/fechas.php';
require_once './ddbb/material.php';


$usuario=new User();
$sesion=new Sesiones();
$empleado= new Empleados();
$fecha= new Fechas();
$material= new Material();

//si la sesion no esta iniciada, devolvemos al usuario a la pagina de inicio de sesion
if (isset($_SESSION['usuario'])==false){
  header('Location: ../index.php');
}else{
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
    <script type="text/javascript">
  function alerta()
      {
      var mensaje;
      var opcion = confirm("¿Quires borrar el empleado?");
      if (opcion == true) {
          //mensaje = "Has clickado OK";
        window.location="borrar.php?e=<?php echo $_GET['e']; ?>";

    } else {
        //mensaje = "Has clickado Cancelar";
        window.location="index.php";

    }
    document.getElementById("ejemplo").innerHTML = mensaje;
  }
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
    <?php
      //sacamos el empleado que se quiere editar por su id
      $seleccionado=$empleado->EmpleadoId($_GET['e']);
      //Sacar el empleado que se quiere editar en la tabla fecha
      $seleccionad=$fecha->FechaId($_GET['e']);

      ?>
    <div class="site-content">
      <div class="container">
        <div class="breadcrumb" style="margin-left: 2%; color:black;">
          <a href="../dashboard.php">Inicio</a> >> <a href="index.php">Gestión empleados</a> >> <a href="">Editar empleado</a>
        </div>

        <!-- Contenido de la pagina. -->
        <h2>Editar empleado</h2>
        <div> <p align='center'><button style ="color :red;" onclick="alerta()">  &nbsp  Borrar definitivamente el empleado  &nbsp </button> </p> </div>

        <div> <p style ="color :red;"> &nbsp &nbsp Los campos con (*) son obligatorios </p></div>
        <form action="editarEmpleado.php" method="post">
          <input type="hidden" name="e" value="<?=$seleccionado['id']?>">
          <div class="formthird">
              <p><label><i class="fa fa-question-circle"></i>Nombre (*)</label><input name='nombre' value='<?=$seleccionado['nombre']?>' type="text" required></p>
              <p><label><i class="fa fa-question-circle"></i>Apellidos (*)</label><input name='apellidos' value='<?=$seleccionado['apellidos']?>' type="text" required></p>
              <p><label><i class="fa fa-question-circle"></i>Telefono (*)</label><input name='tel' value='<?=$seleccionado['telefono']?>' type="tel" required></p
              <p><label><i class="fa fa-question-circle"></i>Fecha de nacimiento (*)</label><input  type="date"  name='nacim' value ='<?=$seleccionad['nacimiento']?>' required></p>
              <p><label><i class="fa fa-question-circle"></i>Fecha Caducidad DNI (*)</label><input  type="date" name='cadDni' value ='<?=$seleccionad['dni']?>' required ></p>
              <p><label><i class="fa fa-question-circle"></i>Permiso de conducir (*)</label><input  type="date" name='cadPerm' value ='<?=$seleccionad['carnet_conducir']?>' required></p>
              <p><label><i class="fa fa-question-circle"></i>Permiso de conducir Ford</label>
                <input type="date" name='cadPermFord' value ='<?=$seleccionad['conducir_ford']?>'>
              </p>
              <p><label><i class="fa fa-question-circle"></i>Caducidad Pass Ford</label><input type="date" name='cadPassFord' value ='<?=$seleccionad['pase_ford']?>'></p>
              <p><label><i class="fa fa-question-circle"></i>Fecha Revision Medica</label><input type="date"  name='revMedico' value ='<?=$seleccionad['medico']?>'></p>
              <p style="visibility:hidden;"><label><i class="fa fa-question-circle"></i>Fecha Revision Medica</label><input type="date"  name=''></p>
              <p style="visibility:hidden;"><label><i class="fa fa-question-circle"></i>Fecha Revision Medica</label><input type="date"  name=''></p>

              </div>
<?php
$seleccionado1=$material->MaterialId($_GET['e'],1);
?>

<div class="formthird">
<p><label >Pantalon Verano</label>
<input type="date" name="pantalon_ver" value ='<?=$seleccionado1['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_pantalon_ver" value ='<?=$seleccionado1['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
</select>
<input type="number" min= "0"  min= "0" name="cant_pantalon_ver" value ='<?=$seleccionado1['cantidad']?>'></p></div>

<?php
$seleccionado2=$material->MaterialId($_GET['e'],2);
?>

<div class="formthird">
<p><label >Pantalon Invierno</label>
<input type="date" name="pantalon_inv" value ='<?=$seleccionado2['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_pantalon_inv"value ='<?=$seleccionado2['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
<option value="XS">S</option>
</select>
<input type="number" min= "0" name="cant_pantalon_inv"value ='<?=$seleccionado2['cantidad']?>'>
</p></div>
<?php
$seleccionado3=$material->MaterialId($_GET['e'],3);
?>


<div class="formthird">
<p><label >Polo Manga Corta</label>
<input type="date" name="polo_mc" value ='<?=$seleccionado3['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_polo_mc"value ='<?=$seleccionado3['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
<option value="XS">XS</option>
</select>
<input type="number" min= "0"  name="cant_polo_mc" value ='<?=$seleccionado3['cantidad']?>'></p></div>
<?php
$seleccionado4=$material->MaterialId($_GET['e'],4);
?>



<div class="formthird">
<p><label >Polo Manga Larga</label>
<input type="date" name="polo_ml" value ='<?=$seleccionado4['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_polo_ml" value ='<?=$seleccionado4['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
<option value="XS">XS</option>
</select>
<input type="number"  min= "0" name="cant_polo_ml" value ='<?=$seleccionado4['cantidad']?>'></p></div>
<?php
$seleccionado5=$material->MaterialId($_GET['e'],5);
?>




<div class="formthird">
<p><label >Zapatos</label>
<input type="date" name="zapato" value ='<?=$seleccionado5['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_zapato" value ='<?=$seleccionado5['talla']?>'>
<option value="46">&nbsp 46</option>
<option value="45">&nbsp 45</option>
<option value="44">&nbsp 44</option>
<option value="43">&nbsp 43</option>
<option value="42">&nbsp 42</option>
<option value="41">&nbsp 41</option>
<option value="40">&nbsp 40</option>
<option value="39">&nbsp 39</option>
<option value="38">&nbsp 38</option>
<option value="37">&nbsp 37</option>
<option value="36">&nbsp 36</option>
</select>
<input type="number" min= "0" name="cant_zapato" value ='<?=$seleccionado5['cantidad']?>'></p></div>
<?php
$seleccionado6=$material->MaterialId($_GET['e'],6);
?>



<div class="formthird">
<p><label >Cazadora</label>
<input type="date" name="cazadora" value ='<?=$seleccionado6['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_cazadora" value ='<?=$seleccionado6['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
</select>
<input type="number" min= "0"  name="cant_cazadora" value ='<?=$seleccionado6['cantidad']?>'></p></div>
<?php
$seleccionado7=$material->MaterialId($_GET['e'],7);
?>



<div class="formthird">
<p><label >Polar</label>
<input type="date" name="polar" value ='<?=$seleccionado7['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_polar" value ='<?=$seleccionado7['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
</select>
<input type="number" min= "0"  name="cant_polar" value ='<?=$seleccionado7['cantidad']?>'></p></div>

<?php
$seleccionado8=$material->MaterialId($_GET['e'],8);
?>
<div class="formthird">
<p><label >Impermeable</label>
<input type="date" name="impermeable" value ='<?=$seleccionado8['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_imper" value ='<?=$seleccionado8['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
</select>
<input type="number" min= "0"  name="cant_imper" value ='<?=$seleccionado8['cantidad']?>'></p></div>

<?php
$seleccionado9=$material->MaterialId($_GET['e'],9);
?>
<div class="formthird">
<p><label >Chaleco</label>
<input type="date" name="chaleco" value ='<?=$seleccionado9['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_chaleco" value ='<?=$seleccionado9['talla']?>'>
<option value="3XL">3XL</option>
<option value="2XL">2XL</option>
<option value="XL">XL</option>
<option value="L"> L</option>
<option value="M">M</option>
<option value="S">S</option>
</select>
<input type="number" min= "0"  name="cant_chaleco" value ='<?=$seleccionado9['cantidad']?>'>
</p></div>

<?php
$seleccionado10=$material->MaterialId($_GET['e'],10);
?>
<div class="formthird">
<p><label >Gorra</label>
<input type="date" name="gorra" value ='<?=$seleccionado10['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_gorra" value ='<?=$seleccionado10['talla']?>'>
<option value="SIN">SIN</option>
</select>
<input type="number" min= "0"  name="cant_gorra" value ='<?=$seleccionado10['cantidad']?>'>
</p></div>

<?php
$seleccionado11=$material->MaterialId($_GET['e'],11);
?>
<div class="formthird">
<p><label >Guantes</label>
<input type="date" name="guantes" value ='<?=$seleccionado11['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_guantes" value ='<?=$seleccionado11['talla']?>'>
<option value="SIN">SIN</option>
</select>
<input type="number"  min= "0" name="cant_guantes" value ='<?=$seleccionado11['cantidad']?>'>
</p></div>

<?php
$seleccionado12=$material->MaterialId($_GET['e'],12);
?>
<div class="formthird">
<p><label >Zapatos Agua</label>
<input type="date" name="agua" value ='<?=$seleccionado12['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_agua" value ='<?=$seleccionado12['talla']?>'>
  <option value="46">&nbsp 46</option>
  <option value="45">&nbsp 45</option>
  <option value="44">&nbsp 44</option>
  <option value="43">&nbsp 43</option>
  <option value="42">&nbsp 42</option>
  <option value="41">&nbsp 41</option>
  <option value="40">&nbsp 40</option>
  <option value="39">&nbsp 39</option>
  <option value="38">&nbsp 38</option>
  <option value="37">&nbsp 37</option>
  <option value="36">&nbsp 36</option>
</select>
<input type="number"  min= "0" name="cant_agua" value ='<?=$seleccionado12['cantidad']?>'>
</p></div>


<?php
$seleccionado13=$material->MaterialId($_GET['e'],13);
?>
<div class="formthird">
<p><label >Chaleco colores</label>
<input type="date" name="colores" value ='<?=$seleccionado13['fecha_entrega']?>'>
</p></div>
<div class="formthird">
<p>
<select id="" name="talla_colores" value ='<?=$seleccionado13['talla']?>'>
  <option value="Rosa">Rosa</option>
  <option value="Azul">Azul</option>
  <option value="Verde">Verde</option>
</select>
<input type="number"  min= "0" name="cant_colores" value ='<?=$seleccionado13['cantidad']?>'>
</p></div>
          <div class="submitbuttons">
              <input style = " color:white;background-color: #4CAF50;" type="submit" value="Enviar" />
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
//comprobamos que se ha rellenado el nombre y los apellidos
  if(isset($_POST['nombre'])&&isset($_POST['apellidos'])){
    //inicializamos alta a 0
    $alta=1;
//si se ha pulsado en la casilla de alta, ponemos la variable a 1
    if (isset($_POST['alta'])){
      $alta=0;
    }
$error = 0;
     //llamamos a la consulta de editar el empleado
    $editarEmpleado = $empleado->editarEmpleado($_POST['e'], $_POST['nombre'], $_POST['apellidos'], $_POST['tel']);
    if ($editarEmpleado == false || $editarEmpleado == null) {
      $error = 1;
    }
//Llamar la consulta de editar fechas
     $editarEmpleado= $fecha->editarFecha($_POST['e'],$_POST['cadPassFord'],$_POST['cadDni'],$_POST['cadPerm'],$_POST['cadPermFord'],$_POST['revMedico'],$_POST['nacim']);
     if ($editarEmpleado == false || $editarEmpleado == null) {
       $error=1;
     }

//Llamar la consulta para editar material
     //Las variables inicializadas son para representar los numero de material
     if (isset($_POST['pantalon_ver']) && $_POST['pantalon_ver']!='' && isset($_POST['cant_pantalon_ver'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],1,$_POST['pantalon_ver'],$_POST['talla_pantalon_ver'],$_POST['cant_pantalon_ver']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['pantalon_inv']) && $_POST['pantalon_inv']!='' && isset($_POST['cant_pantalon_inv'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],2,$_POST['pantalon_inv'],$_POST['talla_pantalon_inv'],$_POST['cant_pantalon_inv']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['polo_mc']) && $_POST['polo_mc']!='' && isset($_POST['cant_polo_mc'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],3,$_POST['polo_mc'],$_POST['talla_polo_mc'],$_POST['cant_polo_mc']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['polo_ml']) && $_POST['polo_ml']!='' && isset($_POST['cant_polo_ml'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],4,$_POST['polo_ml'],$_POST['talla_polo_ml'],$_POST['cant_polo_ml']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['zapato']) && $_POST['zapato']!='' && isset($_POST['cant_zapato'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],5,$_POST['zapato'],$_POST['talla_zapato'],$_POST['cant_zapato']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['cazadora']) && $_POST['cazadora']!='' && isset($_POST['cant_cazadora'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],6,$_POST['cazadora'],$_POST['talla_cazadora'],$_POST['cant_cazadora']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['polar']) && $_POST['polar']!='' && isset($_POST['cant_polar'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],7,$_POST['polar'],$_POST['talla_polar'],$_POST['cant_polar']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['impermeable']) && $_POST['impermeable']!='' && isset($_POST['cant_imper'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],8,$_POST['impermeable'],$_POST['talla_imper'],$_POST['cant_imper']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['chaleco']) && $_POST['chaleco']!='' && isset($_POST['cant_chaleco'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],9,$_POST['chaleco'],$_POST['talla_chaleco'],$_POST['cant_chaleco']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['gorra']) && $_POST['gorra']!='' && isset($_POST['cant_gorra'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],10,$_POST['gorra'],$_POST['talla_gorra'],$_POST['cant_gorra']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['guantes']) && $_POST['guantes']!='' && isset($_POST['cant_guantes'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],11,$_POST['guantes'],$_POST['talla_guantes'],$_POST['cant_guantes']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['agua']) && $_POST['agua']!='' && isset($_POST['cant_agua'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],12,$_POST['agua'],$_POST['talla_agua'],$_POST['cant_agua']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

     if (isset($_POST['colores']) && $_POST['colores']!='' && isset($_POST['cant_colores'])) {
       $editarEmpleado= $material->editarMaterial($_POST['e'],13,$_POST['colores'],$_POST['talla_colores'],$_POST['cant_colores']);
       if ($editarEmpleado== false || $editarEmpleado == null) {
         $error=1;
       }
     }

    if ($error==1){
      //si no se ha podido actualizar, avisamos al usaurio
      ?>
      <script type="text/javascript">
        alert('Error al registrar el nuevo empleado');
        window.location='index.php';
      </script>
      <?php
    }else{
      //si se ha editado correctamente, devolvemos el usuario a inicio
      ?>
        <script type="text/javascript">
          alert('Información editada con exito.');
          window.location='index.php';
        </script>
      <?php
    }
  }
 ?>
 <?php } ?>
