<?php
/* Incluir la libreria PHPExcel */
require_once '../Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once '../bbdd/servicio.php';
require_once '../bbdd/cliente.php';
require_once '../bbdd/recursos.php';
$cliente= new Cliente();
$servicio= new Servicio();
$recursos= new Recursos();

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

//crear arrays de estilo.
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));
$OtherStyle_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'d9d9d9'),));

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

// Agregar Informacion
$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->applyFromArray($style_header);

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'ACTIVIDAD')
->setCellValue('B1', 'DESCRIPCION')
->setCellValue('C1', 'MODELOS')
->setCellValue('D1', 'FECHA INICIO')
->setCellValue('E1', 'FECHA FIN')
->setCellValue('F1', 'TOTAL RECURSOS')
->setCellValue('G1', 'TM')
->setCellValue('H1', 'TT')
->setCellValue('I1', 'TN')
->setCellValue('J1', 'TC')
->setCellValue('K1', 'TE')
->setCellValue('L1', 'CLIENTE')
->setCellValue('M1', 'RESPONSABLE')
->setCellValue('N1', 'TELEFONO')
->setCellValue('O1', 'CORREO')
->setCellValue('P1', 'COMENTARIO SUPERVISOR')
->setCellValue('Q1', 'COMENTARIO RRHH')
->setCellValue('R1', 'COMENTARIO ADMIN. FINANCIERO')
->setCellValue('S1', 'COMENTARIO FINAL')
->setCellValue('T1', 'CANCELADO')
->setCellValue('U1', 'RELACION');

$lista=$servicio->listaServiciosHoy();
  $i=2;
foreach ($lista as $serv) {
  //arreglar las fechas
  $inicio=$serv['f_inicio'];
  if ($serv['f_fin']!=NULL && $serv['f_fin']!='0000-00-00') {
    $fin=$serv['f_fin'];
  }else {
    $fin='';
  }
  //sacar el nombre de los clientes
  $clientes=$cliente->ClienteId($serv['id_cliente']);
  //sacar y arreglar los recursos del servicio
  $recurso=$recursos->RecursosId($serv['id']);
  $otros=0;
  if ($recurso['otro1']!=0) {
    $otros=$recurso['otro1']." (".$recurso['inicio1']."-".$recurso['fin1'].") //";
  }
  if ($recurso['otro2']!=0) {
    $otros=$otros . $recurso['otro2']." (".$recurso['inicio2']."-".$recurso['fin2'].") //";
  }
  if ($recurso['otro3']!=0) {
    $otros=$otros . $recurso['otro3']." (".$recurso['inicio3']."-".$recurso['fin3'].") //";
  }
  if ($recurso['otro4']!=0) {
    $otros=$otros . $recurso['otro4']." (".$recurso['inicio4']."-".$recurso['fin4'].") //";
  }
  if ($recurso['otro5']!=0) {
    $otros=$otros . $recurso['otro5']." (".$recurso['inicio5']."-".$recurso['fin5'].") //";
  }
  if ($recurso['otro6']!=0) {
    $otros=$otros . $recurso['otro6']." (".$recurso['inicio6']."-".$recurso['fin6'].")";
  }
  //sacar el nombre del servicio relacionado.
  if ($serv['relacion']!=0 && $serv['relacion']!=null) {
    $relacionar=$servicio->ServicioRelacion($serv['id']);
    $relacionado=$relacionar['relacionada'];
  }else {
    $relacionado='';
  }
  //si esta cancelado poner un si y si no esta, un no

  if ($serv['cancelado']==true) {
    $cancelado='SI';
  }else {
    $cancelado='NO';
  }
  //apuntar los datos en el excel
  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->applyFromArray($OtherStyle_header);
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $serv['descripcion'])
  ->setCellValue('B'.$i, $serv['com_depto'])
  ->setCellValue('C'.$i, $serv['modelos'])
  ->setCellValue('D'.$i, PHPExcel_Shared_Date::PHPToExcel( $inicio ))
  ->setCellValue('E'.$i, PHPExcel_Shared_Date::PHPToExcel( $fin ))
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
  ->setCellValue('T'.$i, $cancelado)
  ->setCellValue('U'.$i, $relacionado);
  $i++;
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

//dar formato de fecha a las celdas de fechas.
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$i)->getNumberFormat()->setFormatCode($formatoFecha);
$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$i)->getNumberFormat()->setFormatCode($formatoFecha);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
