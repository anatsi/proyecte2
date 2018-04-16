<?php
//llamamos a la clase de db encarada de la conexion a la base de datos.
require_once 'db.php';
/**
 * Clase recursos encargada de las consultas a esta tabla de la DDBB.
 */
class Recursos extends db
{
  //la funcion construct llama al construct del padre encargad de la conexion a la DB
  function __construct()
  {
    parent::__construct();
  }

  //funcion encargada de insertar los recuros para un servicio en la ddbb
  function nuevoRecurso($servicio, $total, $tm, $tt, $tn, $tc, $o1, $i1, $f1, $o2, $i2, $f2, $o3, $i3, $f3, $o4, $i4, $f4, $o5, $i5, $f5, $o6, $i6, $f6){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO recursos(id, servicio, total, tm, tt, tn, tc, otro1, inicio1, fin1, otro2, inicio2, fin2, otro3, inicio3, fin3, otro4, inicio4, fin4, otro5, inicio5, fin5, otro6, inicio6, fin6)
    VALUES(NULL, ".$servicio.", '".$total."', '".$tm."', '".$tt."', '".$tn."', '".$tc."', '".$o1."', '".$i1."', '".$f1."', '".$o2."', '".$i2."', '".$f2."', '".$o3."',
     '".$i3."', '".$f3."', '".$o4."', '".$i4."', '".$f4."', '".$o5."', '".$i5."', '".$f5."', '".$o6."', '".$i6."', '".$f6."')";
    //Realizamos la consulta utilizando la funcion creada en db.php
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      return true;
    }else{
      return null;
    }
  }

  //funcion para sacar los recursos de un servicio determinado
  function RecursosId($id){
  //Construimos la consulta
  $sql="SELECT * FROM recursos WHERE servicio=".$id;
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

//funcion encargada de insertar los recuros modificados para un dia en concreto
function modundia($servicio, $dia, $inicio, $fin, $total, $tm, $tt, $tn, $tc, $o1, $i1, $f1, $o2, $i2, $f2, $o3, $i3, $f3, $o4, $i4, $f4, $o5, $i5, $f5,
 $o6, $i6, $f6){
  //realizamos la consuta y la guardamos en $sql
  $sql="INSERT INTO dias_recursos(id, servicio, inicio, fin, suelto, total, tm, tt, tn, tc, otro1, inicio1, fin1, otro2, inicio2, fin2, otro3, inicio3, fin3, otro4, inicio4, fin4, otro5, inicio5, fin5, otro6, inicio6, fin6)
  VALUES(NULL, ".$servicio.", '".$inicio."', '".$fin."','".$dia."' , '".$total."', '".$tm."', '".$tt."', '".$tn."', '".$tc."', '".$o1."', '".$i1."', '".$f1."',
   '".$o2."', '".$i2."', '".$f2."', '".$o3."', '".$i3."', '".$f3."', '".$o4."', '".$i4."', '".$f4."', '".$o5."',
    '".$i5."', '".$f5."', '".$o6."', '".$i6."', '".$f6."')";
    var_dump($sql);
  //Realizamos la consulta utilizando la funcion creada en db.php
  $resultado=$this->realizarConsulta($sql);
  if($resultado!=false){
    return true;
  }else{
    return null;
  }
}

//funcion para actualizar los recursos de la actividad de la tabla general
function ActualizarRecursosActividad($id, $total, $tm, $tt, $tn, $tc, $o1, $i1, $f1, $o2, $i2, $f2, $o3, $i3, $f3, $o4, $i4, $f4, $o5, $i5, $f5,
 $o6, $i6, $f6){
  $sql="UPDATE recursos SET total=".$total.", tm=".$tm.", tt=".$tt.", tn=".$tn.", tc=".$tc.",
  otro1=".$o1.", inicio1='".$i1."', fin1='".$f1."', otro2=".$o2.", inicio2='".$i2."', fin2='".$f2."',
  otro3=".$o3.", inicio3='".$i3."', fin3='".$f3."', otro4=".$o4.", inicio4='".$i4."', fin4='".$f4."',
  otro5=".$o5.", inicio5='".$i5."', fin5='".$f5."', otro6=".$o6.", inicio6='".$i6."', fin6='".$f6."' WHERE servicio=".$id;
  $finalizarAct=$this->realizarConsulta($sql);
  if ($finalizarAct=!false) {
       return true;
  }else {
       return false;
  }
}

}

 ?>