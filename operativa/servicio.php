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
    $dia_manana = date('d')+1;
    $mes=date('m');
    $numerodias=date('t');
    if ($dia_manana>$numerodias) {
      $dia_manana=1;
      if ($mes!=12) {
        $mes=$mes+1;
      }else {
        $mes=1;
      }
    }
    $ano = date('Y');
    $fecha=$ano."-".$mes."-".$dia_manana;
    //Construimos la consulta
    $sql="SELECT * from servicios WHERE f_inicio<='".$fecha."' AND f_fin IS NULL ORDER BY f_inicio desc";
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
  function nuevoServicio($descripcion, $modelos, $recursos, $finicio, $cliente, $responsable, $telefono, $correo, $csup, $crrhh, $caf, $cdo, $relacion, $qps1, $qps2, $img1, $img2, $video1, $video2){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO servicios(id, descripcion, modelos, recursos, f_inicio, f_fin, id_cliente, responsable, telefono, correo, com_supervisor, com_rrhh, com_admin_fin, com_depto, relacion, qps1, qps2, img1, img2, video1, video2)
    VALUES (NULL, '".$descripcion."', '".$modelos."', ".$recursos.", '".$finicio."', NULL, ".$cliente.", '".$responsable."', ".$telefono.", '".$correo."', '".$csup."', '".$crrhh."', '".$caf."',
     '".$cdo."', '".$relacion."', '".$qps1."', '".$qps2."', '".$img1."', '".$img2."', '".$video1."', '".$video2."')";
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
    $sql="SELECT * from servicios WHERE f_inicio<='".$fecha."' AND f_fin IS NULL ORDER BY f_inicio desc";
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
  function modificarInfo($servicio, $inicio, $fin, $suelto, $desc, $modelos, $responsable, $tel, $correo){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO mod_info(id, servicio, inicio, fin, suelto, descripcion, modelos, responsable, telefono, correo)
    VALUES (NULL, ".$servicio.", '".$inicio."', '".$fin."', '".$suelto."', '".$desc."', '".$modelos."', '".$responsable."', ".$tel.", '".$correo."')";
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
    $sql="SELECT s.*, c.nombre
          FROM servicios s INNER JOIN cliente c ON s.id_cliente=c.id
          WHERE f_fin<= '".$fecha."' AND CONCAT(s.descripcion, s.modelos, c.nombre, s.responsable) LIKE '%".$filtro."%'";
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

  //funcion para sacar las modificaciones de informacion de un servicio
  function mod_info($id){
    //Construimos la consulta
     $sql="SELECT *, replace(concat(inicio, suelto), '0000-00-00', '') AS fecha FROM mod_info WHERE servicio=".$id." ORDER BY fecha desc";
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
//funcion para sacar las modificaciones de informacion de un servicio
function dias_recursos($id){
  //Construimos la consulta
   $sql="SELECT *, replace(concat(inicio, suelto), '0000-00-00', '') AS fecha FROM dias_recursos WHERE servicio=".$id." ORDER BY fecha desc";
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

//funcion para actualizar los comentarios.
function ActualizarComentarios($id, $csup, $crrhh, $caf, $cdo){
  $sql="UPDATE servicios
        SET com_supervisor='".$csup."', com_rrhh='".$crrhh."', com_admin_fin='".$caf."', com_depto='".$cdo."'
        WHERE id=".$id.";";
  $comentarios=$this->realizarConsulta($sql);
  if ($comentarios=!false) {
       return true;
  }else {
       return false;
  }
}

function ServicioRelacion($id){
//Construimos la consulta
$sql="SELECT descripcion, relacion as id_relacionada,
(SELECT descripcion FROM servicios s2 WHERE s2.id=s1.relacion) AS relacionada,
(SELECT s3.f_inicio FROM servicios s3 WHERE s3.id=".$id." or s3.id=s1.relacion ORDER BY s3.f_inicio DESC LIMIT 1) AS inicio,
(SELECT s4.f_fin FROM servicios s4 WHERE s4.id=".$id." and s4.f_fin is not null or s4.id=s1.relacion and s4.f_fin is not null ORDER BY s4.f_fin ASC LIMIT 1) AS fin
FROM servicios s1
WHERE id=".$id;
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

//funcion para sacar la lista para el excel de resumen
function listaResumen($fin, $inicio){
  //Construimos la consulta
   $sql="SELECT * FROM servicios WHERE f_inicio<='".$fin."' AND f_fin>='".$inicio."' OR f_inicio<='".$fin."' AND f_fin is null";
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

//funcion para sacar las modificaciones de informacion de un servicio para el resumen
function infoResumen($id, $inicio, $fin){
  //Construimos la consulta
   $sql="SELECT * FROM mod_info
          WHERE servicio=".$id." and suelto between '".$inicio."' AND '".$fin."'
          or servicio=".$id." and inicio<='".$fin."' and fin = '0000-00-00' and suelto = '0000-00-00'
          OR servicio=".$id." and inicio<='".$fin."' and fin between '".$inicio."' and '".$fin."' and suelto = '0000-00-00'";
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

//funcion para sacar las modificaciones de recursos de un servicio para el resumen
function diasRecursosResumen($id, $inicio, $fin){
  //Construimos la consulta
   $sql="SELECT * FROM dias_recursos
          WHERE servicio=".$id." and suelto between '".$inicio."' AND '".$fin."'
          or servicio=".$id." and inicio<='".$fin."' and fin = '0000-00-00' and suelto = '0000-00-00'
          OR servicio=".$id." and inicio<='".$fin."' and fin between '".$inicio."' and '".$fin."' and suelto = '0000-00-00'";
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
