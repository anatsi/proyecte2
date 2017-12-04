<?php
require_once 'empleados.php';
$empleado=new Empleados();

$buscar = $_POST['b'];

if(!empty($buscar)) {
      buscar($buscar);
}else{


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
                echo "<a href='' title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
              }else {
                echo "<td data-label='Activo' id='tdmod'></td>";
                echo "<td data-label='Opciones' id='tdmod'>";
                echo"<a href='' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
              }
              echo "<a href='' title='Editar información'><i class='material-icons'>mode_edit</i></a></td></tr>";
      ;} echo "</tbody></table></div>";
}

function buscar($b) {
  require_once 'empleados.php';
  $empleado=new Empleados();
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
                echo "<a href='' title='Desactivar empleado'><i class='material-icons'>remove_circle</i></a>";
              }else {
                echo "<td data-label='Activo' id='tdmod'></td>";
                echo "<td data-label='Opciones' id='tdmod'>";
                echo"<a href='' title='Activar empleado'><i class='material-icons'>check_circle</i></a>";
              }
              echo "<a href='' title='Editar información'><i class='material-icons'>mode_edit</i></a></td></tr>";
      ;} echo "</tbody></table></div>";
}
 ?>
