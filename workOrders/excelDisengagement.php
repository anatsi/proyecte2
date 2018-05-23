<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once './bbdd/disengagement.php';
$disengagements=new Disengagement();

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

//crear arrays de estilo.
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));

// Agregar Informacion
$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'VIN')
->setCellValue('B1', 'CONSTRUCCION')
->setCellValue('C1', 'FECHA')
->setCellValue('D1', 'HORA')
->setCellValue('E1', 'TAMAÑO')
->setCellValue('F1', 'MODELO')
->setCellValue('G1', 'RUIDO')
->setCellValue('H1', 'DERECHA')
->setCellValue('I1', 'REPARADO')
->setCellValue('J1', 'IZQUIERDA')
->setCellValue('K1', 'REPARADO')
->setCellValue('L1', 'USUARIO')
;

if (isset($_GET['b'])) {
  $filtro=base64_decode($_GET['b']);
  $lista=$disengagements->listaDisengagementFiltro($filtro);
}else {
  $lista=$disengagements->listaDisengagement();
}

  $i=2;
foreach ($lista as $disengagement) {
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $disengagement['bastidor'])
  ->setCellValue('B'.$i, PHPExcel_Shared_Date::PHPToExcel($disengagement['construccion']))
  ->setCellValue('C'.$i, PHPExcel_Shared_Date::PHPToExcel( $disengagement['fecha'] ))
  ->setCellValue('D'.$i, $disengagement['hora'])
  ->setCellValue('E'.$i, $disengagement['tamano'])
  ->setCellValue('F'.$i, $disengagement['tipo'] )
  ->setCellValue('G'.$i, $disengagement['ruido'])
  ->setCellValue('H'.$i, $disengagement['derecha'])
  ->setCellValue('I'.$i, $disengagement['derechaR'])
  ->setCellValue('J'.$i, $disengagement['izquierda'])
  ->setCellValue('K'.$i, $disengagement['izquierdaR'])
  ->setCellValue('L'.$i, $disengagement['usuario'])
  ;
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
$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:L".$i)->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:L".$i2)->applyFromArray($bordes);

//dar formato de fecha a las celdas de fechas.
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$i)->getNumberFormat()->setFormatCode($formatoFecha);
$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$i)->getNumberFormat()->setFormatCode($formatoFecha);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Disengagement.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
