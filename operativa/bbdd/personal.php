<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once 'db.php';
/**
 * Clase empleados encargada de hacer las consultas contra esta tabla de la db
 */
class Personal extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

//funcion para insertar un nuevo empleado para un servicio.
function nuevoPersonal($servicio, $empleado, $turno, $dia, $usuario){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO personal_servicios(id, servicio, empleado, turno, dia, usuario) VALUES (null, ".$servicio.", '".$empleado."', '".$turno."', '".$dia."', '".$usuario."')";
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

  //funcion encargada de borrar el personal de una actividad para un dia
   function eliminarPersonalTurno($id, $dia, $turno){
      $sql="DELETE FROM personal_servicios WHERE servicio=".$id." AND dia = '".$dia."' AND turno = '".$turno."'";
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

  //funcion para listar los empleados de un dia para un servicio
  function personalHoy($dia, $turno){
    //Construimos la consulta
    $sql="SELECT distinct empleado from personal_servicios where dia='".$dia."' AND turno LIKE '".$turno."' AND empleado != ''";
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


}

 ?>
