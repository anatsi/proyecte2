<?php
/**
 * Clase encargada de las consultas a la tabla ticket.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Ticket extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  //SACAR TODOS LOS TICKETS
  function listaTickets(){
    //Construimos la consulta
    $sql="SELECT * from ticket ORDER BY id desc";
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

  //funcion para resolver un ticket
  function Resolver($id, $resuelto, $comentario){
    $sql="UPDATE ticket SET resuelto='".$resuelto."', comentario='".$comentario."' WHERE id=".$id;
    $finalizarAct=$this->realizarConsulta($sql);
    if ($finalizarAct=!false) {
         return true;
    }else {
         return false;
    }
  }

  //sacar empleado dependiendo del id
  function TicketId($id){
    //Construimos la consulta
    $sql="SELECT * from ticket WHERE id=".$id;
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
}
 ?>
