<?php
    //Reconocimiento idioma
    require('./languages/languages.php');
    $lang = "es";
    if ( isset($_GET['lang']) ){
        $lang = $_GET['lang'];
    }

  require_once './ddbb/sesiones.php';
  $sesion= new Sesiones();
 ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Area trabajadores</title>

        <!-- CSS-->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.ico">
    </head>
    <body>
        <!-- Top content -->
        <div class="top-content">

            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <p><img src="assets/files/logo.png" alt="logo TSI" title="Logo TSI" width="100" height="75" /></p>
                                    <h3>Cambiar contraseña:</h3>
                                </div>
                                <div class="form-top-right">
                                </div>
                            </div>
                            <div class="form-bottom">
                                <form role="form" action="cambiarContra.php" method="post" class="login-form">
                                    <div class="form-group">
                                        <label class="sr-only" for="form-username">Contraseña</label>
                                        <input type="password" name="form-username" placeholder="Nueva contraseña" class="form-username form-control" id="form-username">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-password">Repetir contraseña</label>
                                        <input type="password" name="form-password" placeholder="Repetir contraseña" class="form-password form-control" id="form-password">
                                    </div>
                                    <button type="submit" class="btn"><?php echo __('Enviar', $lang) ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
    </body>
</html>
