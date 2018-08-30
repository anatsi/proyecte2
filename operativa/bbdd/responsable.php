<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once 'db.php';
/**
 * Clase cliente encargada de hacer las consultas contra esta tabla de la db
 */
class Responsable extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

  //funcion para listar los clientes en el formulario de nuevos servicios
  function listaResponsables(){
    //Construimos la consulta
    $sql="SELECT * from responsable order by id asc";
    //Realizamos la consulta
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=null){
      //Montamos la tabla de resultados
      $tabla=[];
      while($fila=$resultado->fetch_assoc()){
        $tabla[]=$fila;
      }
      return $tabla;
    }else{
      return null;
    }
  }

  //funcion para sacar la informacion de un responsable a partir de su id
  function responsableId($id){
  //Construimos la consulta
  $sql="SELECT * from responsable WHERE id=".$id;
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

//funcion para insertar un nuevo cliente en la base de datos.
function nuevoResponsable($nombre, $tel, $email){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO responsable(id, nombre, telefono, email)VALUES (NULL, '".$nombre."', ".$tel.", '".$email."')";
  //Realizamos la consulta utilizando la funcion creada en db.php
  $resultado=$this->realizarConsulta($sql);
  if($resultado!=false){
    return true;
  }else{
    return null;
  }
}

}

 ?>
