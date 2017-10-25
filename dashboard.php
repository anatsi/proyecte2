<?php
	//incluimos los archivos de sesiones y de usuarios y creamos los objetos.
	require_once 'sesiones.php';
	require_once 'users.php';

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
	</head>
	<body>
		<div class="container demo-1">
			<!-- Codrops top bar -->
			<div class="codrops-top clearfix">
				<?php
					//llamamos a la función para devolver el nombre de usuario.
					$nombreuser=$usuario->nombreUsuario($_SESSION['usuario']);
					//sacamos el nombre de usuario por su id
					echo "<a><strong>Bienvenido ".$nombreuser['name']."</strong></a>";
				 ?>
				<span class="right"><a href="logout.php">Cerrar Sesion</a></span>
			</div><!--/ Codrops top bar -->
			<header class="clearfix">
				<h1>Dashboard</h1>
				<div id="imagen">
     			   	<img src="imagenes/logo.png" alt="Logo" height="98.6px" width="147.3px"/>
   				</div><br><br>
				<nav class="codrops-demos">
					<?php
						//llamamos a la funcion que nos devuelve el numero para el menu.
						$menu=$usuario->menuDash($_SESSION['usuario']);
						if ($menu['menu']==1) {
							echo "<a href='operativa/index.php'>APP Operativa</a>";
							echo "<a href='#'>Nóminas</a>";
							echo "<a href='files/Peticion_vacaciones.pdf'>Solicitar vacaciones</a>";
						}elseif ($menu['menu']==2) {
							echo "<a href='operativa/index.php'>APP Operativa</a>";
							echo "<a href='#'>Nóminas</a>";
							echo "<a href='files/Plan_Trabajo_Supervisores_2017.pdf'>Plan trabajo anual</a>";
							echo "<a href='files/Peticion_vacaciones.pdf'>Solicitar vacaciones</a>";
						}elseif ($menu['menu']==3) {
							echo "<a href='#'>Nóminas</a>";
							echo "<a href='files/Peticion_vacaciones.pdf'>Solicitar vacaciones</a>";
						}else {
							//si el numero de permisos no es correcto, sacara un aviso.
						?>
							<script type="text/javascript">
								alert('Ups, algo salio mal. Hable con el responsable.');
							</script>
						<?php
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
