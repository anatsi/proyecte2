<?php
//llamamos a la clase de db encarada de la conexion a la base de datos.
require_once '../db.php';
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
  function nuevoRecurso($servicio, $total, $tm, $tt, $tn, $tc, $o1, $i1, $f1, $o2, $i2, $f2, $o3, $i3, $f3, $o4, $i4, $f4, $o5, $i5, $f5){
    //realizamos la consuta y la guardamos en $sql
    $sql="INSERT INTO recursos(id, servicio, total, tm, tt, tn, tc, otro1, inicio1, fin1, otro2, inicio2, fin2, otro3, inicio3, fin3, otro4, inicio4, fin4, otro5, inicio5, fin5)
    VALUES(NULL, ".$servicio.", '".$total."', '".$tm."', '".$tt."', '".$tn."', '".$tc."', '".$o1."', '".$i1."', '".$f1."', '".$o2."', '".$i2."', '".$f2."', '".$o3."',
     '".$i3."', '".$f3."', '".$o4."', '".$i4."', '".$f4."', '".$o5."', '".$i5."', '".$f5."')";
    //Realizamos la consulta utilizando la funcion creada en db.php
    $resultado=$this->realizarConsulta($sql);
    if($resultado!=false){
      return true;
    }else{
      return null;
    }
  }
}

 ?>
