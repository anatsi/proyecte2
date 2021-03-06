
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
    $empleado=new Empleados();
    $incapacidad = new Incapacidad();

    //guardamos lo que se quiere buscar en una variable
    $buscar = $_POST['b'];

    if(!empty($buscar)) {
        //si se ha enviado algo para buscar, llamamos a la funcion de abajo
          buscar($buscar);
    }else{
      //si no se ha enviado nada, sacamos toda la informacion


         echo "
         <h3><a title='Añadir empleado' href='nuevoEmpleado.php'><i class='material-icons' id='nuevoEmpleado'>group_add</i></a>
         <a href='excelEmpleados.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
         </h3>
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Apellidos</th>
            <th scope='col' id='thmod'>User</th>
            <th scope='col' id='thmod'>Telefono</th>
            <th scope='col' id='thmod'>RRHH</th>
            <th scope='col' id='thmod'>Fecha mod.</th>
            <th scope='col' id='thmod'>Alta</th>
            <th scope='col' id='thmod'>Vacaciones</th>
            <th scope='col' id='thmod'>Incapacidad</th>
            <th scope='col' id='thmod'>Opciones</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>";
            //llamamos a la funcion de listar todos los empleados
          $listaEmpleados=$empleado->listaEmpleados();
          //sacamos los empleados por pantalla en una tabla
          foreach ($listaEmpleados as $empleados){
             //Sacamos el numero de la incapacidad para pasar la fonction que le converte
             $change=$incapacidad->ConverseIncapacidadId($empleados['incapa_temporal']);
             $newDate = date("d/m/Y H:i:s", strtotime($empleados['fecha_mod'] ));
             //Al crear un usuario la fecha se queda en blanco
             $fecha="";


                //Cargar los valores de la base de datos sobre la tabla
             echo"
                <tr id='trmod'>
                  <td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$empleados['id']."' title='Editar información del empleado'>".$empleados['nombre']."</a></td>
                  <td data-label='Apellidos' id='tdmod'>".$empleados['apellidos']."</td>
                  <td data-label='User' id='tdmod'>".$empleados['user']."</td>
                  <td data-label='Telefono' id='tdmod'>".$empleados['telefono']."</td>
                  <td data-label='RRHH' id='tdmod'>".$empleados['usuario_mod']."</td>";

                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación
                if($empleados['fecha_mod']!=null){
                echo "<td data-label='Fecha mod.' id='tdmod'>".$newDate."</td>";
                }else{
                echo "<td data-label='Fecha mod.' id='tdmod'>".$fecha."</td>";
                }

       //Cerrar echo y buscar los valores de alta dónde está 1 o 0 cambiar por clear o done
                if ($empleados['alta']==0){
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>done</i></td>";
                }else{
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>clear</i> </td>";
                }
                //Cerrar echo y buscar los valores de vacacione dónde está 1 o 0 cambiar por clear o done
                if ($empleados['vacaciones']==0){
                echo "<td data-label='Vacaciones' id='tdmod'><i class='material-icons'>clear</i> </td>";
               }else{
                echo "<td data-label='Vacaciones' id='tdmod'><i class='material-icons'>done</i> </td>";
               }
                echo "<td data-label='Incapacidad' id='tdmod'>".$change['tipo']."</td>";
                //Poner icones para cada Opciones // labe=opciones tienen que estar en la primera opcion

                if ($empleados['alta']==0){
                  echo "<td data-label='Opciones ' id='tdmod'>";
                  echo "<a href='desactivarEmpleado.php?e=".$empleados['id']."'
                  title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
                  //Poner icono de avion y maleta para vaciones y solo si está dado de alta
                  if ($empleados['vacaciones']==0) {
                    echo "&nbsp;&nbsp;<a href='deVacaciones.php?e=".$empleados['id']."' title='Dar vacaciones'><i class='material-icons'>flight</i></a>";
                  }else {
                    echo"&nbsp;&nbsp;<a href='deVacaciones.php?e=".$empleados['id']."' title='Poner fin a las Vacaciones '><i class='material-icons'>work</i></a>";
                  }
                  // poner icono de incapacidad temporal  y solo si está dado de alta
                 if ($empleados['incapa_temporal']==0){
                       echo "&nbsp;&nbsp;<a href='darBaja.php?e=".$empleados['id']."' title='Incapacidad Temporal'><i class='material-icons'>healing</i></a>";
                 }else{
                       echo"&nbsp;&nbsp;<a href='incapacidad.php?e=".$empleados['id']."' title='Dar de alta'><i class='material-icons'>sentiment_satisfied_alt</i></a></td></tr>";
                 }
                }else {
                  echo "<td data-label='Opciones ' id='tdmod'>";
                  echo"&nbsp;&nbsp;<a href='activarEmpleado.php?e=".$empleados['id']."' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
                }


          }
        echo "</tbody></table></div>";
    }



    //funcion para sacar por pantalla los datos a buscar
    function buscar($b) {
      //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './ddbb/empleados.php';
      require_once './ddbb/incapacidad.php';
      $empleado=new Empleados();
      $incapacidad = new Incapacidad();
      //sacamos la lista de empleados que coinciden con el filtro
        $filtrados= $empleado->listaFiltrados($b);
        $filtro=base64_encode($b);
        echo "
        <h3><a title='Añadir empleado' href='nuevoEmpleado.php'><i class='material-icons' id='nuevoEmpleado'>group_add</i></a>
        <a href='excelEmpleados.php?b=".$filtro."' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
        </h3>
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Apellidos</th>
            <th scope='col' id='thmod'>User</th>
            <th scope='col' id='thmod'>Telefono</th>
            <th scope='col' id='thmod'>RRHH</th>
            <th scope='col' id='thmod'>Fecha mod.</th>
            <th scope='col' id='thmod'>Alta</th>
            <th scope='col' id='thmod'>Vacaciones</th>
            <th scope='col' id='thmod'>Incapacidad</th>
            <th scope='col' id='thmod'>Opciones</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>";
          foreach ($filtrados as $empleados){
             $change=$incapacidad->ConverseIncapacidadId($empleados['incapa_temporal']);
             $newDate = date("d/m/Y H:i:s", strtotime($empleados['fecha_mod'] ));
               //Al crear un usuario la fecha se queda en blanco
             $fecha="";

              //Cargar los valores de la base de datos sobre la tabla
             echo"
                <tr id='trmod'>
                  <td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$empleados['id']."' title='Editar información del empleado'>".$empleados['nombre']."</a></td>
                  <td data-label='Apellidos' id='tdmod'>".$empleados['apellidos']."</td>
                  <td data-label='User' id='tdmod'>".$empleados['user']."</td>
                  <td data-label='Telefono' id='tdmod'>".$empleados['telefono']."</td>
                  <td data-label='RRHH' id='tdmod'>".$empleados['usuario_mod']."</td>";
                  // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación
                if($empleados['fecha_mod']!=null){
                echo "<td data-label='Fecha mod.' id='tdmod'>".$newDate."</td>";
                }else{
                echo "<td data-label='Fecha mod.' id='tdmod'>".$fecha."</td>";
                }
        //Cerrar echo y buscar los valores de alta dónde está 1 o 0 cambiar por clear o done
                if ($empleados['alta']==0){
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>done</i></td>";
                }else{
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>clear</i> </td>";
                }
                //Cerrar echo y buscar los valores de vacacione dónde está 1 o 0 cambiar por clear o done
                if ($empleados['vacaciones']==0){
                echo "<td data-label='Vacaciones' id='tdmod'><i class='material-icons'>clear</i> </td>";
               }else{
                echo "<td data-label='Vacaciones' id='tdmod'><i class='material-icons'>done</i> </td>";
               }
                echo "<td data-label='Incapacidad' id='tdmod'>".$change['tipo']."</td>";


                //Poner icones para cada Opciones // labe=opciones tienen que estar en la primera opcion

                if ($empleados['alta']==0){
                  echo "<td data-label='Opciones ' id='tdmod'>";
                  echo "<a href='desactivarEmpleado.php?e=".$empleados['id']."'
                  title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
                  //Poner icono de avion y maleta para vaciones y solo si está dado de alta
                  if ($empleados['vacaciones']==0) {
                    echo "&nbsp;&nbsp;<a href='deVacaciones.php?e=".$empleados['id']."' title='Dar vacaciones'><i class='material-icons'>flight</i></a>";
                  }else {
                    echo"&nbsp;&nbsp;<a href='deVacaciones.php?e=".$empleados['id']."' title='Poner fin a las Vacaciones '><i class='material-icons'>work</i></a>";
                  }
                  // poner icono de incapacidad temporal  y solo si está dado de alta
                 if ($empleados['incapa_temporal']==0){
                       echo "&nbsp;&nbsp;<a href='darBaja.php?e=".$empleados['id']."' title='Incapacidad Temporal'><i class='material-icons'>healing</i></a>";
                 }else{
                       echo"&nbsp;&nbsp;<a href='incapacidad.php?e=".$empleados['id']."' title='Dar de alta'><i class='material-icons'>sentiment_satisfied_alt</i></a></td></tr>";
                 }
                }else {
                  echo "<td data-label='Opciones ' id='tdmod'>";
                  echo"&nbsp;&nbsp;<a href='activarEmpleado.php?e=".$empleados['id']."' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
                }

          }
        echo "</tbody></table></div>";
    }
     ?>

  </body>
</html>
