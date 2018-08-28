<?php
  //llamamos a la clase db encargada de la conexion contra la base de datos.
  require_once 'db.php';

  /**
   * Clase encargada de las consultas a la tabla incapacidad_temporal
   */
  class Empleados extends db
  {

    //la funcion de construct llama a la funcion de construct de db para la conexion
    function __construct()
    {
      parent::__construct();
    }

    //funcion para listar todos los empleados de la bbdd
    function listaEmpleados(){
      //Construimos la consulta
      $sql="SELECT * from empleados where ett=0 order by alta";

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

    //funcion para listar los empleados filtrados
    function listaFiltrados($b){
      //Construimos la consulta
      $sql="SELECT * from empleados where ett=0 and concat(nombre, ' ', apellidos, ' ', user) like '%".$b."%' order by alta  ";
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

    //funciones para activar y desactivar empleados
    function ActivarEmpleado($id, $user){
      //Fecha y hora actual
      $fecha = date("Y-m-d H:i:s");
      $sql="UPDATE empleados SET alta=0, fecha_mod ='".$fecha."', usuario_mod='".$user."' where id=".$id;
      $finalizarAct=$this->realizarConsulta($sql);
      if ($finalizarAct=!false) {
           return true;
      }else {
           return false;
      }
    }

    function DesactivarEmpleado($id, $user){
      //Fecha y hora actual
      $fecha = date("Y-m-d H:i:s");
      $sql="UPDATE empleados SET alta=1, fecha_mod='".$fecha."', usuario_mod='".$user."' where id=".$id;
      $finalizarAct=$this->realizarConsulta($sql);
      if ($finalizarAct=!false) {
           return true;
      }else {
           return false;
      }
    }

    //funciones para activar la incapacidad temporal

     function IncapacidadId($id,$usuario){
          //Fecha y hora actual
         $fecha = date("Y-m-d H:i:s");
       //Construimos la consulta
         $sql="SELECT incapa_temporal FROM empleados WHERE id=".$id;
         //Realizamos la consulta
         $resultado = $this->realizarConsulta($sql);
         $almacena = $resultado->fetch_assoc();
          $sacar = current($almacena);

         if($sacar==0){

           $sql="UPDATE empleados SET incapa_temporal =1, fecha_mod='".$fecha."', usuario_mod='".$usuario."' WHERE id=".$id;
             $actIncapacidad=$this->realizarConsulta($sql);
         }else{
              $sql="UPDATE empleados SET incapa_temporal =0, fecha_mod='".$fecha."', usuario_mod='".$usuario."' WHERE id=".$id;
             $actIncapacidad=$this->realizarConsulta($sql);
         }
         if ($actIncapacidad=!false){
                  return true;
         }else{
                  return false;
             }
         }

         //funcion para activar las vaciones

          function DevacionesId($id, $usuario){
             //Fecha y hora actual
             $fecha = date("Y-m-d H:i:s");
             //Construimos la consulta
             $sql="SELECT vacaciones FROM empleados WHERE id=".$id;
             //Realizamos la consulta
             $resultado = $this->realizarConsulta($sql);
             $almacena = $resultado->fetch_assoc();
             $sacar = current($almacena);

             if($sacar==1){

               $sql="UPDATE empleados SET vacaciones =0, fecha_mod='".$fecha."', usuario_mod='".$usuario."' WHERE id=".$id;
                 $actVacaciones=$this->realizarConsulta($sql);
             }else{
                  $sql="UPDATE empleados SET vacaciones =1, fecha_mod='".$fecha."', usuario_mod='".$usuario."' WHERE id=".$id;
                 $actVacaciones=$this->realizarConsulta($sql);
             }
             if ($actVacaciones=!false){
                  return true;
             }else{


                  return false;
           }
         }

         //funcion para insertar un nuevo empleado en la base de datos.

      function nuevoEmpleado($nombre, $apellidos, $user, $alta, $telefono,$password){
       //realizamos la consuta y la guardamos en $sql
       $sql="INSERT INTO empleados(id, nombre,apellidos,user,alta, telefono,password,ett) VALUES (null, '".$nombre."', '".$apellidos."', '".$user."',".$alta.",".$telefono.",'".$password."', 0)";

       //Realizamos la consulta utilizando la funcion creada en db.php
       $resultado=$this->realizarConsulta($sql);
       if($resultado!=false){
         return true;
       }else{
         return null;
       }
     }

       //Fonction para insertar empleado ETT
      function nuevoEmpleadoEtt($nombre, $apellidos,$alta,$ett){
        //realizamos la consuta y la guardamos en $sql
        $sql="INSERT INTO empleados(id, nombre,apellidos,alta,ett) VALUES (null, '".$nombre."','".$apellidos."','".$alta."', '".$ett."')";
       //Realizamos la consulta utilizando la funcion creada en db.php
        $resultado=$this->realizarConsulta($sql);
        if($resultado!=false){
          return true;
        }else{
          return null;
        }
      }

      // Ultimo empleado para sacar su id que tenemos que insertar en la tabla fecha
      function ultimoId(){
        //Construimos la consulta
        $sql="SELECT id from empleados order by id desc limit 1";
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

       //Fonction para comprobar si existe un usuario con el mismo user
       function nuevoUsuario($user){
         //Construimos la consulta
         $sql="SELECT * from empleados WHERE user='".$user."'";

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



   //sacar empleado dependiendo del id
   function EmpleadoId($id){
     //Construimos la consulta
     $sql="SELECT * from empleados WHERE id=".$id;

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


   function editarEmpleado($id, $nombre, $apellidos, $tel){
     $sql="UPDATE empleados SET nombre ='".$nombre."', apellidos='".$apellidos."', telefono=".$tel." WHERE id=".$id;
     $finalizarAct=$this->realizarConsulta($sql);
     if($finalizarAct=!false){
          return true;
     }else{
          return false;
     }
   }

     function darBaja($id, $selbaja,$usuario){
       //Fecha y hora actual

       $fecha = date("Y-m-d H:i:s");

       $sql="UPDATE empleados SET incapa_temporal =".$selbaja.", fecha_mod='".$fecha."', usuario_mod='".$usuario."' WHERE id=".$id;
       $actIncapacidad=$this->realizarConsulta($sql);

       if($actIncapacidad=!false){
              return true;
       }else{
              return false;
       }

     }
   //funcion encargada de borrar un empleado
    function BorrarEmpleado($id){
       $sql="DELETE FROM empleados WHERE id=".$id;
       $borrar=$this->realizarConsulta($sql);
       if ($borrar=!NULL) {
         return true;
       }else {
         return false;
       }
     }
  }
?>
