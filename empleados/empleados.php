<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once '../operativa/db.php';
/**
 * Clase empleados encargada de hacer las consultas contra esta tabla de la db
 */
class Empleados extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

  //funcion para listar todos los empleados de la bbdd
  function listaEmpleados(){
    //Construimos la consulta
    $sql="SELECT * from empleados order by activo desc";
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

  //funcion para listar los empleados filtrados
  function listaFiltrados($b){
    //Construimos la consulta
    $sql="SELECT * from empleados where concat(nombre, ' ', apellidos) like '%".$b."%' order by activo desc";
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

  //funciones para activar y desactivar empleados
  function ActivarEmpleado($id){
    $sql="UPDATE empleados SET activo=1 where id=".$id;
    $finalizarAct=$this->realizarConsulta($sql);
    if ($finalizarAct=!false) {
         return true;
    }else {
         return false;
    }
  }

  function DesactivarEmpleado($id){
    $sql="UPDATE empleados SET activo=0 where id=".$id;
    $finalizarAct=$this->realizarConsulta($sql);
    if ($finalizarAct=!false) {
         return true;
    }else {
         return false;
    }
  }

  //funcion para insertar un nuevo empleado en la base de datos.
function nuevoEmpleado($nombre, $apellidos, $activo, $tel){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO empleados(id, nombre, apellidos, activo, telefono) VALUES (null, '".$nombre."', '".$apellidos."', ".$activo.", ".$tel.")";
  //Realizamos la consulta utilizando la funcion creada en db.php
  $resultado=$this->realizarConsulta($sql);
  if($resultado!=false){
    return true;
  }else{
    return null;
  }
}

//sacar empleado dependiendo del id
function EmpleadoId($id){
//Construimos la consulta
$sql="SELECT * from empleados WHERE id=".$id;
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

//funcion para editar la informacion del empleado.
function editarEmpleado($id, $nombre, $apellidos, $tel){
  $sql="UPDATE empleados SET nombre='".$nombre."', apellidos='".$apellidos."', telefono=".$tel." WHERE id=".$id;
  $finalizarAct=$this->realizarConsulta($sql);
  if ($finalizarAct=!false) {
       return true;
  }else {
       return false;
  }
}

}

 ?>
