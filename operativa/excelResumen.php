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
// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'ACTIVIDAD')
->setCellValue('B1', 'MODELOS')
->setCellValue('C1', 'INICIO')
->setCellValue('D1', 'FIN')
->setCellValue('E1', 'TOTAL RECURSOS')
->setCellValue('F1', 'TM')
->setCellValue('G1', 'TT')
->setCellValue('H1', 'TC')
->setCellValue('I1', 'TN')
->setCellValue('J1', 'OTROS')
->setCellValue('K1', 'CLIENTE')
->setCellValue('L1', 'RESPONSABLE')
->setCellValue('M1', 'TEL. RESPONSABLE')
->setCellValue('N1', 'C0RREO RESPONSABLE')
->setCellValue('O1', 'COMENTARIO SUP.')
->setCellValue('P1', 'COMENTARIO RRHH')
->setCellValue('Q1', 'COMENTARIO ADMIN. FIN.')
->setCellValue('R1', 'COMENTARIO DEPTO. OPERATIVO')
->setCellValue('S1', 'COMENTARIO FINAL')
->setCellValue('T1', 'RELACION')
->setCellValue('U1', 'CANCELADO');

  $lista=$servicio->listaResumen($_POST['fin']);


  $i=2;
  foreach ($lista as $serv) {
    //arreglar las fechas
    $fechai=explode("-", $serv['f_inicio']);
    $inicio=$fechai[2]."-".$fechai[1]."-".$fechai[0];
    if ($serv['f_fin']!=NULL && $serv['f_fin']!='0000-00-00') {
      $fechaf=explode("-", $serv['f_fin']);
      $fin=$fechaf[2]."-".$fechaf[1]."-".$fechaf[0];
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
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i, $serv['descripcion'])
    ->setCellValue('B'.$i, $serv['modelos'])
    ->setCellValue('C'.$i, $inicio)
    ->setCellValue('D'.$i, $fin)
    ->setCellValue('E'.$i, $serv['recursos'])
    ->setCellValue('F'.$i, $recurso['tm'])
    ->setCellValue('G'.$i, $recurso['tt'])
    ->setCellValue('H'.$i, $recurso['tn'])
    ->setCellValue('I'.$i, $recurso['tc'])
    ->setCellValue('J'.$i, $otros)
    ->setCellValue('K'.$i, $clientes['nombre'])
    ->setCellValue('L'.$i, $serv['responsable'])
    ->setCellValue('M'.$i, $serv['telefono'])
    ->setCellValue('N'.$i, $serv['correo'])
    ->setCellValue('O'.$i, $serv['com_supervisor'])
    ->setCellValue('P'.$i, $serv['com_rrhh'])
    ->setCellValue('Q'.$i, $serv['com_admin_fin'])
    ->setCellValue('R'.$i, $serv['com_depto'])
    ->setCellValue('S'.$i, $serv['com_fin'])
    ->setCellValue('T'.$i, $relacionado)
    ->setCellValue('U'.$i, $cancelado);
    $i++;
    //sacar modificaciones de esa actividad
    $modInfo=$servicio->infoResumen($serv['id'], $_POST['inicio'], $_POST['fin']);
    $numeroInfo=count($modInfo);
    $modRecursos=$servicio->diasRecursosResumen($serv['id'], $_POST['inicio'], $_POST['fin']);
    $numeroRecursos=count($modRecursos);
    //modificaciones de la informacion
    if ($numeroInfo>0) {
      foreach ($modInfo as $info) {
        //comrpobacion de las fechas y cambio de formato de las fechas
        if ($info['inicio']!='0000-00-00') {
          $inicioInfo=explode("-", $info['inicio']);
          $inicioInfo=$inicioInfo[2]."-".$inicioInfo[1]."-".$inicioInfo[0];
          if ($info['fin']!='0000-00-00') {
            $finInfo=explode("-", $info['fin']);
            $finInfo=$finInfo[2]."-".$finInfo[1]."-".$finInfo[0];
          }else {
            $finInfo='';
          }
        }else {
          $inicioInfo=explode("-", $info['suelto']);
          $inicioInfo=$inicioInfo[2]."-".$inicioInfo[1]."-".$inicioInfo[0];
          $finInfo=explode("-", $info['suelto']);
          $finInfo=$finInfo[2]."-".$finInfo[1]."-".$finInfo[0];
        }
        //cambiar color de la letra
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':Z'.$i.'')->applyFromArray($style);
        //escribir los datos en el excel
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $info['descripcion'])
        ->setCellValue('B'.$i, $info['modelos'])
        ->setCellValue('C'.$i, $inicioInfo)
        ->setCellValue('D'.$i, $finInfo)
        ->setCellValue('L'.$i, $info['responsable'])
        ->setCellValue('M'.$i, $info['telefono'])
        ->setCellValue('N'.$i, $info['correo']);
        $i++;
      }
    }

    //modificaciones de los recursos
    if ($numeroRecursos>0) {
      foreach ($modRecursos as $mod) {
        //comrpobacion de las fechas y cambio de formato de las fechas
        if ($mod['inicio']!='0000-00-00') {
          $inicioRec=explode("-", $mod['inicio']);
          $inicioRec=$inicioRec[2]."-".$inicioRec[1]."-".$inicioRec[0];
          if ($mod['fin']!='0000-00-00') {
            $finRec=$mod['fin'];
          }else {
            $finRec='';
          }
        }else {
          $inicioRec=$mod['suelto'];
          $finRec=$mod['suelto'];
        }
        //juntamos los recursos de horarios raros
        $otros=0;
        if ($mod['otro1']!=0) {
          $otros=$mod['otro1']." (".$mod['inicio1']."-".$mod['fin1'].") //";
        }
        if ($mod['otro2']!=0) {
          $otros=$otros. $mod['otro2']." (".$mod['inicio2']."-".$mod['fin2'].") //";
        }
        if ($mod['otro3']!=0) {
          $otros=$otros. $mod['otro3']." (".$mod['inicio3']."-".$mod['fin3'].") //";
        }
        if ($mod['otro4']!=0) {
          $otros=$otros. $mod['otro4']." (".$mod['inicio4']."-".$mod['fin4'].") //";
        }
        if ($mod['otro5']!=0) {
          $otros=$otros. $mod['otro5']." (".$mod['inicio5']."-".$mod['fin5'].") //";
        }
        if ($mod['otro6']!=0) {
          $otros=$otros. $mod['otro6']." (".$mod['inicio6']."-".$mod['fin6'].")";
        }
        //cambiar color de la letra
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':Z'.$i.'')->applyFromArray($style);
        //escribimos los datos en el sitio
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('C'.$i, $inicioRec)
        ->setCellValue('D'.$i, $finRec)
        ->setCellValue('E'.$i, $mod['total'])
        ->setCellValue('F'.$i, $mod['tm'])
        ->setCellValue('G'.$i, $mod['tt'])
        ->setCellValue('H'.$i, $mod['tn'])
        ->setCellValue('I'.$i, $mod['tc'])
        ->setCellValue('J'.$i, $otros);
        $i++;
      }
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
$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold(true);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
