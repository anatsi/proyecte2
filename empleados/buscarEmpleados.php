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
    require_once './bbdd/empleados.php';
    $empleado=new Empleados();

    //guardamos lo que se quiere buscar en una variable
    $buscar = $_POST['b'];

    if(!empty($buscar)) {
        //si se ha enviado algo para buscar, llamamos a la funcion de abajo
          buscar($buscar);
    }else{
      //si no se ha enviado nada, sacamos toda la informacion
      //llamamos a la funcion de listar todos los empleados
      $listaEmpleados= $empleado->listaEmpleados();

        echo "
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Apellidos</th>
            <th scope='col' id='thmod'>Activo</th>
            <th scope='col' id='thmod'>Opciones</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>

          "; foreach ($listaEmpleados as $empleados) {
             echo "
                <tr id='trmod'>
                  <td data-label='Nombre' id='tdmod'>".$empleados['nombre']."</td>
                  <td data-label='Apellidos' id='tdmod'>".$empleados['apellidos']."</td>";
                  if ($empleados['activo']==1) {
                    echo "<td data-label='Activo' id='tdmod'><i class='material-icons'>done</i></td>";
                    echo "<td data-label='Opciones' id='tdmod'>";
                    echo "<a href='desactivarEmpleado.php?e=".$empleados['id']."' title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
                  }else {
                    echo "<td data-label='' id='tdmod'></td>";
                    echo "<td data-label='Opciones' id='tdmod'>";
                    echo"<a href='activarEmpleado.php?e=".$empleados['id']."' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
                  }
                  echo "<a href='editarEmpleado.php?e=".$empleados['id']."' title='Editar información del empleado'><i class='material-icons'>mode_edit</i></a></td></tr>";
          ;} echo "</tbody></table></div>";
    }

    //funcion para sacar por pantalla los datos a buscar
    function buscar($b) {
      //incluimos los archivos necesarios e inicializamos sus objetos
      require_once './bbdd/empleados.php';
      $empleado=new Empleados();
      //sacamos la lista de empleados que coinciden con el filtro
        $filtrados= $empleado->listaFiltrados($b);
        $filtro=base64_encode($b);
        echo "
        <table id='tablamod'>
        <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod'>Nombre</th>
            <th scope='col' id='thmod'>Apellidos</th>
            <th scope='col' id='thmod'>Activo</th>
            <th scope='col' id='thmod'>Opciones</th>
          </tr>
        </thead>
        <tbody id='tbodymod'>

          "; foreach ($filtrados as $empleados) {
             echo "
                <tr id='trmod'>
                  <td data-label='Nombre' id='tdmod'>".$empleados['nombre']."</td>
                  <td data-label='Apellidos' id='tdmod'>".$empleados['apellidos']."</td>";
                  if ($empleados['activo']==1) {
                    echo "<td data-label='Activo' id='tdmod'><i class='material-icons'>done</i></td>";
                    echo "<td data-label='Opciones' id='tdmod'>";
                    echo "<a href='desactivarEmpleado.php?e=".$empleados['id']."' title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
                  }else {
                    echo "<td data-label='' id='tdmod'></td>";
                    echo "<td data-label='Opciones' id='tdmod'>";
                    echo"<a href='activarEmpleado.php?e=".$empleados['id']."' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
                  }
                  echo "<a href='editarEmpleado.php?e=".$empleados['id']."' title='Editar información del empleado'><i class='material-icons'>mode_edit</i></a></td></tr>";
          ;} echo "</tbody></table></div>";
    }
     ?>

  </body>
</html>
