<?php
/**
 * Clase encargada de las consultas a la tabla users.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'db.php';

class User extends db
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  //funcion que loguea al usuario contra la db.
  function LoginUser($user){
    //Construimos la consulta
    $sql="SELECT * from users WHERE user='".$user."'";
    //Realizamos la consulta
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      if($resultado!=false){
        return $resultado->fetch_assoc();
      }else{
        return null;
      }
    }else{
      return null;
    }
  }

  //funcion para sacar el nombre del usuario por su id
  function nombreUsuario($id){
    //Construimos la consulta
    $sql="SELECT name from users WHERE id_user='".$id."'";
    //Realizamos la consulta
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      if($resultado!=false){
        return $resultado->fetch_assoc();
      }else{
        return null;
      }
    }else{
      return null;
    }
  }

  //funcion para sacar el nombre del usuario por su id
  function menuDash($id){
    //Construimos la consulta
    $sql="SELECT menu from users WHERE id_user='".$id."'";
    //Realizamos la consulta
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      if($resultado!=false){
        return $resultado->fetch_assoc();
      }else{
        return null;
      }
    }else{
      return null;
    }
  }

}
 ?>
