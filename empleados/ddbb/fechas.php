<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once 'db.php';
/**
 * Clase empleados encargada de hacer las consultas contra esta tabla de la db
 */
class Fechas extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
}
//Fonction para filtrar fechas
 function fechaFiltrados($b){
    //Construimos la consulta
    $sql="SELECT e.id, nombre, apellidos, alta, telefono, pase_ford, medico, carnet_conducir, dni, conducir_ford, nacimiento from empleados e inner join fechas f on e.id = f.empleado
     where concat(nombre, ' ', apellidos) like '%".$b."%' order by alta, pase_ford";
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

  //Fonction para insertar fechas
function insertarFechas($empleados, $cadPassFord,$cadDni,$cadPerm,$cadPermFord, $revmedico,$nacim){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO fechas(id, empleado,pase_ford,dni,carnet_conducir,conducir_ford,medico,nacimiento) VALUES (null, ".$empleados.",'".$cadPassFord."','".$cadDni."', '".$cadPerm."','".$cadPermFord."','".$revmedico."','".$nacim."')";
  echo $sql;
 //Realizamos la consulta utilizando la funcion creada en db.php
  $resultado=$this->realizarConsulta($sql);
  if($resultado!=false){
    return true;
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

//Para insertar editar la fechas
function editarFecha($id,$cadPassFord,$cadDni,$cadPerm,$cadPermFord, $revmedico,$nacim){
  //realizamos la consuta y la guardamos en $sql
  $sql="UPDATE fechas SET pase_ford='".$cadPassFord."',dni='".$cadDni."',carnet_conducir='".$cadPerm."',conducir_ford='".$cadPermFord."',medico='".$revmedico."',nacimiento='".$nacim."'
  WHERE empleado=".$id;

 //Realizamos la consulta utilizando la funcion creada en db.php
  $finalizarAct=$this->realizarConsulta($sql);
  if($finalizarAct=!false){
       return true;
  }else{
       return false;
  }
}

//Fonction para sacar Nombre de la BD empleado y muestrarlo en la tabla fecha

  function sacar2(){
    //Construimos la consulta
    $sql="SELECT e.id, nombre, apellidos, alta, telefono, pase_ford, medico, carnet_conducir, dni, conducir_ford, nacimiento from empleados e inner join fechas f on e.id = f.empleado order by alta, pase_ford";
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

  //funcion encargada de borrar un empleado
   function BorrarFechas($id){
      $sql="DELETE FROM fechas WHERE empleado=".$id;
      $borrar=$this->realizarConsulta($sql);
      if ($borrar=!NULL) {
        return true;
      }else {
        return false;
      }
    }

}
?>
