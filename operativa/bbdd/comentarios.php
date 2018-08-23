<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once 'db.php';
/**
 * Clase comentarios encargada de hacer las consultas contra esta tabla de la db
 */
class Comentarios extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

//funcion para insertar un nuevo empleado para un servicio.
function nuevoComentario($servicio, $dia, $comentario){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO comentario_rrhh(id, servicio, dia, comentario) VALUES (null, ".$servicio.", '".$dia."', '".$comentario."')";
  //Realizamos la consulta utilizando la funcion creada en db.php
  $resultado=$this->realizarConsulta($sql);
  if($resultado!=false){
    return true;
  }else{
    return null;
  }
}

//funcion para sacar el comentario por su servicio y su dia
function sacarComentario($servicio, $dia){
  //Construimos la consulta
  $sql="SELECT * FROM comentario_rrhh where servicio=".$servicio." and dia='".$dia."' order by id desc limit 1";
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
