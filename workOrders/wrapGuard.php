<?php
/**
 * Clase encargada de las consultas a la tabla movimientos.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class WrapGuard extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }
  //SACAR TODOS LOS VINS
  function listaWrapGuard(){
    //Construimos la consulta
    $sql="SELECT * from wrap_guard ORDER BY id desc";
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

  //SACAR EL NUMERO TOTAL DE VINS ESCANEADOSS
  function cuentaListaWrapGuard(){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from wrap_guard";
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
  function listaWrapFiltrados($b){
    //Construimos la consulta
    $sql="SELECT * from wrap_guard WHERE concat(bastidor, usuario1, usuario2, modelo, destino, fecha, hora, repetido) LIKE '%".$b."%' ORDER BY id desc";
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

  //SACAR EL NUMERO TOTAL DE VINS FILTRADOS
  function cuentaListaWrapFiltrados($b){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from wrap_guard WHERE concat(bastidor, usuario1, usuario2, modelo, destino, fecha, hora, repetido) LIKE '%".$b."%'";
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
