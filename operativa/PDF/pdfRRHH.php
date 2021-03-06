<?php
  /*Incluir los archivos necesarios para la db*/
  require '../../ddbb/sesiones.php';
  $sesion = new Sesiones();
  require_once '../../ddbb/users.php';
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
  $usuario=new User();

  //sacar el nombre del usuario que crea el pdf.
  $nombreUser=$usuario->nombreUsuario($_SESSION['usuario']);


  //incluir libreria de pdf's
  require 'fpdf/fpdf.php';
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','',10);

  ////////CABEZA FIJA DE LA PAGINA
  $fecha_actual=explode("-", $_POST['fecha']);
  $fecha_actual=$fecha_actual[2]."-".$fecha_actual[1]."-".$fecha_actual[0];

  //sacar la fecha y hora de creacion del pdf.
  $creacion=date('d-m-Y H:i');

  $pdf->SetY(5);
  $pdf->Image('../../assets/files/logo.png',15,8,20,13,"PNG");
  $pdf->SetX(40);
  $pdf->Cell(70,20,"GRUPOS DE TRABAJO",1,0,"C");
  $pdf->Cell(40,5,"Turno",1,0,"L");
  $pdf->Cell(40,5,"NOCHE",1,1,"L");
  // Empezar a 120 mm para el segundo cuadro
  $pdf->SetX(110);
  $pdf->Cell(40,5,"RRHH",1,0,"L");
  $pdf->Cell(40,5,utf8_decode($nombreUser['name']),1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Fecha confimación'),1,0,"L");
  $pdf->Cell(40,5,$creacion,1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Día efectivo'),1,0,"L");
  $pdf->Cell(40,5,"$fecha_actual",1,1,"L");
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
  $listaHoy = $servicio -> listaRRHH($_POST['fecha']);

  foreach ($listaHoy as $act) {
    //sacar el total de recursos para esa actividad, ese dia y ese turno.
    $recursosTotal = $recursos -> ModSupervisores($act['id'], $_POST['fecha'], 'tn');
    if ($recursosTotal == null || $recursosTotal == false) {
      $recursosTotal = $recursos -> RecursosSupervisores($act['id'], $_POST['fecha'], 'tn');
    }

    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'tn');

    //COMPROBAR   que esa actividad tiene recursos para ese turno.
    if ($recursosTotal != null && $recursosTotal != false && $recursosTotal['tn'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['tn'];
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
           for ($gente=0; $gente < $recursosTotal['tn'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
           for ($gente=0; $gente < $recursosTotal['tn'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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

  //TURNO MAÑANA
  $pdf->AddPage();
  $pdf->SetY(5);
  $pdf->Image('../../assets/files/logo.png',15,8,20,13,"PNG");
  $pdf->SetX(40);
  $pdf->Cell(70,20,"GRUPOS DE TRABAJO",1,0,"C");
  $pdf->Cell(40,5,"Turno",1,0,"L");
  $pdf->Cell(40,5,utf8_decode('MAÑANA'),1,1,"L");
  // Empezar a 120 mm para el segundo cuadro
  $pdf->SetX(110);
  $pdf->Cell(40,5,"RRHH",1,0,"L");
  $pdf->Cell(40,5,$nombreUser['name'],1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Fecha confimación'),1,0,"L");
  $pdf->Cell(40,5,$creacion,1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Día efectivo'),1,0,"L");
  $pdf->Cell(40,5,"$fecha_actual",1,1,"L");
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
  foreach ($listaHoy as $act) {
    //sacar el total de recursos para esa actividad, ese dia y ese turno.
    $recursosTotal = $recursos -> ModSupervisores($act['id'], $_POST['fecha'], 'tm');
    if ($recursosTotal == null || $recursosTotal == false) {
      $recursosTotal = $recursos -> RecursosSupervisores($act['id'], $_POST['fecha'], 'tm');
    }

    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'tm');

    //COMPROBAR   que esa actividad tiene recursos para ese turno.
    if ($recursosTotal != null && $recursosTotal != false && $recursosTotal['tm'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['tm'];
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
           for ($gente=0; $gente < $recursosTotal['tm'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
           for ($gente=0; $gente < $recursosTotal['tm'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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


  //TURNO TARDE
  $pdf->AddPage();
  $pdf->SetY(5);
  $pdf->Image('../../assets/files/logo.png',15,8,20,13,"PNG");
  $pdf->SetX(40);
  $pdf->Cell(70,20,"GRUPOS DE TRABAJO",1,0,"C");
  $pdf->Cell(40,5,"Turno",1,0,"L");
  $pdf->Cell(40,5,'TARDE',1,1,"L");
  // Empezar a 120 mm para el segundo cuadro
  $pdf->SetX(110);
  $pdf->Cell(40,5,"RRHH",1,0,"L");
  $pdf->Cell(40,5,$nombreUser['name'],1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Fecha confimación'),1,0,"L");
  $pdf->Cell(40,5,$creacion,1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Día efectivo'),1,0,"L");
  $pdf->Cell(40,5,"$fecha_actual",1,1,"L");
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
  foreach ($listaHoy as $act) {
    //sacar el total de recursos para esa actividad, ese dia y ese turno.
    $recursosTotal = $recursos -> ModSupervisores($act['id'], $_POST['fecha'], 'tt');
    if ($recursosTotal == null || $recursosTotal == false) {
      $recursosTotal = $recursos -> RecursosSupervisores($act['id'], $_POST['fecha'], 'tt');
    }

    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'tt');

    //COMPROBAR   que esa actividad tiene recursos para ese turno.
    if ($recursosTotal != null && $recursosTotal != false && $recursosTotal['tt'] > 0) {

      if ($i<=1) {
           $der=40;
      }
        //bucle para recoger array de nombre
        $descripcion = $act['descripcion'] ." - " .$recursosTotal['tt'];
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
           for ($gente=0; $gente < $recursosTotal['tt'] ; $gente++) {
             $der = $der +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($der);
             $pdf->SetX(110);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
           for ($gente=0; $gente < $recursosTotal['tt'] ; $gente++) {
             $izq = $izq +5;
             $pdf->SetTextColor(3, 3, 3);
             $pdf->SetY($izq);
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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

  //TURNOS ESPECIALES
  $pdf->AddPage();
  $pdf->SetY(5);
  $pdf->Image('../../assets/files/logo.png',15,8,20,13,"PNG");
  $pdf->SetX(40);
  $pdf->Cell(70,20,"GRUPOS DE TRABAJO",1,0,"C");
  $pdf->Cell(40,5,"Turno",1,0,"L");
  $pdf->Cell(40,5,'ESPECIALES',1,1,"L");
  // Empezar a 120 mm para el segundo cuadro
  $pdf->SetX(110);
  $pdf->Cell(40,5,"RRHH",1,0,"L");
  $pdf->Cell(40,5,$nombreUser['name'],1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Fecha confimación'),1,0,"L");
  $pdf->Cell(40,5,$creacion,1,1,"L");
  $pdf->SetX(110);
  $pdf->Cell(40,5,utf8_decode('Día efectivo'),1,0,"L");
  $pdf->Cell(40,5,"$fecha_actual",1,1,"L");
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
    $recursosTotal = $recursos ->ModSupervisoresRaros($act['id'], $_POST['fecha']);
    if ($recursosTotal == null || $recursosTotal == false) {
      $recursosTotal = $recursos -> RecursosId($act['id']);
    }

    //sacar los nombres de los empleados de esa actividad para ese dia.
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'tc');

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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro1');
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro2');
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro3');
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro4');
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro5');
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
    $listaEmpleados = $personal -> empleadosServicio($act['id'], $_POST['fecha'], 'otro6');
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
             $pdf->Cell(90, $t, 'SIN ASIGNAR', 1, 1, "L");
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
             $pdf->Cell(90, $t, utf8_decode($empleado['empleado']), 1, 1, "L");
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
  //$pdf->Output();
$pdf->Output('./files/RRHH.pdf','F');
 ?>
 <script type="text/javascript">
   window.location = '../rrhh/correoPablo.php?fecha=<?php echo $_POST['fecha']; ?>';
 </script>
