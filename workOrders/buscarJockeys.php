<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    tr:nth-child(even) {
      background-color: #CAC6C5;
    }
    </style>

  </head>
  <body>

    <?php
    require_once 'movimientos.php';
    $movimientos= new Movimientos();

    $b = str_replace(" ", "%", $_POST['b']);

    if(!empty($b)) {
          buscar($b);
    }else{

      $lista= $movimientos->listaMovimientos();
      $recuentos= $movimientos -> cuentalistaMovimientos();
        echo "
          <h3><a href='excelWorkOrders.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a></h3>
          <br>TOTAL MOVIMIENTOS: " .$recuentos[0]['recuento']."
          <table id='tablamod'>
          <thead id='theadmod'>
            <tr id='trmod'>
            <th scope='col' id='thmod' class='bastidor'>VIN</th>
            <th scope='col' id='thmod'>ORIGEN</th>
            <th scope='col' id='thmod'>F.ORIGEN</th>
            <th scope='col' id='thmod'>H.ORIGEN</th>
            <th scope='col' id='thmod'>DESTINO</th>
            <th scope='col' id='thmod'>F.DESTINO</th>
            <th scope='col' id='thmod'>H.DESTINO</th>
            <th scope='col' id='thmod'>PROD.</th>
            <th scope='col' id='thmod'>NO PROD.</th>
            <th scope='col' id='thmod'>CICLO</th>
            <th scope='col' id='thmod'>USER</th>
            <th scope='col' id ='thmod'>ROL</th>
            <th scope='col' id='thmod'>ER</th>
            </tr>
          </thead><tbody id='tbodymod'>

          "; foreach ($lista as $movimiento) {
            $diferencia = $movimientos -> RestarHoras($movimiento['hora_origen'], $movimiento['hora_destino']);
            $siguienteMovimiento = $movimientos -> UltimoMovimiento($movimiento['usuario'], $movimiento['id']);
            if ($siguienteMovimiento != null && $siguienteMovimiento != false && $movimiento['error'] == 0) {
              $noproductivo = $movimientos -> RestarHoras($movimiento['hora_destino'], $siguienteMovimiento['hora_origen']);
              $ciclo = $movimientos -> SumarHoras($diferencia, $noproductivo);
            }else {
              $noproductivo = "";
              $ciclo = "";
            }
            //transformar fechas
            $inicio=explode("-", $movimiento['fecha_origen']);
            $inicio=$inicio[2]."-".$inicio[1]."-".$inicio[0];
            $fin=explode("-", $movimiento['fecha_destino']);
            $fin=$fin[2]."-".$fin[1]."-".$fin[0];
            //sacar si hay error o no
            if ($movimiento['error']==1) {
              $error = 'SI';
              $noproductivo = '00:00:00';
              $ciclo = '00:00:00';
            }else {
              $error = 'NO';
            }
             echo "
                <tr id='trmod'>
                <td data-label='VIN' id='tdmod' class='bastidor'>".$movimiento['bastidor']."</td>
                <td data-label='ORIGEN' id='tdmod'>".$movimiento['origen']."</td>
                <td data-label='FECHA ORIGEN' id='tdmod'>".$inicio."</td>
                <td data-label='HORA ORIGEN' id='tdmod'>".$movimiento['hora_origen']."</td>
                <td data-label='DESTINO' id='tdmod'>".$movimiento['destino']."</td>
                <td data-label='FECHA DESTINO' id='tdmod'>".$fin."</td>
                <td data-label='HORA DESTIO' id='tdmod'>".$movimiento['hora_destino']."</td>
                <td data-label='PRODUCTIVO' id='tdmod'>".$diferencia."</td>
                <td data-label='NO PORDUCTIVO' id='tdmod'>".$noproductivo."</td>
                <td data-label='CICLO' id='tdmod'>".$ciclo."</td>

                <td data-label='USUARIO' id='tdmod'>".$movimiento['usuario']."</td>
                <td data-label='ROL' id='tdmod'>".$movimiento['rol']."</td>
                <td data-label='ERROR' id='tdmod'>".$error."</td>
                </tr>

          ";} echo "</tbody></table></div>";
    }

    function buscar($b) {
      require_once 'movimientos.php';
      $movimientos= new Movimientos();
        $filtrados= $movimientos->listaMovimientosFiltrados($b);
        $filtro=base64_encode($b);
        $recuentos= $movimientos -> cuentalistaMovimientosFiltrados($b);
        ?>
        <?php
            echo "
                <h3><a href='excelWorkOrders.php?b=".$filtro."' title='Exportar filtrados a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a></h3>
                <br>TOTAL MOVIMIENTOS: " .$recuentos[0]['recuento']."
              <table id='tablamod'>
              <thead id='theadmod'>
                <tr id='trmod'>
                <th scope='col' id='thmod' class='bastidor'>VIN</th>
                <th scope='col' id='thmod'>ORIGEN</th>
                <th scope='col' id='thmod'>F.ORIGEN</th>
                <th scope='col' id='thmod'>H.ORIGEN</th>
                <th scope='col' id='thmod'>DESTINO</th>
                <th scope='col' id='thmod'>F.DESTINO</th>
                <th scope='col' id='thmod'>H.DESTINO</th>
                <th scope='col' id='thmod'>PROD.</th>
                <th scope='col' id='thmod'>NO PROD.</th>
                <th scope='col' id='thmod'>CICLO</th>
                <th scope='col' id='thmod'>USER</th>
                <th scope='col' id ='thmod'>ROL</th>
                <th scope='col' id='thmod'>ER</th>
                </tr>
              </thead><tbody id='tbodymod'>

              "; foreach ($filtrados as $movimiento) {
                  $diferencia = $movimientos -> RestarHoras($movimiento['hora_origen'], $movimiento['hora_destino']);
                  $siguienteMovimiento = $movimientos -> UltimoMovimiento($movimiento['usuario'], $movimiento['id']);
                  if ($siguienteMovimiento != null && $siguienteMovimiento != false && $movimiento['error'] == 0) {
                    $noproductivo = $movimientos -> RestarHoras($movimiento['hora_destino'], $siguienteMovimiento['hora_origen']);
                    $ciclo = $movimientos -> SumarHoras($diferencia, $noproductivo);
                    if ($noproductivo > '05:00:00') {
                      $noproductivo = '00:00:00';
                      $ciclo = '00:00:00';
                    }
                  }else {
                    $noproductivo = "";
                    $ciclo = "";
                  }
                  //transformar fechas
                $inicio=explode("-", $movimiento['fecha_origen']);
                $inicio=$inicio[2]."-".$inicio[1]."-".$inicio[0];
                $fin=explode("-", $movimiento['fecha_destino']);
                $fin=$fin[2]."-".$fin[1]."-".$fin[0];
                //sacar si hay error o no
                if ($movimiento['error']==1) {
                  $error = 'SI';
                }else {
                  $error = 'NO';
                }
                 echo "
                    <tr id='trmod'>
                    <td data-label='VIN' id='tdmod' class='bastidor'>".$movimiento['bastidor']."</td>
                    <td data-label='ORIGEN' id='tdmod'>".$movimiento['origen']."</td>
                    <td data-label='FECHA ORIGEN' id='tdmod'>".$inicio."</td>
                    <td data-label='HORA ORIGEN' id='tdmod'>".$movimiento['hora_origen']."</td>
                    <td data-label='DESTINO' id='tdmod'>".$movimiento['destino']."</td>
                    <td data-label='FECHA DESTINO' id='tdmod'>".$fin."</td>
                    <td data-label='HORA DESTIO' id='tdmod'>".$movimiento['hora_destino']."</td>
                    <td data-label='PRODUCTIVO' id='tdmod'>".$diferencia."</td>
                    <td data-label='NO PORDUCTIVO' id='tdmod'>".$noproductivo."</td>
                    <td data-label='CICLO' id='tdmod'>".$ciclo."</td>

                    <td data-label='USUARIO' id='tdmod'>".$movimiento['usuario']."</td>
                    <td data-label='ROL' id='tdmod'>".$movimiento['rol']."</td>
                    <td data-label='ERROR' id='tdmod'>".$error."</td>
                    </tr>

              ";} echo "</tbody></table></div>";

    }
     ?>

     <!--ORDENAR TABLA
     <script type="text/javascript" src="../js/jquery.min.js"></script>-->
     <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
     <script>
       $(function(){
         $("#tablamod").tablesorter();
       });
     </script>
  </body>
</html>
