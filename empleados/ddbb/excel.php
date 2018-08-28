<?php
require_once 'db.php';

/**
 * Classe para las consultas del excel
 */
class Excel extends db
{

  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

  //funcion para listar todos los empleados de la bbdd
  function listaEmpleados(){
    //Construimos la consulta
    $sql="SELECT * from empleados where ett=0 order by alta";

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
    $sql="SELECT * from empleados where ett=0 and concat(nombre, ' ', apellidos, ' ', user) like '%".$b."%' order by alta  ";
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

  // funcion para cambiar el numero al incapacidad correspondiente
   function ConverseIncapacidadId($id){
      //Construimos la consulta
      $sql= "SELECT * from incapacidad_temporal where id=".$id;
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

    //sacar fecha dependiendo del id
    function fechaId($id){
      //Construimos la consulta
      $sql="SELECT * from fechas WHERE empleado=".$id;

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
