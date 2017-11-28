<?php
/* Incluir la libreria PHPExcel */
require_once './Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once 'servicio.php';
require_once 'cliente.php';
$cliente= new Cliente();
$servicio= new Servicio();

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'ACTIVIDAD')
->setCellValue('B1', 'MODELOS')
->setCellValue('C1', 'CLIENTE')
->setCellValue('D1', 'RESPONSABLE');

if (isset($_GET['b'])) {
  $lista=$servicio->listaFiltrados($_GET['b']);
}else {
  $lista=$servicio->listaFinalizados();
}

  $i=2;
foreach ($lista as $serv) {
  $fecha=explode("-", $serv['f_inicio']);
  $fechaHoy=$fecha[2]."-".$fecha[1]."-".$fecha[0];
  $clientes=$cliente->ClienteId($serv['id_cliente']);
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $serv['descripcion'])
  ->setCellValue('B'.$i, $serv['modelos'])
  ->setCellValue('C'.$i, $clientes['nombre'])
  ->setCellValue('D'.$i, $serv['responsable']);
  $i++;
}

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
