<?php
  //llamamos a la clase db encargada de la conexion contra la base de datos.
  require_once 'db.php';

  /**
   * Clase encargada de las consultas a la tabla incapacidad_temporal
   */
  class Incapacidad extends db
  {

    //la funcion de construct llama a la funcion de construct de db para la conexion
    function __construct()
    {
      parent::__construct();
    }

    //funcion para listar los las incapacidades temporales
    function listaIncapacidad(){
      //Construimos la consulta
      $sql="SELECT * from incapacidad_temporal";
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
      $sql= "SELECT tipo from incapacidad_temporal where id=".$id;
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


  }
?>
