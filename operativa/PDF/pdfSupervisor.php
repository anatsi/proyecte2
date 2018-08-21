<?php
  /*Incluir los archivos necesarios para la db*/
  require_once '../bbdd/servicio.php';
  require_once '../bbdd/cliente.php';
  require_once '../bbdd/recursos.php';
  require_once '../bbdd/empleados.php';
  require_once '../bbdd/personal.php';
  require_once '../../ddbb/users.php';
  $cliente= new Cliente();
  $servicio= new Servicio();
  $recursos= new Recursos();
  $empleados = new Empleados();
  $personal = new Personal();
  $usuario=new User();
  //sacar el nombre del usuario conectado
  $nombreUsuario=$usuario->nombreUsuario($_GET['u']);
  //incluir libreria de pdf's
  require 'fpdf/fpdf.php';
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','',10);
  ////////CABEZA FIJA DE LA PAGINA
  //CONVERTIMOS ORDEN DE LA FECHA PARA SACARLA EN LA CABECERA
  $fechaMostrar=explode("-", $_GET['fecha']);
  $fechaMostrar=$fechaMostrar[2]."-".$fechaMostrar[1]."-".$fechaMostrar[0];
//SACAR EL NUMERO DE SEMANA A PARTIR DE LA FECHA
  $semana = date("W", strtotime($_GET['fecha']));
  //SACAR EL NOMBRE DEL MES A PARTIR DE LA FECHA
  $mes = date("F", strtotime($_GET['fecha']));

  $pdf->SetY(5);
  $pdf->Image('../../assets/files/logo.png',15,8,20,13,"PNG");
  $pdf->SetX(40);
  $pdf->Cell(80,20,"GRUPOS DE TRABAJO",1,0,"C");
  $pdf->Cell(30,5,"Turno",1,0,"C");
  $pdf->Cell(40,5,utf8_decode($_GET['turno']),1,1,"C");
  // Empezar a 120 mm para el segundo cuadro
  $pdf->SetX(120);
  $pdf->Cell(30,5,"Jefe Turno",1,0,"C");
  $pdf->Cell(40,5,$nombreUsuario['name'],1,1,"C");
  $pdf->SetX(120);
  $pdf->Cell(30,5,"Semana",1,0,"C");
  $pdf->Cell(40,5,$semana,1,1,"C");
  $pdf->SetX(120);
  $pdf->Cell(30,5,"Dia",1,0,"C");
  $pdf->Cell(40,5,$fechaMostrar,1,1,"C");
  /////////FIN CABEZA DE LA PAGINA
  ////DEBUT ACTIVIDADES

  $derNombre=0;
  $izqNombre=0;
  $t=5;
  $sepa=10;
  $p=1;
  $p2=1;
  $cont=0;
  $cont1=0;
  $der=30;
  $izq=40;
  $add=30;
  $i=0;

  //sacar las actividades para ese dia.
  $listaHoy = $servicio -> listaRRHH($_GET['fecha']);

  //convertimos el turno que nos han enviado.
  if ($_GET['turno'] == 'Mañana') {
    $turno = 'tm';
  }elseif ($_GET['turno'] == 'Tarde') {
    $turno = 'tt';
  }elseif ( $_GET['turno'] == 'Noche') {
    $turno = 'tn';
  }

  foreach ($listaHoy as $act) {
    //sacar el total de recursos para esa actividad, ese dia y ese turno.
    $recursosTotal = $recursos -> ModSupervisores($act['id'], $_GET['fecha'], $turno);
    if ($recursosTotal == null || $recursosTotal == false) {
      $recursosTotal = $recursos -> RecursosSupervisores($act['id'], $_GET['fecha'], $turno);
    }

    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], $turno);

    //COMPROBAR   que esa actividad tiene recursos para ese turno.
    if ($recursosTotal != null && $recursosTotal != false && $recursosTotal[$turno] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal[$turno];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");

         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal[$turno] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal[$turno] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

  }
  //si el supervisor ha puesto algun comentario, lo escribimos a continuacion
  if ($_GET['com'] && $_GET['com'] != '') {
    //comprobamos que columna es mas larga
    if ($der > $izq) {
      $total = $der;
    }else {
      $total = $izq;
    }
    //titulo del comentario
    $pdf->SetY($total);
    $pdf->SetTextColor(220,50,50);
    $pdf->Cell(190,5,'MODIFICACIONES DE PERSONAL.',1,2,"C");
    //creamos la celda con el comentario
    $comentario = base64_decode($_GET['com']);
    $pdf->SetTextColor(3, 3, 3);
    $total = $total+5;
    $pdf->SetY($total);
    $pdf->MultiCell(190, 5, utf8_decode($comentario), 1);

  }

  //TURNOS ESPECIALES
  $pdf->AddPage();
  $pdf->SetY(5);
  $pdf->Image('../../assets/files/logo.png',15,8,20,13,"PNG");
  $pdf->SetX(40);
  $pdf->Cell(80,20,"GRUPOS DE TRABAJO",1,0,"C");
  $pdf->Cell(30,5,"Turno",1,0,"C");
  $pdf->Cell(40,5,'ESPECIALES',1,1,"C");
  // Empezar a 120 mm para el segundo cuadro
  $pdf->SetX(120);
  $pdf->Cell(30,5,"Jefe Turno",1,0,"C");
  $pdf->Cell(40,5,$nombreUsuario['name'],1,1,"C");
  $pdf->SetX(120);
  $pdf->Cell(30,5,"Semana",1,0,"C");
  $pdf->Cell(40,5,$semana,1,1,"C");
  $pdf->SetX(120);
  $pdf->Cell(30,5,"Dia",1,0,"C");
  $pdf->Cell(40,5,$fechaMostrar,1,1,"C");
  /////////FIN CABEZA DE LA PAGINA
  ////DEBUT ACTIVIDADES

  $derNombre=0;
  $izqNombre=0;
  $t=5;
  $sepa=10;
  $p=1;
  $p2=1;
  $cont=0;
  $cont1=0;
  $der=30;
  $izq=40;
  $add=30;
  $i=0;
  foreach ($listaHoy as $act){
    //SACAR RECURSOS PARA ACTIVIDADES CON HORARIOS RAROS
    $recursosTotal = $recursos ->ModSupervisoresRaros($act['id'], $_GET['fecha']);
    if ($recursosTotal == null || $recursosTotal == false) {
      $recursosTotal = $recursos -> RecursosId($act['id']);
    }

    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'tc');

    //COMPROBAR   que esa actividad tiene recursos para ese turno.
    //TURNO CENTRAL
    if ($recursosTotal['tc'] != null && $recursosTotal['tc'] != false && $recursosTotal['tc'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['tc'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,'TURNO CENTRAL',1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['tc'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,'TURNO CENTRAL',1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['tc'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

    //TURNO RARO 1
    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'otro1');
    if ($recursosTotal['otro1'] != null && $recursosTotal['otro1'] != false && $recursosTotal['otro1'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['otro1'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $horas = 'DE ' .$recursosTotal['inicio1'] .' A ' .$recursosTotal['fin1'];
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro1'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $horas = 'DE ' .$recursosTotal['inicio1'] .' A ' .$recursosTotal['fin1'];
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro1'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

    //TURNO RARO 2
    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'otro2');
    if ($recursosTotal['otro2'] != null && $recursosTotal['otro2'] != false && $recursosTotal['otro2'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['otro2'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $horas = 'DE ' .$recursosTotal['inicio2'] .' A ' .$recursosTotal['fin2'];
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro2'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $horas = 'DE ' .$recursosTotal['inicio2'] .' A ' .$recursosTotal['fin2'];
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro2'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

    //TURNO RARO 3
    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'otro3');
    if ($recursosTotal['otro3'] != null && $recursosTotal['otro3'] != false && $recursosTotal['otro3'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['otro3'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $horas = 'DE ' .$recursosTotal['inicio3'] .' A ' .$recursosTotal['fin3'];
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro3'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $horas = 'DE ' .$recursosTotal['inicio3'] .' A ' .$recursosTotal['fin3'];
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro3'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

    //TURNO RARO 4
    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'otro4');
    if ($recursosTotal['otro4'] != null && $recursosTotal['otro4'] != false && $recursosTotal['otro4'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['otro4'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $horas = 'DE ' .$recursosTotal['inicio4'] .' A ' .$recursosTotal['fin4'];
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro4'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $horas = 'DE ' .$recursosTotal['inicio4'] .' A ' .$recursosTotal['fin4'];
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro4'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

    //TURNO RARO 5
    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'otro5');
    if ($recursosTotal['otro5'] != null && $recursosTotal['otro5'] != false && $recursosTotal['otro5'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['otro5'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $horas = 'DE ' .$recursosTotal['inicio5'] .' A ' .$recursosTotal['fin5'];
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro5'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $horas = 'DE ' .$recursosTotal['inicio5'] .' A ' .$recursosTotal['fin5'];
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro5'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

    //TURNO RARO 6
    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_GET['fecha'], 'otro6');
    if ($recursosTotal['otro6'] != null && $recursosTotal['otro6'] != false && $recursosTotal['otro6'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['otro6'];
       if($izq>$der){
         $derNombre=0;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,1,"C");
         $horas = 'DE ' .$recursosTotal['inicio6'] .' A ' .$recursosTotal['fin6'];
         $der = $der +5;
         $pdf->SetY($der);
         $pdf->SetX(110);//Posición de la derecha de la pagina dónde queremos ecribir
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro6'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $derNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $derNombre++;
           }
         }
         $p2++;
         $der = $der +5;
         $cont1=$cont1+$derNombre+1;
       }else{
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,utf8_decode($descripcion),1,2,"C");
         $izqNombre=0;
         $horas = 'DE ' .$recursosTotal['inicio6'] .' A ' .$recursosTotal['fin6'];
         $izq = $izq +5;
         $pdf->SetY($izq);
         $pdf->SetTextColor(220,50,50);
         $pdf->Cell(90,5,$horas,1,1,"C");
         //Bucle para rellenar nombre de las actividades
         //Bucle para rellenar nombre de las actividades
         //si la actividad no tiene a nadie asignado, lo ponemos como sin asignar.
         //sacar la lista de empleados para esa actividad, dia y turno.
         if ($listaEmpleados == null || $listaEmpleados == '') {
           for ($gente=0; $gente < $recursosTotal['otro6'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "C");
             $izqNombre++;
           }
         }else {
           foreach ($listaEmpleados as $empleado) {
             //SI EL EMPLEADO ESTA VACIO, LO CAMBIAMOS POR 'SIN ASIGNAR'
             if ($empleado['empleado'] == '') {
               $empleado['empleado'] = 'SIN ASIGNAR';
             }
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "C");
             $izqNombre++;
           }
         }
        $izq = $izq +5;
        $cont=$cont+$izqNombre+1;
        $p++;
       }

       $izq=$add+(5*$cont)+($sepa*$p);
       $der=$add+(5*$cont1)+($sepa*$p2);

 ////Para añadir una nueva pagina cuando abscisse Y=250 con sus parametros para seguir
     if($der>=240||$izq>=240){
         $pdf->AddPage();

         $der=10;
         $izq=10;
         $p=1;
         $p2=1;
         $cont=0;
         $cont1=0;
         $add=0;
     }
     $i++;
    }

  }


  if (file_exists('./files/supervisor_'.$turno.'.pdf')) {
    unlink('./files/supervisor_'.$turno.'.pdf');
  }
  $pdf->Output('./files/supervisor_'.$turno.'.pdf','F');
  ?>
  <script type="text/javascript">
    window.location = '../supervisores/correoPablo.php?fecha=<?php echo $_GET['fecha']; ?>&turno=<?php echo $turno; ?>';
  </script>
