<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

/*Incluir los archivos necesarios para la db*/
require_once './ddbb/excel.php';
$material=new Excel();


// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de material de loas empleados.")
->setKeywords("Excel Office 2007 openxml php");

//crear arrays de estilo.
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));

// Agregar Informacion
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'NOMBRE')
->setCellValue('B1', 'APELLIDOS')
->setCellValue('C1', 'MATERIAL')
->setCellValue('D1', 'FECHA DE ENTREGA ')
->setCellValue('E1', 'TALLA')
->setCellValue('F1', 'CANTIDAD')
;

if (isset($_GET['b'])) {
  $filtro=base64_decode($_GET['b']);
  $lista=$material->materialFiltrados($filtro);
}else {
  $lista=$material->listaMaterial();
}

  $i=2;
foreach ($lista as $entregado){

  //Buscar el nombre  y apellidos del empleado apartir de su id 
   if ($entregado['empleado']!=null){
        $emplead = $material->EmpleadoId($entregado['empleado']);
        $nombre = $emplead['nombre'];
        $apellidos = $emplead['apellidos'];
      }else {
        $nombre=' ';
        $apellidos='';
      }
  
     //traducir campo material de numero a su material que corresponde
      if ($entregado['materiales']!=null){
        $changes = $material->ConverseMaterial($entregado['materiales']);
        $change = $changes['tipo_material'];
      }else {
        $change=' ';
      }

      if ($entregado['fecha_entrega']=='0000-00-00' || $entregado['fecha_entrega']==null) {
        $entreg ='';
      }else {
        $entrega = $entregado['fecha_entrega'];
      }
      if ($entregado['talla']==null){
        $tallas ='';
      }else {
        $tallas = $entregado['talla'];
      }

     if ($entregado['cantidad']==null) {
          $cont ='';
     }else{
          $cont= $entregado['cantidad'];
    }

      
      //sacar los datos del empleado
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, $nombre)
      ->setCellValue('B'.$i, $apellidos)
      ->setCellValue('C'.$i, $change)
      ->setCellValue('D'.$i, PHPExcel_Shared_Date::PHPToExcel( $entrega ))
      ->setCellValue('E'.$i, $entregado['talla'])
      ->setCellValue('F'.$i, $entregado['cantidad'])
     
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

//dar formato de fecha a las celdas de fechas.Y las columnas dondés quiero ponerlo 
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('D2:D2'.$i)->getNumberFormat()->setFormatCode($formatoFecha);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Materiales.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
