<?php
//llamamos a la clase de db encarada de la conexion a la base de datos.
require_once '../db.php';
/**
 * Clase servicios encargada de las consultas hacia esta tabla de la db.
 */
class Servicio extends db
{
  //la clase consruct llama al construct de db, encargado a la conexion a la db.
  function __construct()
  {
    parent::__construct();
  }

  //funcion para sacar los servicios para mañana para index de operativa
  function ServiciosTomorrow()
  {
    //cogemos la fecha de mañana para compararla con lo que vamos a sacar.
    $dia_manana = date('d',time()+84600);
    $mes_ano = date('Y-m');
    $fecha=$mes_ano."-".$dia_manana;
    //Construimos la consulta
    $sql="SELECT * from servicios WHERE f_inicio<='".$fecha."' AND f_fin IS NULL";
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

    //funcion para insertar un nuevo servicio en la base de datos.
  function nuevoServicio($descripcion, $modelos, $recursos, $finicio, $cliente, $responsable, $telefono, $correo, $csup, $crrhh, $caf){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO servicios(id, descripcion, modelos, recursos, f_inicio, f_fin, id_cliente, responsable, telefono, correo, com_supervisor, com_rrhh, com_admin_fin)
    VALUES (NULL, '".$descripcion."', '".$modelos."', ".$recursos.", '".$finicio."', NULL, ".$cliente.", '".$responsable."', ".$telefono.", '".$correo."', '".$csup."', '".$crrhh."', '".$caf."')";
    //Realizamos la consulta utilizando la funcion creada en db.php
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      return true;
    }else{
      return null;
    }
  }

  //funcion para listar los servicios de hoy en el index de la operativa de servicios
  function listaServiciosHoy(){
    //cogemos la fecha de hoy para compararla con lo que vamos a sacar.
    $fecha=date("Y-m-d");
    //Construimos la consulta
    $sql="SELECT * from servicios WHERE f_inicio<='".$fecha."' AND f_fin IS NULL";
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

  //funcion para sacar el ultimo servicio insertado en a BBDD
  function ultimoServicio(){
    //Construimos la consulta
     $sql="SELECT * from servicios ORDER BY id DESC LIMIT 1";
     //Realizamos la consulta
     $resultado=$this->realizarConsulta($sql);
     if($resultado!=null){
       //Montamos la tabla de resultado
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
