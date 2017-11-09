<?php
//llamamos a la clase de db encarada de la conexion a la base de datos.
require_once 'db.php';
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
  function nuevoServicio($descripcion, $modelos, $recursos, $finicio, $cliente, $responsable, $telefono, $correo, $csup, $crrhh, $caf, $cdo, $relacion, $archivo1, $archivo2, $archivo3){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO servicios(id, descripcion, modelos, recursos, f_inicio, f_fin, id_cliente, responsable, telefono, correo, com_supervisor, com_rrhh, com_admin_fin, com_depto, relacion, archivo1, archivo2, archivo3)
    VALUES (NULL, '".$descripcion."', '".$modelos."', ".$recursos.", '".$finicio."', NULL, ".$cliente.", '".$responsable."', ".$telefono.", '".$correo."', '".$csup."', '".$crrhh."', '".$caf."',
     '".$cdo."', '".$relacion."', '".$archivo1."', '".$archivo2."', '".$archivo3."')";
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

function ServicioId($id){
//Construimos la consulta
$sql="SELECT * from servicios WHERE id=".$id;
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

//funcion para añadir la fecha de fin cuando se finaliza la actividad
function FinalizarActividad($id, $fecha, $fin){
  $sql="UPDATE servicios SET f_fin='".$fecha."', com_fin='".$fin."' WHERE id='".$id."'";
  $finalizarAct=$this->realizarConsulta($sql);
  if ($finalizarAct=!false) {
       return true;
  }else {
       return false;
  }
}
//funcion para cancrlar una acctividad
function CancelarActividad($id, $fecha)
{
  $sql="UPDATE servicios SET f_fin='".$fecha."', cancelado='true' WHERE id='".$id."'";
  $cancelarAct=$this->realizarConsulta($sql);
  if ($cancelarAct=!false) {
    return true;
  }else {
    return false;
  }
}

//funcion para poner la relacion en una actividad
function RelacionActividad($id1, $id2){
  $sql="UPDATE servicios SET relacion='".$id2."' WHERE id='".$id1."'";
  $finalizarAct=$this->realizarConsulta($sql);
  if ($finalizarAct=!false) {
       return true;
  }else {
       return false;
  }
}


    //funcion para modificar la informacion de un servicio
  function modificarInfo($servicio, $inicio, $fin, $suelto, $desc, $modelos, $responsable, $tel, $correo, $csup, $crrhh, $caf, $cdo){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO mod_info(id, servicio, inicio, fin, suelto, descripcion, modelos, responsable, telefono, correo, com_supervisor, com_rrhh, com_admin_fin, com_depto)
    VALUES (NULL, ".$servicio.", '".$inicio."', '".$fin."', '".$suelto."', '".$desc."', '".$modelos."', '".$responsable."', ".$tel.", '".$correo."', '".$csup."', '".$crrhh."', '".$caf."', '".$cdo."')";

    echo $sql;
    //Realizamos la consulta utilizando la funcion creada en db.php
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      return true;
    }else{
      return null;
    }
  }

  //funcion para listar los servicios finalizados
  function listaFinalizados(){
    //cogemos la fecha de hoy para compararla con lo que vamos a sacar.
    $fecha=date("Y-m-d");
    //Construimos la consulta
    $sql="SELECT * from servicios WHERE f_fin<='".$fecha."'";
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

  //funcion para listar los servicios finalizados filtrados
  function listaFiltrados($filtro){
    //cogemos la fecha de hoy para compararla con lo que vamos a sacar.
    $fecha=date("Y-m-d");
    //Construimos la consulta
    $sql="SELECT * FROM servicios s INNER JOIN cliente c ON s.id_cliente=c.id
          WHERE s.descripcion LIKE '%".$filtro."%' AND f_fin<= '".$fecha."' OR s.modelos LIKE '%".$filtro."%'  AND f_fin<= '".$fecha."'
          OR c.nombre LIKE '%".$filtro."%'  AND f_fin<= '".$fecha."' ;";
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
