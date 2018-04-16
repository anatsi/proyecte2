<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once './bbdd/campa.php';
$campa=new Campa();

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
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'VIN')
->setCellValue('B1', 'FECHA')
->setCellValue('C1', 'HORA')
->setCellValue('D1', 'USUARIO')
->setCellValue('E1', 'PROVEEDOR')
->setCellValue('F1', 'INSPECCION')
;

if (isset($_GET['b'])) {
  $filtro=base64_decode($_GET['b']);
  $lista=$campa->listaCampaFiltro($filtro);
}else {
  $lista=$campa->listaCampa();
}

  $i=2;
foreach ($lista as $campas) {
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $campas['bastidor'])
  ->setCellValue('B'.$i, PHPExcel_Shared_Date::PHPToExcel( $campas['fecha'] ))
  ->setCellValue('C'.$i, $campas['hora'])
  ->setCellValue('D'.$i, $campas['usuario'])
  ->setCellValue('E'.$i, $campas['proveedor'])
  ->setCellValue('F'.$i, $campas['inspeccion'])
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
$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:F".$i)->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:F".$i2)->applyFromArray($bordes);

//dar formato de fecha a las celdas de fechas.
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$i)->getNumberFormat()->setFormatCode($formatoFecha);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Campa.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
