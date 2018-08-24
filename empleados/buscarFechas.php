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
    $empleado=new Empleados();
    $incapacidad = new Incapacidad();
     $fecha = new Fechas();

    //guardamos lo que se quiere buscar en una variable
    $buscar = $_POST['b'];

    if(!empty($buscar)){
        //si se ha enviado algo para buscar, llamamos a la funcion de abajo
          buscar($buscar);
    }else{
      //si no se ha enviado nada, sacamos toda la informacion
      echo "
      <h3><a href='excelEmpleados.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
      </h3>
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Telefono</th>
            <th scope='col' id='thmod'>Alta</th>
            <th scope='col' id='thmod'>Pass Ford</th>
            <th scope='col' id='thmod'>Revision Medica</th>
            <th scope='col' id='thmod'>Permiso de Conducir</th>
            <th scope='col' id='thmod'>Caducidad DNI</th>
            <th scope='col' id='thmod'>Permio Conducir Ford</th>
            <th scope='col' id='thmod'>Nacimiento</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>";
          //llamamos a la consulta de listar todas las fechas
            $listaFecha=$fecha->sacar2();
         //sacamos los empleados por pantalla en una tabla
              foreach ($listaFecha as $fechas){
                 //Fonction para convertir fecha format dd/mm/aa
                 if ($fechas['pase_ford']!='0000-00-00' && $fechas['pase_ford']!=null) {
                   $Date1 = date("d/m/Y", strtotime($fechas['pase_ford'] ));
                 }else {
                   $Date1=' ';
                 }
                 if ($fechas['medico']!='0000-00-00' && $fechas['medico']!=null) {
                   $Date2 = date("d/m/Y", strtotime($fechas['medico'] ));
                 }else {
                   $Date2=' ';
                 }
                 if ($fechas['carnet_conducir']!='0000-00-00' && $fechas['carnet_conducir']!=null) {
                   $Date3 = date("d/m/Y", strtotime($fechas['carnet_conducir'] ));
                 }else {
                   $Date3=' ';
                 }
                 if ($fechas['dni']!='0000-00-00' && $fechas['dni']!=null) {
                   $Date4 = date("d/m/Y", strtotime($fechas['dni'] ));
                 }else {
                   $Date4=' ';
                 }
                 if ($fechas['conducir_ford']!='0000-00-00' && $fechas['conducir_ford']!=null) {
                   $Date5 = date("d/m/Y", strtotime($fechas['conducir_ford'] ));
                 }else {
                   $Date5=' ';
                 }
                 if ($fechas['nacimiento']!='0000-00-00' && $fechas['nacimiento']!=null) {
                   $Date6 = date("d/m/Y", strtotime($fechas['nacimiento'] ));
                 }else {
                   $Date6=' ';
                 }
                echo "<tr id='trmod'>";
                 //Cargar los valores de la base de datos sobre la tabla

                echo "<td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$fechas['id']."' title='Editar información del empleado'>".$fechas['nombre']." ".$fechas['apellidos']."</a></td>";
                echo "<td data-label='telefono' id='tdmod'>".$fechas['telefono']."</td>";
                //buscar los valores de alta dónde está 1 o 0 cambiar por clear o done
                if ($fechas['alta']==0){
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>done</i></td>";
                }else{
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>clear</i> </td>";
                }
                //echo "<td data-label='Apellidos' id='tdmod'>".$nombre['alta']."</td>";
                echo "<td data-label='cadPassFord' id='tdmod'>".$Date1."</td>";
                echo "<td data-label='revMedico' id='tdmod'>".$Date2."</td>";
                echo "<td data-label='cadPerm' id='tdmod'>".$Date3."</td>";
                echo "<td data-label='cadDni' id='tdmod'>".$Date4."</td>";
                echo "<td data-label='cadPermFord' id='tdmod'>".$Date5."</td>";
                echo "<td data-label='nacim' id='tdmod'>".$Date6."</td>";

                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación


           }
               echo "</tbody></table></div>";
    }

     //funcion para sacar por pantalla los datos a buscar
    function buscar($b){
      //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './ddbb/empleados.php';
      require_once './ddbb/fechas.php';
      require_once './ddbb/incapacidad.php';
      $empleado=new Empleados();
      $incapacidad = new Incapacidad();
       $fecha= new Fechas();
      //sacamos la lista de empleados que coinciden con el filtro
        $filtrados= $fecha->fechaFiltrados($b);
        $filtro=base64_encode($b);
        echo "
        <h3><a href='excelEmpleados.php?b=".$filtro."' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
        </h3>
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Telefono</th>
            <th scope='col' id='thmod'>Alta</th>
            <th scope='col' id='thmod'>Pass Ford</th>
            <th scope='col' id='thmod'>Revision Medica</th>
            <th scope='col' id='thmod'>Permiso de Conducir</th>
            <th scope='col' id='thmod'>Caducidad DNI</th>
            <th scope='col' id='thmod'>Permio Conducir Ford</th>
            <th scope='col' id='thmod'>Nacimiento</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>";
         //llamamos a la consulta de listar todas las fechas
            $listaFecha=$fecha->sacar2();
        //sacamos los empleados por pantalla en una tabla
           foreach ($filtrados as $fechas){
                //Fonction para convertir fecha format dd/mm/aa
                if ($fechas['pase_ford']!='0000-00-00' && $fechas['pase_ford']!=null) {
                  $Date1 = date("d/m/Y", strtotime($fechas['pase_ford'] ));
                }else {
                  $Date1=' ';
                }
                if ($fechas['medico']!='0000-00-00' && $fechas['medico']!=null) {
                  $Date2 = date("d/m/Y", strtotime($fechas['medico'] ));
                }else {
                  $Date2=' ';
                }
                if ($fechas['carnet_conducir']!='0000-00-00' && $fechas['carnet_conducir']!=null) {
                  $Date3 = date("d/m/Y", strtotime($fechas['carnet_conducir'] ));
                }else {
                  $Date3=' ';
                }
                if ($fechas['dni']!='0000-00-00' && $fechas['dni']!=null) {
                  $Date4 = date("d/m/Y", strtotime($fechas['dni'] ));
                }else {
                  $Date4=' ';
                }
                if ($fechas['conducir_ford']!='0000-00-00' && $fechas['conducir_ford']!=null) {
                  $Date5 = date("d/m/Y", strtotime($fechas['conducir_ford'] ));
                }else {
                  $Date5=' ';
                }
                if ($fechas['nacimiento']!='0000-00-00' && $fechas['nacimiento']!=null) {
                  $Date6 = date("d/m/Y", strtotime($fechas['nacimiento'] ));
                }else {
                  $Date6=' ';
                }

                echo "<tr id='trmod'>";
                 //Cargar los valores de la base de datos sobre la tabla

                echo "<td data-label='Nombre' id='tdmod'><a href='editarEmpleado.php?e=".$fechas['id']."' title='Editar información del empleado'>".$fechas['nombre']." ".$fechas['apellidos']."</a></td>";
                echo "<td data-label='telefono' id='tdmod'>".$fechas['telefono']."</td>";
                //buscar los valores de alta dónde está 1 o 0 cambiar por clear o done
                if ($fechas['alta']==0){
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>done</i></td>";
                }else{
                 echo "<td data-label='Alta' id='tdmod'><i class='material-icons'>clear</i> </td>";
                }
                //echo "<td data-label='Apellidos' id='tdmod'>".$nombre['alta']."</td>";
                echo "<td data-label='cadPassFord' id='tdmod'>".$Date1."</td>";
                echo "<td data-label='revMedico' id='tdmod'>".$Date2."</td>";
                echo "<td data-label='cadPerm' id='tdmod'>".$Date3."</td>";
                echo "<td data-label='cadDni' id='tdmod'>".$Date4."</td>";
                echo "<td data-label='cadPermFord' id='tdmod'>".$Date5."</td>";
                echo "<td data-label='nacim' id='tdmod'>".$Date6."</td>";

                // Si la fecha esta null no se nuestra nada en caso contrario es la fecha de la ultima modificación


           }
            echo "</tbody></table></div>";
    }

  ?>

  </body>
</html>
