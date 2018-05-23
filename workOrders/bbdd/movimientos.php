<?php
/**
 * Clase encargada de las consultas a la tabla movimientos.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbMovimientos.php';

class Movimientos extends dbMovimientos
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }
  //SACAR TODOS LOS MOVIMIENTOS
  function listaMovimientos(){
    //Construimos la consulta
    $sql="SELECT * from movimientos ORDER BY id desc";
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

  function listaMovimientosExcel(){
    //Construimos la consulta
    $sql="SELECT * from movimientos ORDER BY id desc LIMIT 12000";
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

  function cuentaListaMovimientos(){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from movimientos";
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
  function listaMovimientosFiltrados($b){
    //Construimos la consulta
    $sql="SELECT * from movimientos WHERE concat(bastidor, origen, fecha_origen, hora_origen, destino, fecha_destino, hora_destino, usuario, rol, lanzamiento) LIKE '%".$b."%' ORDER BY id desc";
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
  function listaMovimientosFiltradosExcel($b){
    //Construimos la consulta
    $sql="SELECT * from movimientos WHERE concat(bastidor, origen, fecha_origen, hora_origen, destino, fecha_destino, hora_destino, usuario, rol, lanzamiento) LIKE '%".$b."%' ORDER BY id desc LIMIT 12000";
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

  function cuentalistaMovimientosFiltrados($b){
    //Construimos la consulta
    $sql="SELECT count(*) as 'recuento' from movimientos WHERE concat(bastidor, origen, fecha_origen, hora_origen, destino, fecha_destino, hora_destino, usuario, rol, lanzamiento) LIKE '%".$b."%'";
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

  function RestarHoras($horaini,$horafin)
  {
    $horai=substr($horaini,0,2);
    $mini=substr($horaini,3,2);
    $segi=substr($horaini,6,2);

    $horaf=substr($horafin,0,2);
    $minf=substr($horafin,3,2);
    $segf=substr($horafin,6,2);

    $ini=((($horai*60)*60)+($mini*60)+$segi);
    $fin=((($horaf*60)*60)+($minf*60)+$segf);

    $dif=$fin-$ini;

    $difh=floor($dif/3600);
    $difm=floor(($dif-($difh*3600))/60);
    $difs=$dif-($difm*60)-($difh*3600);
    return date("H:i:s",mktime($difh,$difm,$difs));
  }

  function UltimoMovimiento($usuario, $id){
    //Construimos la consulta
    $sql="SELECT * from movimientos WHERE usuario='".$usuario."' AND error = 0 AND id > ".$id." ORDER BY id ASC LIMIT 1";
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


  function SumarHoras($horaini,$horafin)
  {
    $horai=substr($horaini,0,2);
    $mini=substr($horaini,3,2);
    $segi=substr($horaini,6,2);

    $horaf=substr($horafin,0,2);
    $minf=substr($horafin,3,2);
    $segf=substr($horafin,6,2);

    $ini=((($horai*60)*60)+($mini*60)+$segi);
    $fin=((($horaf*60)*60)+($minf*60)+$segf);

    $dif=$fin+$ini;

    $difh=floor($dif/3600);
    $difm=floor(($dif-($difh*3600))/60);
    $difs=$dif-($difm*60)-($difh*3600);
    return date("H:i:s",mktime($difh,$difm,$difs));
  }
}
 ?>
