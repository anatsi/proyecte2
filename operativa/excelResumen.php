<?php
/* Incluir la libreria PHPExcel */
require_once './Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once 'servicio.php';
require_once 'cliente.php';
require_once 'recursos.php';
$cliente= new Cliente();
$servicio= new Servicio();
$recursos= new Recursos();

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

//array para cambiar el color
$style = array('font' => array('color' => array('rgb' => 'E72512')));
$otherStyle = array('font' => array('color' => array('rgb' => '49678D')));
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));
$OtherStyle_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'d9d9d9'),));
// Agregar Informacion
$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'ACTIVIDAD')
->setCellValue('B1', 'DESCRIPCION')
->setCellValue('C1', 'MODELOS')
->setCellValue('D1', 'INICIO')
->setCellValue('E1', 'FIN')
->setCellValue('F1', 'TOTAL RECURSOS')
->setCellValue('G1', 'TM')
->setCellValue('H1', 'TT')
->setCellValue('I1', 'TC')
->setCellValue('J1', 'TN')
->setCellValue('K1', 'TE')
->setCellValue('L1', 'CLIENTE')
->setCellValue('M1', 'RESPONSABLE')
->setCellValue('N1', 'TEL. RESPONSABLE')
->setCellValue('O1', 'C0RREO RESPONSABLE')
->setCellValue('P1', 'COMENTARIO SUP.')
->setCellValue('Q1', 'COMENTARIO RRHH')
->setCellValue('R1', 'COMENTARIO ADMIN. FIN.')
->setCellValue('S1', 'COMENTARIO FINAL')
->setCellValue('T1', 'RELACION')
->setCellValue('U1', 'CANCELADO');

  $lista=$servicio->listaResumen($_GET['fin'], $_GET['inicio']);


  $i=2;
  foreach ($lista as $serv) {
    //arreglar las fechas
    $fechai=explode("-", $serv['f_inicio']);
    $inicio=$fechai[2]."/".$fechai[1]."/".$fechai[0];
    if ($serv['f_fin']!=NULL && $serv['f_fin']!='0000-00-00') {
      $fechaf=explode("-", $serv['f_fin']);
      $fin=$fechaf[2]."/".$fechaf[1]."/".$fechaf[0];
    }else {
      $fin='';
    }
    //sacar nombre clientes
    $clientes=$cliente->ClienteId($serv['id_cliente']);
    //sacar recursos de la actividad
    $recurso=$recursos->RecursosId($serv['id']);
    $otros=0;
    if ($recurso['otro1']!=0) {
      $otros=$recurso['otro1']." (".$recurso['inicio1']."-".$recurso['fin1'].") //";
    }
    if ($recurso['otro2']!=0) {
      $otros=$otros. $recurso['otro2']." (".$recurso['inicio2']."-".$recurso['fin2'].") //";
    }
    if ($recurso['otro3']!=0) {
      $otros=$otros. $recurso['otro3']." (".$recurso['inicio3']."-".$recurso['fin3'].") //";
    }
    if ($recurso['otro4']!=0) {
      $otros=$otros. $recurso['otro4']." (".$recurso['inicio4']."-".$recurso['fin4'].") //";
    }
    if ($recurso['otro5']!=0) {
      $otros=$otros. $recurso['otro5']." (".$recurso['inicio5']."-".$recurso['fin5'].") //";
    }
    if ($recurso['otro6']!=0) {
      $otros=$otros. $recurso['otro6']." (".$recurso['inicio6']."-".$recurso['fin6'].")";
    }

    //sacar el nombre de la actividad que esta relacionada.
    if ($serv['relacion']!=null && $serv['relacion']!=0) {
      $relacionar=$servicio->ServicioRelacion($serv['id']);
      $relacionado=$relacionar['relacionada'];
    }else {
      $relacionado='';
    }

    //sacar un si si la actividad ha sido cancelada y un no si no lo ha sido
    if ($serv['cancelado']==true) {
      $cancelado='SI';
    }else {
      $cancelado='NO';
    }

    //sacar datos en el excel
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->applyFromArray($OtherStyle_header);
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i, $serv['descripcion'])
    ->setCellValue('B'.$i, $serv['com_depto'])
    ->setCellValue('C'.$i, $serv['modelos'])
    ->setCellValue('D'.$i, $inicio)
    ->setCellValue('E'.$i, $fin)
    ->setCellValue('F'.$i, $serv['recursos'])
    ->setCellValue('G'.$i, $recurso['tm'])
    ->setCellValue('H'.$i, $recurso['tt'])
    ->setCellValue('I'.$i, $recurso['tn'])
    ->setCellValue('J'.$i, $recurso['tc'])
    ->setCellValue('K'.$i, $otros)
    ->setCellValue('L'.$i, $clientes['nombre'])
    ->setCellValue('M'.$i, $serv['responsable'])
    ->setCellValue('N'.$i, $serv['telefono'])
    ->setCellValue('O'.$i, $serv['correo'])
    ->setCellValue('P'.$i, $serv['com_supervisor'])
    ->setCellValue('Q'.$i, $serv['com_rrhh'])
    ->setCellValue('R'.$i, $serv['com_admin_fin'])
    ->setCellValue('S'.$i, $serv['com_fin'])
    ->setCellValue('T'.$i, $relacionado)
    ->setCellValue('U'.$i, $cancelado);
    $i++;
    //sacar modificaciones de esa actividad
    $modInfo=$servicio->infoResumen($serv['id'], $_GET['inicio'], $_GET['fin']);
    $numeroInfo=count($modInfo);
    $modRecursos=$servicio->diasRecursosResumen($serv['id'], $_GET['inicio'], $_GET['fin']);
    $numeroRecursos=count($modRecursos);
    //modificaciones de la informacion
    if ($numeroInfo>0) {
      //cambiar color de la letra
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
      //fecha inicio periodo normal
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D'.$i, $inicio)
      ;
      foreach ($modInfo as $info) {
        //comrpobacion de las fechas y cambio de formato de las fechas
        if ($info['inicio']!='0000-00-00') {
          //fecha fin del periodo (un dia antes del inicio de la modificacion).
          //restar un dia a la fecha
          $nuevoHasta= strtotime('-1 day', strtotime($info['inicio']));
          $nuevoHasta = date('d/m/Y', $nuevoHasta);
          //cambiar color de la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
          //escribir la informacion en el excel
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('E'.$i, $nuevoHasta)
          ->setCellValue('A'.$i, $serv['descripcion'])
          ->setCellValue('C'.$i, $serv['modelos'])
          ->setCellValue('F'.$i, $serv['recursos'])
          ->setCellValue('M'.$i, $serv['responsable'])
          ->setCellValue('N'.$i, $serv['telefono'])
          ->setCellValue('O'.$i, $serv['correo'])
          ;

          $i++;
          //acaba periodo normal y empieza modificacion
          //convertir las fechas
          $inicioInfo = date('d/m/Y', strtotime($info['inicio']));
          if ($info['fin']!='0000-00-00') {
            $finInfo = date('d/m/Y', strtotime($info['fin']));
            $nuevoDesde= strtotime('+1 day', strtotime($info['fin']));
            $nuevoDesde = date('d/m/Y', $nuevoDesde);
          }else {
            $finInfo='';
          }
          //cambiar color de la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
          //escribir los datos en el excel
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, $info['descripcion'])
          ->setCellValue('C'.$i, $info['modelos'])
          ->setCellValue('D'.$i, $inicioInfo)
          ->setCellValue('E'.$i, $finInfo)
          ->setCellValue('F'.$i, $serv['recursos'])
          ->setCellValue('M'.$i, $info['responsable'])
          ->setCellValue('N'.$i, $info['telefono'])
          ->setCellValue('O'.$i, $info['correo']);
          $i++;

          //cambiar color de la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
          //Ponemos el desde del nuevo periodo de normalidad
          $objPHPExcel -> setActiveSheetIndex(0)
          ->setCellValue('D'.$i, $nuevoDesde)
          ;

        }else {

          //hasta del periodo normal
          //fecha fin del periodo (un dia antes del inicio de la modificacion).
          //restar un dia a la fecha
          $nuevoHasta= strtotime('-1 day', strtotime($info['suelto']));
          $nuevoHasta = date('d/m/Y', $nuevoHasta);
          //cambiar color de la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
          //escribir la informacion en el excel
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('E'.$i, $nuevoHasta)
          ->setCellValue('A'.$i, $serv['descripcion'])
          ->setCellValue('C'.$i, $serv['modelos'])
          ->setCellValue('F'.$i, $serv['recursos'])
          ->setCellValue('M'.$i, $serv['responsable'])
          ->setCellValue('N'.$i, $serv['telefono'])
          ->setCellValue('O'.$i, $serv['correo'])
          ;
          $i++;
          //periodo de modificaciones
          //convertir la fecha
          $sueltoInfo = date('d/m/Y', strtotime($info['suelto']));
          //cambiar color de la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
          //escribir los datos en el excel
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, $info['descripcion'])
          ->setCellValue('C'.$i, $info['modelos'])
          ->setCellValue('D'.$i, $sueltoInfo)
          ->setCellValue('E'.$i, $sueltoInfo)
          ->setCellValue('F'.$i, $serv['recursos'])
          ->setCellValue('M'.$i, $info['responsable'])
          ->setCellValue('N'.$i, $info['telefono'])
          ->setCellValue('O'.$i, $info['correo']);
          $i++;

          //desde del siguiente periodo normal
          //sumar un dia al dia de la modificacion.
          $nuevoDesde= strtotime('+1 day', strtotime($info['suelto']));
          $nuevoDesde = date('d/m/Y', $nuevoDesde);
          //cambiar color de la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
          //escribir el desde en el excel
          $objPHPExcel -> setActiveSheetIndex(0)
          ->setCellValue('D'.$i, $nuevoDesde)
          ;

        }
      }
      //ultima informacion y hasta del periodo normal
      //cambiar color de la letra
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($style);
      //escribir la informacion en el excel
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('E'.$i, $fin)
      ->setCellValue('A'.$i, $serv['descripcion'])
      ->setCellValue('C'.$i, $serv['modelos'])
      ->setCellValue('F'.$i, $serv['recursos'])
      ->setCellValue('M'.$i, $serv['responsable'])
      ->setCellValue('N'.$i, $serv['telefono'])
      ->setCellValue('O'.$i, $serv['correo'])
      ;
      $i++;
    }


    //modificaciones de los recursos
    if ($numeroRecursos>0) {
      //periodo normal. primer desde.
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
      //fecha inicio periodo normal
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D'.$i, $inicio)
      ;
      foreach ($modRecursos as $mod) {
        //arreglamos los turnos especiales.
        $otrosMod=0;
        if ($mod['otro1']!=0) {
          $otrosMod=$mod['otro1']." (".$mod['inicio1']."-".$mod['fin1'].") //";
        }
        if ($mod['otro2']!=0) {
          $otrosMod=$otros. $mod['otro2']." (".$mod['inicio2']."-".$mod['fin2'].") //";
        }
        if ($mod['otro3']!=0) {
          $otrosMod=$otros. $mod['otro3']." (".$mod['inicio3']."-".$mod['fin3'].") //";
        }
        if ($mod['otro4']!=0) {
          $otrosMod=$otros. $mod['otro4']." (".$mod['inicio4']."-".$mod['fin4'].") //";
        }
        if ($mod['otro5']!=0) {
          $otrosMod=$otros. $mod['otro5']." (".$mod['inicio5']."-".$mod['fin5'].") //";
        }
        if ($mod['otro6']!=0) {
          $otrosMod=$otros. $mod['otro6']." (".$mod['inicio6']."-".$mod['fin6'].")";
        }


        //comrpobacion de las fechas y cambio de formato de las fechas
        if ($mod['inicio']!='0000-00-00') {
          //informacion  y final del primer periodo normal.
          //restar un dia a la fecha
          $nuevoHasta= strtotime('-1 day', strtotime($mod['inicio']));
          $nuevoHasta = date('d/m/Y', $nuevoHasta);
          //cambiar el color a la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
          //escribir la informacion en el excel.
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('E'.$i, $nuevoHasta)
          ->setCellValue('A'.$i, $serv['descripcion'])
          ->setCellValue('F'.$i, $serv['recursos'])
          ->setCellValue('G'.$i, $recurso['tm'])
          ->setCellValue('H'.$i, $recurso['tt'])
          ->setCellValue('I'.$i, $recurso['tn'])
          ->setCellValue('J'.$i, $recurso['tc'])
          ->setCellValue('K'.$i, $otros);
          $i++;

          //acaba periodo normal y empieza la modificacion
          //convertir las fechas de inicio y fin de la modificacion.
          $inicioRec = date('d/m/Y', strtotime($mod['inicio']));
          if ($mod['fin']!='0000-00-00') {
            $finRec = date('d/m/Y', strtotime($mod['fin']));
            $nuevoDesde= strtotime('+1 day', strtotime($mod['fin']));
            $nuevoDesde = date('d/m/Y', $nuevoDesde);
          }else {
            $finRec='';
          }
          //escribir en el excel las modificaciones
          //cambiar el color a la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('D'.$i, $inicioRec)
          ->setCellValue('E'.$i, $finRec)
          ->setCellValue('A'.$i, $serv['descripcion'])
          ->setCellValue('F'.$i, $mod['total'])
          ->setCellValue('G'.$i, $mod['tm'])
          ->setCellValue('H'.$i, $mod['tt'])
          ->setCellValue('I'.$i, $mod['tn'])
          ->setCellValue('J'.$i, $mod['tc'])
          ->setCellValue('K'.$i, $otros);
          $i++;

          //empezamos el nuevo periodo normal.
          //ponemos el desde.
          //cambiar el color a la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
          $objPHPExcel -> setActiveSheetIndex(0)
          ->setCellValue('D'.$i, $nuevoDesde)
          ;
        }else {
          //hasta del periodo normal
          //fecha fin del periodo (un dia antes del inicio de la modificacion).
          //restar un dia a la fecha
          $nuevoHasta= strtotime('-1 day', strtotime($mod['suelto']));
          $nuevoHasta = date('d/m/Y', $nuevoHasta);
          //escribir la informacion del periodo normal.
          //cambiar el color a la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('E'.$i, $nuevoHasta)
          ->setCellValue('A'.$i, $serv['descripcion'])
          ->setCellValue('F'.$i, $serv['recursos'])
          ->setCellValue('G'.$i, $recurso['tm'])
          ->setCellValue('H'.$i, $recurso['tt'])
          ->setCellValue('I'.$i, $recurso['tn'])
          ->setCellValue('J'.$i, $recurso['tc'])
          ->setCellValue('K'.$i, $otros);
          $i++;

          //periodo de modificaciones
          //convertir la fecha
          $sueltoRec = date('d/m/Y', strtotime($mod['suelto']));
          //escribir en el excel las modificaciones
          //cambiar el color a la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('D'.$i, $sueltoRec)
          ->setCellValue('E'.$i, $sueltoRec)
          ->setCellValue('A'.$i, $serv['descripcion'])
          ->setCellValue('F'.$i, $mod['total'])
          ->setCellValue('G'.$i, $mod['tm'])
          ->setCellValue('H'.$i, $mod['tt'])
          ->setCellValue('I'.$i, $mod['tn'])
          ->setCellValue('J'.$i, $mod['tc'])
          ->setCellValue('K'.$i, $otros);
          $i++;

          //empieza periodo normal.
          //desde del periodo normal
          //sumar un dia al dia de la modificacion.
          $nuevoDesde= strtotime('+1 day', strtotime($mod['suelto']));
          $nuevoDesde = date('d/m/Y', $nuevoDesde);
          //escribir el desde en el excel
          //cambiar el color a la letra
          $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
          $objPHPExcel -> setActiveSheetIndex(0)
          ->setCellValue('D'.$i, $nuevoDesde)
          ;
        }
      }
      //escribir la informacion del periodo normal.
      //cambiar el color a la letra
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i.'')->applyFromArray($otherStyle);
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('E'.$i, $fin)
      ->setCellValue('A'.$i, $serv['descripcion'])
      ->setCellValue('F'.$i, $serv['recursos'])
      ->setCellValue('G'.$i, $recurso['tm'])
      ->setCellValue('H'.$i, $recurso['tt'])
      ->setCellValue('I'.$i, $recurso['tn'])
      ->setCellValue('J'.$i, $recurso['tc'])
      ->setCellValue('K'.$i, $otros);
      $i++;
    }
  }

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
//adaptar las celdas al ancho del texto
for($col = 'A'; $col !== 'Z'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

//poner el encabezado en negrita
$objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:U100")->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:U".$i2)->applyFromArray($bordes);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
