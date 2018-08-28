
<?php
//incluimos todas las clases necesarias e iniciamos sus objetos.
    require_once '../ddbb/sesiones.php';
    require_once '../ddbb/users.php';
    require_once './ddbb/empleados.php';
    require_once './ddbb/fechas.php';
    require_once './ddbb/material.php';


    $usuario=new User();
    $sesion=new Sesiones();
    $empleado= new Empleados();
    $fecha= new Fechas();
    $material= new Material();


    $borrar=$material->BorrarMaterial($_GET['e']);
    $borrar1=$fecha->BorrarFechas($_GET['e']);
    $borrar2=$empleado->BorrarEmpleado($_GET['e']);


   if ($borrar==false || $borrar1==false || $borrar2==false){
      //si no se ha podido actualizar, avisamos al usaurio
      ?>
      <script type="text/javascript">
        alert('Error al borrar el empleado');
        window.location='index.php';
      </script>
      <?php
    }else{
      //si se ha editado correctamente, devolvemos el usuario a inicio
      ?>
        <script type="text/javascript">
          alert('Empleado borrado con exito.');
          window.location='index.php';
        </script>
      <?php
    }

 ?>
