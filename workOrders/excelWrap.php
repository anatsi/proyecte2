<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once 'wrapGuard.php';
$wraps=new WrapGuard();

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
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'VIN')
->setCellValue('B1', 'USUARIO 1')
->setCellValue('C1', 'USUARIO 2')
->setCellValue('D1', 'MODELO')
->setCellValue('E1', 'DESTINO')
->setCellValue('F1', 'FECHA')
->setCellValue('G1', 'HORA')
->setCellValue('H1', 'VECES TRABAJADO')
;

if (isset($_GET['b'])) {
  $filtro=base64_decode($_GET['b']);
  $lista=$wraps->listaWrapFiltrados($filtro);
}else {
  $lista=$wraps->listaWrapGuard();
}

  $i=2;
foreach ($lista as $wrap) {
  $fecha = str_replace("-", "/", $wrap['fecha']);

  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $wrap['bastidor'])
  ->setCellValue('B'.$i, $wrap['usuario1'])
  ->setCellValue('C'.$i, $wrap['usuario2'])
  ->setCellValue('D'.$i, $wrap['modelo'])
  ->setCellValue('E'.$i, $wrap['destino'])
  ->setCellValue('F'.$i, $fecha)
  ->setCellValue('G'.$i, $wrap['hora'])
  ->setCellValue('H'.$i, $wrap['repetido'])
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
$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:H999")->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:H".$i2)->applyFromArray($bordes);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WrapGuard.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
