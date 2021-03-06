<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once 'dbEmpleados.php';
/**
 * Clase empleados encargada de hacer las consultas contra esta tabla de la db
 */
class Empleados extends dbEmpleados
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

  //funcion para listar todos los empleados de la bbdd
  function listaEmpleados(){
    //Construimos la consulta
    $sql="SELECT * from empleados order by alta desc";
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

  //funcion para listar todos los empleados de la bbdd
  function listaEmpleadosActivos(){
    //Construimos la consulta
    $sql="SELECT * from empleados where alta = 0 AND vacaciones = 0 AND incapa_temporal = 0";
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

//funcion para insertar un nuevo empleado para un servicio.
function nuevoPersonal($servicio, $empleado, $turno, $dia){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO personal_servicios(id, servicio, empleado, turno, dia) VALUES (null, ".$servicio.", '".$empleado."', '".$turno."', '".$dia."')";
  //Realizamos la consulta utilizando la funcion creada en db.php
  $resultado=$this->realizarConsulta($sql);
  if($resultado!=false){
    return true;
  }else{
    return null;
  }
}

//funcion para listar los empleados de un dia para un servicio
function empleadosServicio($servicio, $dia, $turno){
  //Construimos la consulta
  $sql="SELECT * from personal_servicios where servicio=".$servicio." AND dia='".$dia."' AND turno='".$turno."'";
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

//funcion encargada de borrar el personal de una actividad para un dia
 function eliminarPersonal($id, $dia){
    $sql="DELETE FROM personal_servicios WHERE servicio=".$id." AND dia = '".$dia."'";
    $borrar=$this->realizarConsulta($sql);
    if ($borrar=!NULL) {
      return true;
    }else {
      return false;
    }
  }

  //sacar personal total asignado
  function personalAsignado($id, $dia){
    //Construimos la consulta
    $sql="SELECT count(*) as total from personal_servicios WHERE servicio=".$id." AND dia='".$dia."' AND empleado!=''";
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
