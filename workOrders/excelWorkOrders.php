<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once 'movimientos.php';
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
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'VIN')
->setCellValue('B1', 'ORIGEN')
->setCellValue('C1', 'FECHA ORIGEN')
->setCellValue('D1', 'HORA ORIGEN')
->setCellValue('E1', 'DESTINO')
->setCellValue('F1', 'FECHA DESTINO')
->setCellValue('G1', 'HORA DESTINO')
->setCellValue('H1', 'TIEMPO PRODUCTIVO')
->setCellValue('I1', 'TIEMPO NO PRODUCTIVO')
->setCellValue('J1', 'TIEMPO CICLO')
->setCellValue('K1', 'USUARIO')
->setCellValue('L1', 'ROL')
->setCellValue('M1', 'ERROR')
;

if (isset($_GET['b'])) {
  $filtro=base64_decode($_GET['b']);
  $lista=$movimientos->listaMovimientosFiltrados($filtro);
}else {
  $lista=$movimientos->listaMovimientos();
}

  $i=2;
foreach ($lista as $movimiento) {
  $diferencia = $movimientos ->RestarHoras($movimiento['hora_origen'], $movimiento['hora_destino']);
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
  $fecha_origen = str_replace("-", "/", $movimiento['fecha_origen']);
  $fecha_destino = str_replace("-", "/", $movimiento['fecha_destino']);
  //sacar si hay error o no
  if ($movimiento['error']==1) {
    $error = 'SI';
    $noproductivo = '00:00:00';
    $ciclo = '00:00:00';
  }else {
    $error = 'NO';
  }
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $movimiento['bastidor'])
  ->setCellValue('B'.$i, $movimiento['origen'])
  ->setCellValue('C'.$i, $fecha_origen)
  ->setCellValue('D'.$i, $movimiento['hora_origen'])
  ->setCellValue('E'.$i, $movimiento['destino'])
  ->setCellValue('F'.$i, $fecha_destino)
  ->setCellValue('G'.$i, $movimiento['hora_destino'])
  ->setCellValue('H'.$i, $diferencia)
  ->setCellValue('I'.$i, $noproductivo)
  ->setCellValue('J'.$i, $ciclo)
  ->setCellValue('K'.$i, $movimiento['usuario'])
  ->setCellValue('L'.$i, $movimiento['rol'])
  ->setCellValue('M'.$i, $error)
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
$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:M".$i)->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:M".$i2)->applyFromArray($bordes);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WorkOrders.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
