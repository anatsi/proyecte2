<?php
require_once 'servicio.php';
require_once 'cliente.php';
$servicio= new Servicio();
$cliente= new Cliente();

$buscar = $_POST['b'];

if(!empty($buscar)) {
      buscar($buscar);
}else{


  $finalizados= $servicio->listaFinalizados();

    echo "
    <h3><a href='excelHistorico.php' id='excel'>Exportar a excel</a></h3>
      <table id='tablamod'>
      <thead id='theadmod'>
        <tr id='trmod'>
          <th scope='col' id='thmod'>ACTIVIDAD</th>
          <th scope='col' id='thmod'>MODELOS</th>
          <th scope='col' id='thmod'>CLIENTE</th>
          <th scope='col' id='thmod'>RESPONSABLE</th>
        </tr>
      </thead><tbody id='tbodymod'>

      "; foreach ($finalizados as $servicio) {
        $clientes=$cliente->ClienteId($servicio['id_cliente']);
         echo "
            <tr id='trmod'>
              <td data-label='ACTIVIDAD' id='tdmod'><a href='timeline.php?servicio=".$servicio['id']."'>".$servicio['descripcion']."</a></td>
              <td data-label='MODELOS' id='tdmod'>".$servicio['modelos']."</td>
              <td data-label='CLIENTE' id='tdmod'>".$clientes['nombre']."</td>
              <td data-label='RESPONSABLE' id='tdmod'>".$servicio['responsable']."</td>
            </tr>

      ";} echo "</tbody></table></div></body></html>";
}

function buscar($b) {
  require_once 'servicio.php';
  require_once 'cliente.php';
  $servicio= new Servicio();
  $cliente= new Cliente();
    $filtrados= $servicio->listaFiltrados($b);
        echo "
          <h3><a href='excelHistorico.php?b=".$b."' id='excel'>Exportar a excel</a></h3>
          <table id='tablamod'>
          <thead id='theadmod'>
            <tr id='trmod'>
              <th scope='col' id='thmod'>ACTIVIDAD</th>
              <th scope='col' id='thmod'>MODELOS</th>
              <th scope='col' id='thmod'>CLIENTE</th>
              <th scope='col' id='thmod'>RESPONSABLE</th>
            </tr>
          </thead><tbody id='tbodymod'>

          "; foreach ($filtrados as $servicio) {
            $clientes=$cliente->ClienteId($servicio['id_cliente']);
             echo "
                <tr id='trmod'>
                  <td data-label='ACTIVIDAD' id='tdmod'><a href='timeline.php?servicio=".$servicio['id']."'>".$servicio['descripcion']."</a></td>
                  <td data-label='MODELOS' id='tdmod'>".$servicio['modelos']."</td>
                  <td data-label='CLIENTE' id='tdmod'>".$clientes['nombre']."</td>
                  <td data-label='RESPONSABLE' id='tdmod'>".$servicio['responsable']."</td>
                </tr>

          ";} echo "</tbody></table></div></body></html>";
}
 ?>
