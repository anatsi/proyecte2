<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once './bbdd/movimientos.php';
$movimientos=new Movimientos();

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
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'VIN')
->setCellValue('B1', 'ORIGEN')
->setCellValue('C1', 'FECHA ORIGEN')
->setCellValue('D1', 'HORA ORIGEN')
->setCellValue('E1', 'DESTINO')
->setCellValue('F1', 'FECHA DESTINO')
->setCellValue('G1', 'HORA DESTINO')
->setCellValue('H1', 'USUARIO')
->setCellValue('I1', 'ROL')
->setCellValue('J1', 'ERROR')
->setCellValue('K1', 'LANZAMIENTO')
;

  $lista=$movimientos->listaMovimientosFormExcel($_GET['i'], $_GET['f'], $_GET['u'], $_GET['hi'], $_GET['hf'], $_GET['b'], $_GET['o'], $_GET['d']);


  $i=2;
foreach ($lista as $movimiento) {

  //sacar si hay error o no
  if ($movimiento['error']==1) {
    $error = 'SI';

  }else {
    $error = 'NO';
  }
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $movimiento['bastidor'])
  ->setCellValue('B'.$i, $movimiento['origen'])
  ->setCellValue('C'.$i, PHPExcel_Shared_Date::PHPToExcel( $movimiento['fecha_origen'] ))
  ->setCellValue('D'.$i, $movimiento['hora_origen'])
  ->setCellValue('E'.$i, $movimiento['destino'])
  ->setCellValue('F'.$i, PHPExcel_Shared_Date::PHPToExcel( $movimiento['fecha_destino'] ))
  ->setCellValue('G'.$i, $movimiento['hora_destino'])
  ->setCellValue('H'.$i, $movimiento['usuario'])
  ->setCellValue('I'.$i, $movimiento['rol'])
  ->setCellValue('J'.$i, $error)
  ->setCellValue('K'.$i, $movimiento['lanzamiento'])
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
$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:K".$i)->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:K".$i2)->applyFromArray($bordes);

//dar formato de fecha a las celdas de fechas.
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$i)->getNumberFormat()->setFormatCode($formatoFecha);
$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$i)->getNumberFormat()->setFormatCode($formatoFecha);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WorkOrders.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
