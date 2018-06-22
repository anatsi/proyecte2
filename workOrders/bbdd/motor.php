<?php
/**
 * Clase encargada de las consultas a la tabla movimientos.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Motor extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  //SACAR TODOS LOS MOVIMIENTOS
  function listaMotor(){
    //Construimos la consulta
    $sql="SELECT * from motor WHERE leido = 1 ORDER BY id desc";
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

  //SACAR EL NUMERO TOTAL DE MOVIMIENTOS
  function cuentaListaMotor(){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from motor WHERE leido = 1 ";
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

  //SACAR TODOS LOS MOVIMIENTOS FILTRADOS
  function listaMotorFiltro($b){
    //Construimos la consulta
    $sql="SELECT * from motor WHERE leido = 1 AND concat(bastidor, motor) LIKE '%".$b."%' ORDER BY id desc";
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

  //SACAR EL NUMERO TOTAL DE MOVIMIENTOS FILTRADOS
  function cuentaListaMotorFiltro($b){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from motor WHERE leido = 1 AND concat(bastidor, motor) LIKE '%".$b."%'";
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
