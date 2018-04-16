<?php
//incluimos el archivo encargado de las sesiones y creamos el objeto.
  include './ddbb/sesiones.php';
  $sesion= new Sesiones();
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
  </head>
  <body>
    <?php
    //Reconocimiento idioma
      require('./languages/languages.php');
          $lang = "es";
      if ( isset($_GET['lang']) ){
          $lang = $_GET['lang'];
      }
    //incluimos el archivo encargado de la tabla de usuarios de la db y creamos el objeto.
      include './ddbb/users.php';
      $user= new User();

      //llamamos a la funcion de loguear el usuario creada en users.php
      $registrado=$user->LoginUser($_POST['form-username']);
      //comprobamos que el usuario existe
      if ($registrado!=null) {
        $salt='$tsi$/';
        //comprobamos que la contraseña que ha puesto es correcta.
        if ($registrado['pass']==sha1(md5($salt . $_POST['form-password']))) {
          //si el usuario existe y la contraseña es correcta, iniciamos la sesion.
          $sesion->addUsuario($registrado['id_user']);
          ?>
            <script type="text/javascript">
              window.location="dashboard.php?lang=<?php echo $lang; ?>";
            </script>
          <?php
        }else {
          //si la contraseña no coincide, sacamos un mensaje y lo reenviamos al formulario.
          ?>
            <script type="text/javascript">
            alert('<?php echo __('Contraseña incorrecta.', $lang) ?>');
            window.location="index.php?lang=<?php echo $lang; ?>";
            </script>
          <?php
        }
      }else {
        //si el usuario no esta registrado, se lo indicamos y le volvemos a enviar al formulario.
        ?>
          <script type="text/javascript">
            alert('<?php echo __('Usuario incorrecto.', $lang) ?>');
            window.location="index.php?lang=<?php echo $lang; ?>";
          </script>
        <?php
      }
     ?>
  </body>
</html>
