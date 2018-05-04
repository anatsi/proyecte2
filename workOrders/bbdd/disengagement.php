<?php
/**
 * Clase encargada de las consultas a la tabla movimientos.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Disengagement extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  //SACAR TODOS LOS MOVIMIENTOS
  function listaDisengagement(){
    //Construimos la consulta
    $sql="SELECT * from disengagement ORDER BY id desc";
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
  function cuentaListaDisengagement(){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from disengagement";
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
  function listaDisengagementFiltro($b){
    //Construimos la consulta
    $sql="SELECT * from disengagement WHERE concat(bastidor, construccion, fecha, hora, tamano, tipo, ruido, derecha, izquierda, derechaR, izquierdaR, usuario) LIKE '%".$b."%' ORDER BY id desc";
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
  function cuentaListaDisengagementFiltro($b){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from disengagement WHERE concat(bastidor, construccion, fecha, hora, tamano, tipo, ruido, derecha, izquierda, derechaR, izquierdaR, usuario) LIKE '%".$b."%'";
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
