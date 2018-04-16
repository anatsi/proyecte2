<?php
/**
 * Clase encargada de las consultas a la tabla reallocation.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Reallocation extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  function listaReallocation(){
    //Construimos la consulta
    $sql="SELECT * FROM reallocation ORDER BY id DESC";
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

  function listaReallocationFiltrados($b){
    //Contruimos la consulta.
    $sql = "SELECT * FROM reallocation WHERE concat(bastidor, fecha, hora, destino, usuario) LIKE '%".$b."%' ORDER BY id DESC";
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


  function cuentaListaReallocation(){
    //Construimos la consulta
    $sql="SELECT count(*) as recuento FROM reallocation";
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

  function cuentaListaReallocationFiltrados($b){
    //Contruimos la consulta.
    $sql = "SELECT count(*) as recuento FROM reallocation WHERE concat(bastidor, fecha, hora, destino, usuario) LIKE '%".$b."%'";
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
