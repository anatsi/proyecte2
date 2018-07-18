<?php
/* Incluir la libreria PHPExcel */
require_once '../Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once '../bbdd/servicio.php';
require_once '../bbdd/cliente.php';
require_once '../bbdd/recursos.php';
require_once '../bbdd/empleados.php';
$cliente= new Cliente();
$servicio= new Servicio();
$recursos= new Recursos();
$empleados = new Empleados();

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();


// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

$OtherStyle_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'d9d9d9'),));


$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'FECHA')
->setCellValue('A2', 'TURNO')
->setCellValue('B1', PHPExcel_Shared_Date::PHPToExcel( $_POST['fecha'] ))
->setCellValue('B2', $_POST['turno'])
;

//sacar las actividades para ese dia.
$listaHoy = $servicio -> listaRRHH($_POST['fecha']);
//iniciamos el contador a 4;
$Ai = 4;
$Bi = 4;

//convertimos el turno que nos han enviado.
if ($_POST['turno'] == 'Mañana') {
  $turno = 'tm';
}elseif ($_POST['turno'] == 'Tarde') {
  $turno = 'tt';
}elseif ( $_POST['turno'] == 'Noche') {
  $turno = 'tn';
}

foreach ($listaHoy as $actividad) {
  //sacar el total de recursos para esa actividad, ese dia y ese turno.
  $recursosTotal = $recursos -> ModSupervisores($actividad['id'], $_POST['fecha'], $turno);
  if ($recursosTotal == null || $recursosTotal == false) {
    $recursosTotal = $recursos -> RecursosSupervisores($actividad['id'], $_POST['fecha'], $turno);
  }
  //sacar los nombres de los empleados de esa actividad para ese dia.
  $listaEmpleados = $empleados -> empleadosServicio($actividad['id'], $_POST['fecha'], $turno);
  //COMPROBAR   que esa actividad tiene recursos para ese turno.
  if ($recursosTotal != null && $recursosTotal != false && $recursosTotal[$turno] > 0) {
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosTotal[$turno]);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosTotal[$turno] ; $gente++) {
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('B'.$Bi, 'SIN ASIGNAR');
          $Bi++;
        }
      }else {
        foreach ($listaEmpleados as $empleado) {
          //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
          if ($empleado['empleado'] == '') {
            $empleado['empleado'] = 'SIN ASIGNAR';
          }
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('B'.$Bi, $empleado['empleado']);
          $Bi++;
        }
      }
    }else {
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion']." - " .$recursosTotal[$turno]);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosTotal[$turno] ; $gente++) {
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$Ai, 'SIN ASIGNAR');
          $Ai++;
        }
      }else {
        foreach ($listaEmpleados as $empleado) {
          //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
          if ($empleado['empleado'] == '') {
            $empleado['empleado'] = 'SIN ASIGNAR';
          }
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$Ai, $empleado['empleado']);
          $Ai++;
        }
      }
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
$objPHPExcel->getActiveSheet()->getStyle("A1:B2")->getFont()->setBold(true);

//centrar todo el texto
$centrar = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle("A1:B100")->applyFromArray($centrar);

//poner los bordes
if ($Ai > $Bi) {
  $total = $Ai-1;
}else {
  $total = $Bi-1;
}
$bordes = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
$objPHPExcel->getActiveSheet()->getStyle("A4:B".$total)->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle("A1:B2")->applyFromArray($bordes);

//dar formato de fecha a las celdas de fechas.
$formatoFecha = 'dd/mm/yyyy';
$objPHPExcel->getActiveSheet()->getStyle('B1')->getNumberFormat()->setFormatCode($formatoFecha);

//dar formato a los encabezados
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));
$objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($style_header);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
