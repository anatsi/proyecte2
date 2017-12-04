<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once '../operativa/db.php';
/**
 * Clase cliente encargada de hacer las consultas contra esta tabla de la db
 */
class Empleados extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

  //funcion para listar los clientes en el formulario de nuevos servicios
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

  function listaFiltrados($b){
    //Construimos la consulta
    $sql="SELECT * from empleados where nombre LIKE '%".$b."%' OR apellidos LIKE '%".$b."%' order by activo desc";
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

  //funcion para añadir la fecha de fin cuando se finaliza la actividad
  function ActivarEmpleado($id){
    $sql="UPDATE empleados SET activo=1 where id=".$id;
    $finalizarAct=$this->realizarConsulta($sql);
    if ($finalizarAct=!false) {
         return true;
    }else {
         return false;
    }
  }

  //funcion para añadir la fecha de fin cuando se finaliza la actividad
  function DesactivarEmpleado($id){
    $sql="UPDATE empleados SET activo=0 where id=".$id;
    $finalizarAct=$this->realizarConsulta($sql);
    if ($finalizarAct=!false) {
         return true;
    }else {
         return false;
    }
  }



}

 ?>
