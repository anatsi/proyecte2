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

    //funcion para listar materiales
    function listaMaterial(){
      //Construimos la consulta
      $sql="SELECT * from material_entregado";
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

    //Fonction para sacar Nombre de la BD empleados y muestrarlo en la tabla material

  function materialFiltrados($b){
    //Construimos la consulta
    $sql="SELECT e.id, nombre, apellidos, materiales,fecha_entrega,talla, cantidad
     FROM empleados e inner join material_entregado m on e.id = m.empleado
     WHERE concat(nombre, ' ', apellidos) like '%".$b."%' order by nombre";
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

  // funcion para cambiar el numero al material correspondiente
   function ConverseMaterial($id){
      //Construimos la consulta
      $sql= "SELECT tipo_material from material where id=".$id;
      //Realizamos la consulta
        $resultado = $this->realizarConsulta($sql);
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


    //Fonction para recuperar los datos de la base de dato
 function MaterialId($id,$indice){
    //Construimos la consulta
    $sql="SELECT* FROM material_entregado
     WHERE materiales=$indice and  empleado=$id order by id desc limit 1";
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


}

 ?>
