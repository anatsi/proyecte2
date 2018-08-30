<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
    <title></title>
    <!--ORDENAR TABLA-->
    <!--<script type="text/javascript" src="../js/jquery.min.js"></script>-->
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
    <script>
      $(function(){
        $("#tablamod").tablesorter();
      });
  </script>
  </head>
  <body>
  <?php
    //incluimos los archivos necesarios e inicializamos sus objetos

    require_once './ddbb/empleados.php';
    require_once './ddbb/incapacidad.php';
     require_once './ddbb/fechas.php';
     require_once './ddbb/material.php';
    $empleado=new Empleados();
    $incapacidad = new Incapacidad();
     $fecha = new Fechas();
     $material= new Material();

    //guardamos lo que se quiere buscar en una variable
    $buscar = $_POST['b'];

    if(!empty($buscar)){
        //si se ha enviado algo para buscar, llamamos a la funcion de abajo
          buscar($buscar);
    }else{
      //si no se ha enviado nada, sacamos toda la informacion
      echo "
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Material</th>
            <th scope='col' id='thmod'>Fecha de entrega</th>
            <th scope='col' id='thmod'>Talla</th>
            <th scope='col' id='thmod'>Cantidad</th>
            </tr>
        </thead>
        <tbody id='tbodymod'>";
           //llamamos a la consulta de listar material
            $listarMaterial= $material->listaMaterial();
         //sacamos los empleados por pantalla en una tabla
              foreach ($listarMaterial as $materiales){
                    //Sacamos el numero del material para pasar la fonction que le convierte
                $change=$material->ConverseMaterial($materiales['materiales']);

                  //Sacamos el id del empleado para sacar su nombre
                $changeEmpleo=$empleado->EmpleadoId($materiales['empleado']);

                $Date = date("d/m/Y", strtotime($materiales['fecha_entrega'] ));
                //Al crear material la fecha se queda en blanco



                echo "<tr id='trmod'>";


               //Cargar los valores de la base de datos sobre la tabla
                 //Cambiar el id al empleado corresondete y concater nombre y apellidos
                echo "<td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$materiales['id']."' title='Editar información del empleado'>".$changeEmpleo['nombre']." ".$changeEmpleo['apellidos']."</a></td>";
                 //Cambiar de numero al material correspondete
                echo "<td data-label='telefono' id='tdmod'>".$change['tipo_material']."</td>";
                echo "<td data-label='cadPassFord' id='tdmod'>".$Date."</td>";
                //Sacar de material  talla y cantidad
                echo "<td data-label='revMedico' id='tdmod'>".$materiales['talla']."</td>";
                echo "<td data-label='cadPerm' id='tdmod'>".$materiales['cantidad']."</td>";


                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación


           }
               echo "</tbody></table></div>";
    }

     //funcion para sacar por pantalla los datos a buscar
    function buscar($b){
      //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './ddbb/empleados.php';
      require_once './ddbb/material.php';

      $empleado=new Empleados();
      $material = new Material();
      //sacamos la lista de empleados que coinciden con el filtro
        $filtrados= $material->materialFiltrados($b);
        $filtro=base64_encode($b);
        echo "
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Material</th>
            <th scope='col' id='thmod'>Fecha de entrega</th>
            <th scope='col' id='thmod'>Talla</th>
            <th scope='col' id='thmod'>Cantidad</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>";
         //llamamos a la consulta de listar todas las fechas
           // $listaMaterial=$material->listaMaterial();
        //sacamos los empleados por pantalla en una tabla
           foreach ($filtrados as $materiales){
                //Sacamos el numero del material para pasar la fonction que le convierte
                $change=$material->ConverseMaterial($materiales['materiales']);

                  //Sacamos el id del empleado para sacar su nombre
                //$changeEmpleo=$empleado->EmpleadoId($materiales['empleado']);

                $Date = date("d/m/Y", strtotime($materiales['fecha_entrega'] ));
                //Al crear material la fecha se queda en blanco
                $fecha="";

                echo "<tr id='trmod'>";
                 //Cargar los valores de la base de datos sobre la tabla

                 //Cambiar el id al empleado corresondete y concater nombre y apellidos
                echo "<td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$materiales['id']."' title='Editar información del empleado'>".$materiales['nombre']." ".$materiales['apellidos']."</a></td>";
                 //Cambiar de numero al material correspondete
                echo "<td data-label='telefono' id='tdmod'>".$change['tipo_material']."</td>";
                echo "<td data-label='cadPassFord' id='tdmod'>".$Date."</td>";
                //Sacar de material  talla y cantidad
                echo "<td data-label='revMedico' id='tdmod'>".$materiales['talla']."</td>";
                echo "<td data-label='cadPerm' id='tdmod'>".$materiales['cantidad']."</td>";


                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación


           }
            echo "</tbody></table></div>";
    }

  ?>

  </body>
</html>
