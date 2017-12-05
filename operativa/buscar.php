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
      <h3><a href='excelHistorico.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a></h3>
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
    $filtro=base64_encode($b);
        echo "
            <h3><a href='excelHistorico.php?b=".$filtro."' title='Exportar filtrados a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a></h3>
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
