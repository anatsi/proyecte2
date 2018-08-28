<?php
    //Reconocimiento idioma
    require('./languages/languages.php');
    	$lang = "es";
    if ( isset($_GET['lang']) ){
    	$lang = $_GET['lang'];
    }
	//incluimos los archivos de sesiones y de usuarios y creamos los objetos.
	require_once './ddbb/sesiones.php';
	require_once './ddbb/users.php';

	$sesion= new Sesiones();
	$usuario= new User();

	//comprobamos si la sesión esta iniciada
	if (isset($_SESSION['usuario'])==false) {
		//si no esta iniciada nos devuelve al formulario de inicio.
		header('Location: index.php');
	}else {
		//si esta iniciada se muestra la pagina.
 ?>
<!DOCTYPE html>
<html  charset="UTF-8" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Area trabajadores.</title>
		<link rel="shortcut icon" href="imagenes/favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/dashboard.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	</head>
	<body>
		<div class="container demo-1">
			<!-- Codrops top bar -->
			<div class="codrops-top clearfix">
				<?php
					//llamamos a la función para devolver el nombre de usuario.
					$nombreuser=$usuario->nombreUsuario($_SESSION['usuario']);
					//sacamos el nombre de usuario por su id
					echo "<a><strong>".__('Bienvenido ', $lang).$nombreuser['name']."</strong></a>";
				 ?>
				<span class="right"><a href="logout.php?lang=<?php echo $lang; ?>" id="logout"><?php echo __('Cerrar Sesion', $lang) ?></a></span>
			</div><!--/ Codrops top bar -->
			<header class="clearfix">
				<h1>Intranet</h1>
				<div id="imagen">
     			   	<img src="imagenes/logo.png" alt="Logo" height="98.6px" width="147.3px"/>
   				</div><br><br>
				<nav class="codrops-demos">
					<?php
						//llamamos a la funcion que nos devuelve el numero para el menu.
						$menu=$usuario->menuDash($_SESSION['usuario']);
            $opciones = explode(",", $menu['menu']);
            foreach ($opciones as $opcion) {
              if ($opcion == 1) {
                echo "<a href='./tickets/index.php'>Tickets</a>";
              }elseif ($opcion == 2) {
                echo "<a href='./operativa/index.php'>Gestión actividades</a>";
              }elseif ($opcion == 3) {
                echo "<a href='./directorio/index.php'>Directorio empleados</a>";
              }elseif ($opcion == 4) {
                echo "<a href='./workOrders/movimientos/filtroMovimientos.php'>Registro movimientos</a>";
              }elseif ($opcion == 5) {
                echo "<a href='./workOrders/actividades/index.php'>Registro actividades</a>";
              }elseif ($opcion == 6) {
                echo "<a href='./empleados/index.php'>Gestión empleados</a>";
              }elseif ($opcion == 0) {
                echo "<a href='./tickets/index.php'>Tickets</a>";
                echo "<a href='./operativa/index.php'>Gestión actividades</a>";
                echo "<a href='./directorio/index.php'>Directorio empleados</a>";
                echo "<a href='./workOrders/movimientos/filtroMovimientos.php'>Registro movimientos</a>";
                echo "<a href='./workOrders/actividades/index.php'>Registro actividades</a>";
                echo "<a href='./empleados/index.php'>Gestión empleados</a>";
              }
            }
					 ?>
				</nav>
			</header>
			<div class="main clearfix">
				<div class="column">
					<p></p>
				</div>
				<div class="column">
					<p></p>
				</div>
				</div>
			</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</body>
</html>
<?php } ?>
