<?php
/* Incluir la libreria PHPExcel */
require_once './Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once 'servicio.php';
require_once 'cliente.php';
require_once 'recursos.php';
$cliente= new Cliente();
$servicio= new Servicio();
$recursos= new Recursos();

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
->setCellValue('C1', 'FECHA INICIO')
->setCellValue('D1', 'FECHA FIN')
->setCellValue('E1', 'TOTAL RECURSOS')
->setCellValue('F1', 'TM')
->setCellValue('G1', 'TT')
->setCellValue('H1', 'TN')
->setCellValue('I1', 'TC')
->setCellValue('J1', 'OTROS')
->setCellValue('K1', 'CLIENTE')
->setCellValue('L1', 'RESPONSABLE')
->setCellValue('M1', 'TELEFONO')
->setCellValue('N1', 'CORREO')
->setCellValue('O1', 'COMENTARIO SUPERVISOR')
->setCellValue('P1', 'COMENTARIO RRHH')
->setCellValue('Q1', 'COMENTARIO ADMIN. FINANCIERO')
->setCellValue('R1', 'COMENTARIO DEPTO. OPERATIVO')
->setCellValue('S1', 'COMENTARIO FINAL')
->setCellValue('T1', 'CANCELADO')
->setCellValue('U1', 'RELACION');

$lista=$servicio->listaServiciosHoy();
  $i=2;
foreach ($lista as $serv) {
  //arreglar las fechas
  $fechai=explode("-", $serv['f_inicio']);
  $inicio=$fechai[2]."-".$fechai[1]."-".$fechai[0];
  if ($serv['f_fin']!=NULL && $serv['f_fin']!='0000-00-00') {
    $fechaf=explode("-", $serv['f_fin']);
    $fin=$fechaf[2]."-".$fechaf[1]."-".$fechaf[0];
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
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A'.$i, $serv['descripcion'])
  ->setCellValue('B'.$i, $serv['modelos'])
  ->setCellValue('C'.$i, $inicio)
  ->setCellValue('D'.$i, $fin)
  ->setCellValue('E'.$i, $serv['recursos'])
  ->setCellValue('F'.$i, $recurso['tm'])
  ->setCellValue('G'.$i, $recurso['tt'])
  ->setCellValue('H'.$i, $recurso['tn'])
  ->setCellValue('I'.$i, $recurso['tc'])
  ->setCellValue('J'.$i, $otros)
  ->setCellValue('K'.$i, $clientes['nombre'])
  ->setCellValue('L'.$i, $serv['responsable'])
  ->setCellValue('M'.$i, $serv['telefono'])
  ->setCellValue('N'.$i, $serv['correo'])
  ->setCellValue('O'.$i, $serv['com_supervisor'])
  ->setCellValue('P'.$i, $serv['com_rrhh'])
  ->setCellValue('Q'.$i, $serv['com_admin_fin'])
  ->setCellValue('R'.$i, $serv['com_depto'])
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
$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold(true);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
