<?php
/* Incluir la libreria PHPExcel */
require_once '../operativa/Classes/PHPExcel.php';
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

/*Incluir los archivos necesarios para la db*/
require_once './ddbb/excel.php';
$empleado=new Excel();


// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

//crear arrays de estilo.
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));

// Agregar Informacion
$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style_header);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'NOMBRE')
->setCellValue('B1', 'APELLIDOS')
->setCellValue('C1', 'USUARIO')
->setCellValue('D1', 'TELEFONO')
->setCellValue('E1', 'ALTA')
->setCellValue('F1', 'VACACIONES')
->setCellValue('G1', 'INCAPACIDAD TEMPORAL')
->setCellValue('H1', 'ETT')
->setCellValue('I1', 'PASE FORD')
->setCellValue('J1', 'DNI')
->setCellValue('K1', 'CARNET CONDUCIR')
->setCellValue('L1', 'CONDUCIR FORD')
->setCellValue('M1', 'MEDICO')
->setCellValue('N1', 'NACIMIENTO')
;

if (isset($_GET['b'])) {
  $filtro=base64_decode($_GET['b']);
  $lista=$empleado->listaFiltrados($filtro);
}else {
  $lista=$empleado->listaEmpleados();
}

  $i=2;
foreach ($lista as $trabajador) {
  //traducir campo alta.
  if ($trabajador['alta']==0) {
    $alta = 'SI';
  }else {
    $alta = 'NO';
  }

  //traducir campo vacaciones.
  if ($trabajador['vacaciones']==0) {
    $vacaciones = 'NO';
  }else {
    $vacaciones = 'SI';
  }
  //traducir campo ett.
  if ($trabajador['ett']==0) {
    $ett = 'NO';
  }else {
    $ett = 'SI';
  }

  //traducir campo incapacidad Temporal
  if ($trabajador['incapa_temporal']!=0) {
    $incapa = $empleado->ConverseIncapacidadId($trabajador['incapa_temporal']);
    $incapa = $incapa['tipo'];
  }else {
    $incapa=' ';
  }

  //sacar las fechas de ese empleado.
  $dates= $empleado->fechaId($trabajador['id']);
  //comprobar que la fecha no esta vacia.
  if ($dates['pase_ford']=='0000-00-00' || $dates['pase_ford']==null) {
    $pase ='';
  }else {
    $pase = $dates['pase_ford'];
  }
  if ($dates['dni']=='0000-00-00' || $dates['dni']==null) {
    $dni ='';
  }else {
    $dni = $dates['dni'];
  }
  if ($dates['carnet_conducir']=='0000-00-00' || $dates['carnet_conducir']==null) {
    $conducir ='';
  }else {
    $conducir = $dates['carnet_conducir'];
  }
  if ($dates['conducir_ford']=='0000-00-00' || $dates['conducir_ford']==null) {
    $cford ='';
  }else {
    $cford = $dates['conducir_ford'];
  }
  if ($dates['medico']=='0000-00-00' || $dates['medico']==null) {
    $medico ='';
  }else {
    $medico = $dates['medico'];
  }
  if ($dates['nacimiento']=='0000-00-00' || $dates['nacimiento']==null) {
    $nacimiento ='';
  }else {
    $nacimiento = $dates['nacimiento'];
  }
  //sacar los datos del empleado
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $trabajador['nombre'])
  ->setCellValue('B'.$i, $trabajador['apellidos'] )
  ->setCellValue('C'.$i, $trabajador['user'])
  ->setCellValue('D'.$i, $trabajador['telefono'])
  ->setCellValue('E'.$i, $alta)
  ->setCellValue('F'.$i, $vacaciones)
  ->setCellValue('G'.$i, $incapa)
  ->setCellValue('H'.$i, $ett)
  ->setCellValue('I'.$i, PHPExcel_Shared_Date::PHPToExcel( $pase ))
  ->setCellValue('J'.$i, PHPExcel_Shared_Date::PHPToExcel( $dni ))
  ->setCellValue('K'.$i, PHPExcel_Shared_Date::PHPToExcel( $conducir ))
  ->setCellValue('L'.$i, PHPExcel_Shared_Date::PHPToExcel( $cford ))
  ->setCellValue('M'.$i, PHPExcel_Shared_Date::PHPToExcel( $medico ))
  ->setCellValue('N'.$i, PHPExcel_Shared_Date::PHPToExcel( $nacimiento ))
  ;
  $i++;
}
/*for ($i=0; $i <200 ; $i++) {
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, "trabajador['nombre']")
  ->setCellValue('B'.$i, "trabajador['apellidos']" )
  ->setCellValue('C'.$i, "trabajador['user']")
  ->setCellValue('D'.$i, "trabajador['telefono']")
  ->setCellValue('E'.$i, "alta")
  ->setCellValue('F'.$i, "vacaciones")
  ->setCellValue('G'.$i, "incapa")
  ->setCellValue('H'.$i, "ett")
  ->setCellValue('I'.$i, "PHPExcel_Shared_Date::PHPToExcel( pase )")
  ->setCellValue('J'.$i, "PHPExcel_Shared_Date::PHPToExcel( dni )")
  ->setCellValue('K'.$i, "PHPExcel_Shared_Date::PHPToExcel( conducir )")
  ->setCellValue('L'.$i, "PHPExcel_Shared_Date::PHPToExcel( cford )")
  ->setCellValue('M'.$i, "PHPExcel_Shared_Date::PHPToExcel( medico )")
  ->setCellValue('N'.$i, "PHPExcel_Shared_Date::PHPToExcel( nacimiento )")
  ;
}*/

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
//adaptar las celdas al ancho del texto
for($col = 'A'; $col !== 'Z'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}
//poner el encabezado en negrita
$objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:N".$i)->applyFromArray($centrar);

//poner los bordes
$i2 = $i -1;
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A1:N".$i2)->applyFromArray($bordes);

//dar formato de fecha a las celdas de fechas.
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('I2:N'.$i)->getNumberFormat()->setFormatCode($formatoFecha);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Empleados.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
