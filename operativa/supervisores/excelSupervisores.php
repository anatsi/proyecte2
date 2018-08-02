<?php
/* Incluir la libreria PHPExcel */
require_once '../Classes/PHPExcel.php';

/*Incluir los archivos necesarios para la db*/
require_once '../bbdd/servicio.php';
require_once '../bbdd/cliente.php';
require_once '../bbdd/recursos.php';
require_once '../bbdd/empleados.php';
require_once '../bbdd/personal.php';
$cliente= new Cliente();
$servicio= new Servicio();
$recursos= new Recursos();
$empleados = new Empleados();
$personal = new Personal();

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();


// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Tomas")
->setTitle("Documento Excel de Actividades Actuales")
->setDescription("Resumen de las actividades actuales.")
->setKeywords("Excel Office 2007 openxml php");

$OtherStyle_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'d9d9d9'),));
$style_header = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'538DD5'),));
$formatoFecha = 'dd/mm/yyyy';
$OtherStyle_header2 = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'9CD8A3'),));


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
  $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], $turno);
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

//ACTIVIDADES CON HORARIOS RAROS.
//ponemos dos lineas en blanco antes de empezar a escribirlas.
if ($Bi > $Ai) {
  $Bi = $Bi+3;
  $Ai = $Bi;
}else {
  $Ai = $Ai+3;
  $Bi = $Ai;
}

//anunciamos que a partir de ahora vienen turnos especiales.
//poner estilo al encabezado y la fecha
$objPHPExcel->getActiveSheet()->getStyle("A".$Ai.":B".$Bi)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.$Ai.':B'.$Bi)->applyFromArray($style_header);
$objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->getNumberFormat()->setFormatCode($formatoFecha);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$Ai, PHPExcel_Shared_Date::PHPToExcel( $_POST['fecha'] ))
->setCellValue('B'.$Bi, 'TURNOS ESPECIALES');
$Bi=$Bi+2;
$Ai=$Ai+2;


foreach ($listaHoy as $actividad) {
  //SACAR RECURSOS PARA ACTIVIDADES CON HORARIOS RAROS
  $recursosRaros = $recursos ->ModSupervisoresRaros($actividad['id'], $_POST['fecha']);
  if ($recursosRaros == null || $recursosRaros == false) {
    $recursosRaros = $recursos -> RecursosId($actividad['id']);
  }
  //para cada uno de los turnos raros comprobamos si hay recursos en ese dia.
  if ($recursosRaros['tc']>0 && $recursosRaros['tc'] != null && $recursosRaros['tc'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'tc');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'Turno central');
      $Bi++;
      //descripcion de la actividad y numero de recursos
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['tc']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['tc'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'Turno central');
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['tc']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['tc'] ; $gente++) {
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

  //TURNO RARO 1
  if ($recursosRaros['otro1']>0 && $recursosRaros['otro1'] != null && $recursosRaros['otro1'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'otro1');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'DE ' .$recursosRaros['inicio1'] .' A ' .$recursosRaros['fin1']);
      $Bi++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['otro1']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro1'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'DE ' .$recursosRaros['inicio1'] .' A ' .$recursosRaros['fin1']);
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['otro1']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro1'] ; $gente++) {
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

  //TURNO RARO 2
  if ($recursosRaros['otro2']>0 && $recursosRaros['otro2'] != null && $recursosRaros['otro2'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'otro2');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'DE ' .$recursosRaros['inicio2'] .' A ' .$recursosRaros['fin2']);
      $Bi++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['otro2']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro2'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'DE ' .$recursosRaros['inicio2'] .' A ' .$recursosRaros['fin2']);
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['otro2']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro2'] ; $gente++) {
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

  //TURNO RARO 3
  if ($recursosRaros['otro3']>0 && $recursosRaros['otro3'] != null && $recursosRaros['otro3'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'otro3');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'DE ' .$recursosRaros['inicio3'] .' A ' .$recursosRaros['fin3']);
      $Bi++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['otro3']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro3'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'DE ' .$recursosRaros['inicio3'] .' A ' .$recursosRaros['fin3']);
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['otro3']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro3'] ; $gente++) {
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

  //TURNO RARO 4
  if ($recursosRaros['otro4']>0 && $recursosRaros['otro4'] != null && $recursosRaros['otro4'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'otro4');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'DE ' .$recursosRaros['inicio4'] .' A ' .$recursosRaros['fin4']);
      $Bi++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['otro4']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro4'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'DE ' .$recursosRaros['inicio4'] .' A ' .$recursosRaros['fin4']);
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['otro4']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro4'] ; $gente++) {
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

  //TURNO RARO 5
  if ($recursosRaros['otro5']>0 && $recursosRaros['otro5'] != null && $recursosRaros['otro5'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'otro5');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'DE ' .$recursosRaros['inicio5'] .' A ' .$recursosRaros['fin5']);
      $Bi++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['otro5']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro5'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'DE ' .$recursosRaros['inicio5'] .' A ' .$recursosRaros['fin5']);
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['otro5']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro5'] ; $gente++) {
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

  //TURNO RARO 6
  if ($recursosRaros['otro6']>0 && $recursosRaros['otro6'] != null && $recursosRaros['otro6'] != false) {
    //si los recursos son mayor que 0, sacamos los nombres asignados a esta actividad.
    $listaEmpleados = $personal -> empleadosServicio($actividad['id'], $_POST['fecha'], 'otro6');
    //elegimos si escribir en la columna A o B.
    if ($Bi < $Ai) {
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, 'DE ' .$recursosRaros['inicio6'] .' A ' .$recursosRaros['fin6']);
      $Bi++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('B'.$Bi)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$Bi, $actividad['descripcion'] ." - " .$recursosRaros['otro6']);
      $Bi++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro6'] ; $gente++) {
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
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header2);
      //informacion sobre el turno
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, 'DE ' .$recursosRaros['inicio6'] .' A ' .$recursosRaros['fin6']);
      $Ai++;
      //cambiar el estilo de la cabecera de la actividad
      $objPHPExcel->getActiveSheet()->getStyle('A'.$Ai)->applyFromArray($OtherStyle_header);
      //descripcion de la actividad y numero de recursos
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$Ai, $actividad['descripcion'] ." - " .$recursosRaros['otro6']);
      $Ai++;
      //sacar la lista de empleados para esa actividad, dia y turno.
      if ($listaEmpleados == null || $listaEmpleados == '') {
        for ($gente=0; $gente < $recursosRaros['otro6'] ; $gente++) {
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
$objPHPExcel->getActiveSheet()->getStyle('B1')->getNumberFormat()->setFormatCode($formatoFecha);

//dar formato a los encabezados
$objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($style_header);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 ?>
