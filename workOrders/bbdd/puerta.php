<?php
/**
 * Clase encargada de las consultas a la tabla radio.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Puerta extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  function listaPuerta(){
    //Construimos la consulta
    $sql="SELECT * FROM puerta ORDER BY id DESC";
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

  function listaPuertaFiltrados($b)
  {
    //Contruimos la consulta.
    $sql = "SELECT * FROM puerta WHERE concat(bastidor, derecha, izquierda, fecha, hora, usuario) LIKE '%".$b."%' ORDER BY id DESC";
    //realizamos la consulta.
    $resultado=$this->realizarConsulta($sql);
    if ($resultado!=null) {
      //Montamos la tabla de resultados
      $tabla=[];
      while ($fila=$resultado->fetch_assoc()) {
        $tabla[]=$fila;
      }
      return $tabla;
    }else {
      return null;
    }
  }


  function cuentaListaPuerta(){
    //Construimos la consulta
    $sql="SELECT count(*) as recuento FROM puerta";
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

  function cuentaListaPuertaFiltrados($b)
  {
    //Contruimos la consulta.
    $sql = "SELECT count(*) as recuento FROM puerta WHERE concat(bastidor, derecha, izquierda, fecha, hora, usuario) LIKE '%".$b."%'";
    //realizamos la consulta.
    $resultado=$this->realizarConsulta($sql);
    if ($resultado!=null) {
      //Montamos la tabla de resultados
      $tabla=[];
      while ($fila=$resultado->fetch_assoc()) {
        $tabla[]=$fila;
      }
      return $tabla;
    }else {
      return null;
    }
  }
}
 ?>
