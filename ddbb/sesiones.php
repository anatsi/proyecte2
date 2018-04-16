<?php
/**
 * Clase encargada de crear/destruir sesiones.
 */
class Sesiones
{
  private $usuario=null;

  //En el construct hacemos el session start para no tener que hacerlo cada vez.
  function __construct()
  {
    session_start();
    if (isset($_SESSION['usuario'])) $this->usuario=$_SESSION['usuario'];
  }

  public function getUsuario()
  {
    return $this->usuario;
  }

  //addusuario para guardar el usuario en la sesion.
  public function addUsuario($usuario)
  {
    $_SESSION['usuario']=$usuario;
    $this->usuario=$usuario;
  }

  //logout para cerrar la sesion.
  public function logOut($value='')
  {
    $_SESSION=[];
    session_destroy();
  }

}
 ?>
