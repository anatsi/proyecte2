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
    <!--Script para fijar la cabecera de las tablas -->
    <script type="text/javascript" src="../js/jquery.stickytableheaders.min.js"></script>
    <script type="text/javascript">
      $(function() {
        $("table").stickyTableHeaders();
      });
    </script>

  </head>
  <body>

    <?php
    require_once './bbdd/disengagement.php';
    $disengagements= new Disengagement();

    $b = str_replace(" ", "%", $_POST['b']);

    if(!empty($b)) {
          buscar($b);
    }else{

      $lista= $disengagements->listaDisengagement();
      $recuentos= $disengagements -> cuentalistaDisengagement();
        echo "
          <h3>
          <a href='excelDisengagement.php' title='Exportar todo a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
          <img onclick='location.reload(true);' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGuSURBVEhL7dfNKwVRHMbxq7xHUhaWKCsWpKxF2SkrG4WFkGRth4WFyEIUKYoi/gFRZGFlJdlQyMJKyUbJ+/eZ26lpmmvOuXfMylOfmrndOb+5p/M7MzcVc/rRkj5MNie4QKF3llCq8YlvzOiDv0geVKgOtSjGKFRU3hHblFdgDMd4gSlivAbOL1GEnDKEJ/gHtpH1lJdiD2GD2tCUt8IpWpmHCBvQhfOUr8Fc/IEjTKEPPRjBAs7hLxTGesq7oAueMQ+t3N9yh2AxQzfchsjk4xq7UMtEpR7BYpohXe/UUt3oTR9aZRymoNpsGepv57hudwd4xDSq9EFSGYDa7j+xRlOq/brdO0sgWjST0CLS6lUL2aYRepo5RRvHIvxPI20WthnESvrQLk3YgTZ5U9BYRVRqsI83WM2OHujqy2AxvwlkSjO2YG5Ye7t1lhAsFnSFTWgfn8M2buD/jvboAlinDLfwD+LqDHpjcU4HvhA2aBStgRJkHa3GsIEzOUUnck457uEffBbr0OuQHntqtWG49LVV9AtMUb1pJJoNqLB2rkRTiQc0eGcJJ+Y/YqnUD0m40+Em89vZAAAAAElFTkSuQmCC'>
          </h3>
          <br>TOTAL MOVIMIENTOS: " .$recuentos[0]['recuento']."
          <table id='tablamod'>
          <thead id='theadmod'>
          <tr id='trmod'>
            <th scope='col' id='thmod' class='bastidor'>VIN</th>
            <th scope='col' id='thmod'>CONSTRUCCION</th>
            <th scope='col' id='thmod'>FECHA</th>
            <th scope='col' id='thmod'>HORA</th>
            <th scope='col' id='thmod'>TAMAÑO</th>
            <th scope='col' id='thmod'>MODELO</th>
            <th scope='col' id='thmod'>RUIDO</th>
            <th scope='col' id='thmod'>DERECHA</th>
            <th scope='col' id='thmod'>REPARADO</th>
            <th scope='col' id='thmod'>IZQUIERDA</th>
            <th scope='col' id='thmod'>REPARADO</th>
            <th scope='col' id ='thmod'>USER</th>
          </tr>
          </thead><tbody id='tbodymod'>";

          foreach ($lista as $disengagement) {
            //transformar fechas
            $construccion=explode("-", $disengagement['construccion']);
            $construccion=$construccion[2]."-".$construccion[1]."-".$construccion[0];
            $fecha=explode("-", $disengagement['fecha']);
            $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
             echo "
             <tr id='trmod'>
               <td data-label='VIN' id='tdmod' class='bastidor'>".$disengagement['bastidor']."</td>
               <td data-label='CONSTRUCCION' id='tdmod'>".$construccion."</td>
               <td data-label='FECHA' id='tdmod'>".$fecha."</td>
               <td data-label='HORA' id='tdmod'>".$disengagement['hora']."</td>
               <td data-label='TAMAÑO' id='tdmod'>".$disengagement['tamano']."</td>
               <td data-label='TIPO' id='tdmod'>".$disengagement['tipo']."</td>
               <td data-label='RUIDO' id='tdmod'>".$disengagement['ruido']."</td>
               <td data-label='DERECHA' id='tdmod'>".$disengagement['derecha']."</td>
               <td data-label='REPARADO' id='tdmod'>".$disengagement['derechaR']."</td>
               <td data-label='IZQUIERDA' id='tdmod'>".$disengagement['izquierda']."</td>
               <td data-label='REPARADO' id='tdmod'>".$disengagement['izquierdaR']."</td>
               <td data-label='USUARIO' id='tdmod'>".$disengagement['usuario']."</td>
             </tr>";}
          echo "</tbody></table></div>";
    }

    function buscar($b) {
      require_once './bbdd/disengagement.php';
      $disengagements= new Disengagement();
        $filtrados= $disengagements->listaDisengagementFiltro($b);
        $filtro=base64_encode($b);
        $recuentos= $disengagements -> cuentalistaDisengagementFiltro($b);
        ?>
        <?php
            echo "
                <h3>
                <a href='excelDisengagement.php?b=".$filtro."' title='Exportar filtrados a excel'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAALbSURBVEhL1VZLaBRBEB1FUEHUgwpCNLiZn6viUQ+iHsSLJyEeNOt0T5SoAcGbB8EFvxdBBQ+KARFEkt3t2URCPC74ARX1oIecFAQ/Nz+gxB/GVz09s7uzEza7afwUPGaquqped1Vt7xitij2SW2UJb6ct2Ekn4KPA60yhb5FanrlsqeTnuKJ3rR34OTvg52zBK47gH0A0mUTbxB1D3fOtYX8DEh+wA3bZEewBEk4kCaZCW8Qgeo6y/UhLOF0QMfIcR1WuNAP8L1G7jGSSdiCJBXuWtpYGbODUXyFGS89qI3YCNoiTPCYg8UvoHyM9tsMmY3QSq5GR4pT4HthHlRoL2WTMv0Ms+ENj0phFTtmRfWbdtAs+IKMh9LuujQtLze+hnO8VPmPKv9foIWBTuRpP7AacqfwGiG+GjuxrtrB3JdlWF701cQIFLcOFBG+yhf4FROKU+To4/YLtAulUDey8kowhYjfwtuN9Vwh2FZt7UtVDKFs6MQFEZyQRBM7FjMgto3fVuwb/ZI9xzR6Cva3hmpC3C8S+1beEnuZYz0L09m2Kr74Th2CDRBgJgk6n+2nqcQT0sl9xSrFK3mbqd5ovEeNZM9XsCzba2lQTEHjfyOdnE6Eb+LslMwRr15O+BC09Rh+/mcN+lhy7ynwF7d4S/nrSzSF/Ke08GaNruE4oPwMOA8oWJ1FJ62LCUlfvamz+Fd3Lsa5ANhmTQjxujh2eSwR0aug/ozUQbpLMaAESPIrshBkPF5pfxM6OEnBb3a1dA55GazjRjdo1NVy91Vhehj4e+0d22GTMVMPVKnT1uGUQsSvYRqvkbyOgIhfpuy3SI6hvOb3E2i6QVvD/EAfsIG4mzqCcR/3vYCefGp2ag4hNsb8Dfcw0Q/S/Xi/4vzUDr8sRfjeVA0lvY1DeJYmSIGKVQa+4ome5JdgOlOgY/dbxfPFHiNOks8wWO2W2Fb090nmNzVPmaYph/AbQ+I/d0UElTgAAAABJRU5ErkJggg=='></a>
                <img onclick='location.reload(true);' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGuSURBVEhL7dfNKwVRHMbxq7xHUhaWKCsWpKxF2SkrG4WFkGRth4WFyEIUKYoi/gFRZGFlJdlQyMJKyUbJ+/eZ26lpmmvOuXfMylOfmrndOb+5p/M7MzcVc/rRkj5MNie4QKF3llCq8YlvzOiDv0geVKgOtSjGKFRU3hHblFdgDMd4gSlivAbOL1GEnDKEJ/gHtpH1lJdiD2GD2tCUt8IpWpmHCBvQhfOUr8Fc/IEjTKEPPRjBAs7hLxTGesq7oAueMQ+t3N9yh2AxQzfchsjk4xq7UMtEpR7BYpohXe/UUt3oTR9aZRymoNpsGepv57hudwd4xDSq9EFSGYDa7j+xRlOq/brdO0sgWjST0CLS6lUL2aYRepo5RRvHIvxPI20WthnESvrQLk3YgTZ5U9BYRVRqsI83WM2OHujqy2AxvwlkSjO2YG5Ye7t1lhAsFnSFTWgfn8M2buD/jvboAlinDLfwD+LqDHpjcU4HvhA2aBStgRJkHa3GsIEzOUUnck457uEffBbr0OuQHntqtWG49LVV9AtMUb1pJJoNqLB2rkRTiQc0eGcJJ+Y/YqnUD0m40+Em89vZAAAAAElFTkSuQmCC'>
                </h3>
                <br>TOTAL MOVIMIENTOS: " .$recuentos[0]['recuento']."
              <table id='tablamod'>
              <thead id='theadmod'>
              <tr id='trmod'>
                <th scope='col' id='thmod' class='bastidor'>VIN</th>
                <th scope='col' id='thmod'>CONSTRUCCION</th>
                <th scope='col' id='thmod'>FECHA</th>
                <th scope='col' id='thmod'>HORA</th>
                <th scope='col' id='thmod'>TAMAÑO</th>
                <th scope='col' id='thmod'>MODELO</th>
                <th scope='col' id='thmod'>RUIDO</th>
                <th scope='col' id='thmod'>DERECHA</th>
                <th scope='col' id='thmod'>REPARADO</th>
                <th scope='col' id='thmod'>IZQUIERDA</th>
                <th scope='col' id='thmod'>REPARADO</th>
                <th scope='col' id ='thmod'>USER</th>
              </tr>
              </thead><tbody id='tbodymod'>";
              foreach ($filtrados as $disengagement) {
                //transformar fechas
                $construccion=explode("-", $disengagement['construccion']);
                $construccion=$construccion[2]."-".$construccion[1]."-".$construccion[0];
                $fecha=explode("-", $disengagement['fecha']);
                $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
                 echo "
                 <tr id='trmod'>
                   <td data-label='VIN' id='tdmod' class='bastidor'>".$disengagement['bastidor']."</td>
                   <td data-label='CONSTRUCCION' id='tdmod'>".$construccion."</td>
                   <td data-label='FECHA' id='tdmod'>".$fecha."</td>
                   <td data-label='HORA' id='tdmod'>".$disengagement['hora']."</td>
                   <td data-label='TAMAÑO' id='tdmod'>".$disengagement['tamano']."</td>
                   <td data-label='TIPO' id='tdmod'>".$disengagement['tipo']."</td>
                   <td data-label='RUIDO' id='tdmod'>".$disengagement['ruido']."</td>
                   <td data-label='DERECHA' id='tdmod'>".$disengagement['derecha']."</td>
                   <td data-label='REPARADO' id='tdmod'>".$disengagement['derechaR']."</td>
                   <td data-label='IZQUIERDA' id='tdmod'>".$disengagement['izquierda']."</td>
                   <td data-label='REPARADO' id='tdmod'>".$disengagement['izquierdaR']."</td>
                   <td data-label='USUARIO' id='tdmod'>".$disengagement['usuario']."</td>
                 </tr>";}
              echo "</tbody></table></div>";

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
