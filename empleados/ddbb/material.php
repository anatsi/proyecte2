<?php
//llamamos a la clase db encargada de la conexion contra la base de datos.
require_once 'db.php';
/**
 * Clase empleados encargada de hacer las consultas contra esta tabla de la db
 */
class Material extends db
{
  //la funcion de construct llama a la funcion de construct de db para la conexion
  function __construct()
  {
    parent::__construct();
  }

   //Fonction para insertar material
  function editarMaterial($empleados, $pentalon,$fecha, $talla,$cantidad){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO material_entregado(id, empleado,materiales,fecha_entrega,talla,cantidad) VALUES (null, ".$empleados.",'".$pentalon."','".$fecha."','".$talla."', '".$cantidad."')";
   //Realizamos la consulta utilizando la funcion creada en db.php
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      return true;
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


  //funcion encargada de borrar un empleado
   function BorrarMaterial($id){
      $sql="DELETE FROM material_entregado WHERE empleado=".$id;
      $borrar=$this->realizarConsulta($sql);
      if ($borrar=!NULL) {
        return true;
      }else {
        return false;
      }
    }

}
?>
