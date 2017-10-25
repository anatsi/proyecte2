<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
  </head>
  <body>
    <?php
    //incluimos el archivo encargado de la tabla de usuarios de la db y creamos el objeto.
      include 'users.php';
      $user= new User();
    //incluimos el archivo encargado de las sesiones y creamos el objeto.
      include 'sesiones.php';
      $sesion= new Sesiones();
      //llamamos a la funcion de loguear el usuario creada en users.php
      $registrado=$user->LoginUser($_POST['form-username']);
      //comprobamos que el usuario existe
      if ($registrado!=null) {
        //comprobamos que la contraseña que ha puesto es correcta.
        if ($registrado['pass']==md5($_POST['form-password'])) {
          //si el usuario existe y la contraseña es correcta, iniciamos la sesion.
          $sesion->addUsuario($registrado['id_user']);
          ?>
            <script type="text/javascript">
              window.location="dashboard.php";
            </script>
          <?php
        }else {
          //si la contraseña no coincide, sacamos un mensaje y lo reenviamos al formulario.
          ?>
            <script type="text/javascript">
            alert('Contraseña incorrecta.');
            window.location="index.php";
            </script>
          <?php
        }
      }else {
        //si el usuario no esta registrado, se lo indicamos y le volvemos a enviar al formulario.
        ?>
          <script type="text/javascript">
            alert('Usuario incorrecto.');
            window.location="index.php";
          </script>
        <?php
      }
     ?>
  </body>
</html>
