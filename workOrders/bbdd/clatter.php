<?php
/**
 * Clase encargada de las consultas a la tabla Clatter.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Clatter extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  function listaClatter(){
    //Construimos la consulta
    $sql="SELECT * FROM clatter ORDER BY id DESC";
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

  function listaClatterFiltrados($b)
  {
    //Contruimos la consulta.
    $sql = "SELECT * FROM clatter WHERE concat(bastidor, clatter, fecha, hora, usuario) LIKE '%".$b."%' ORDER BY id DESC";
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


  function cuentaListaClatter(){
    //Construimos la consulta
    $sql="SELECT count(*) as recuento FROM clatter";
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

  function cuentaListaClatterFiltrados($b)
  {
    //Contruimos la consulta.
    $sql = "SELECT count(*) as recuento FROM clatter WHERE concat(bastidor, clatter, fecha, hora, usuario) LIKE '%".$b."%'";
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
