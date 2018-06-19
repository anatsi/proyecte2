<?php
/**
 * Clase encargada de las consultas a la tabla aullido.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Aullido extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  function listaAullido(){
    //Construimos la consulta
    $sql="SELECT * FROM aullido ORDER BY id DESC";
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

  function listaAullidoFiltrados($b)
  {
    //Contruimos la consulta.
    $sql = "SELECT * FROM aullido WHERE concat(bastidor, aullido, fecha, hora, usuario) LIKE '%".$b."%' ORDER BY id DESC";
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


  function cuentaListaAullido(){
    //Construimos la consulta
    $sql="SELECT count(*) as recuento FROM aullido";
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

  function cuentaListaAullidoFiltrados($b)
  {
    //Contruimos la consulta.
    $sql = "SELECT count(*) as recuento FROM aullido WHERE concat(bastidor, aullido, fecha, hora, usuario) LIKE '%".$b."%'";
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
