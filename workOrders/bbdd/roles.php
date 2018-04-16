<?php
/**
 * Clase encargada de las consultas a la tabla movimientos.
 */

 //Llamamos a la clase db, encargada de la conexion.
 require_once 'dbJockeys.php';

class Roles extends dbJockeys
{
  //la funcion construct llama al construct de db, encargada de la conexión.
  function __construct()
  {
    parent::__construct();
  }

  function listaRolesFiltrados($inicio, $fin, $usuario, $rol){
    //Construimos la consulta
    $sql="SELECT * FROM roles WHERE fecha_inicio<='".$fin."' AND fecha_fin>='".$inicio."' AND usuario LIKE '".$usuario."' AND rol LIKE '".$rol."' OR fecha_inicio<='".$fin."' AND usuario LIKE '".$usuario."' AND rol LIKE '".$rol."' AND fecha_fin is null ORDER BY id DESC";
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
