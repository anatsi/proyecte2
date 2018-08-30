<?php

//SCRIPT PARA EJECUTAR MANUALEMENTE LOS AVISOS SOBRE LAS FECHAS DE  CADUCIDADES DE LOS EMPLEADOS

require_once './ddbb/empleados.php';
require_once './ddbb/fechas.php';

$empleado= new Empleados();
$fecha= new Fechas();




       $contador=0;
      //Llamar la fonction para listar las fechas
       $listaFecha=$fecha->listaFechas();

     //Foreach para fecha de caducidad  dni
      foreach($listaFecha as $fechas){
        //Poner las fechas en format numebr "aammdd"
        $hoy=date("Y-m-d");
        $datetime1 = new DateTime($hoy);
        $datetime2 = new DateTime($fechas['pase_ford']);
        if ($datetime1 < $datetime2) {
          $interval = $datetime1->diff($datetime2);
          $alerta=$interval->format('%a');
          //Poner una condición de si la diferencia es 15 o 10 o 1 enviar correo  y además el empleado está de  alta
           if($alerta<=15){
             $empleados= $empleado->EmpleadoId($fechas['empleado']);
           //Solo los empleados en activo pueden apacer
             if($empleados['alta']==0){
               $a[]= $empleados['nombre'].' '.$empleados['apellidos'].' - '.$fechas['pase_ford'];
               //var_dump($a);
             }

          }
        }
      }


      //enviar correo para avisar de la caducidad si solo existe nombre
       if(empty($a)==false){


          $mail = "robot@tsiberia.es";

          $header = 'From: ' .$mail . " \r\n";
          $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
          $header .= "Mime-Version: 1.0 \r\n";
          //$header .= "Content-Type: text/plain";
          $header .= "Content-Type: text/html; charset=utf-8";

          $mensaje = '<html>' . '<head><title>Email</title>
          <style type="text/css">
          h2 {
              color: black;
              font-family: Impact;
            }
          </style>
          </head>'.
          '<body>
            <h4> <b>La fecha de validez del Pase Ford de los siguientes empleados caduca pronto.  </b></h4><br>';

            for($i=0;$i<count($a);$i++){
              $mensaje.=$a[$i] . '<br>';
            }

            $mensaje.= ' <br>'.

            'Por favor, no responda a este correo lo envia un robot automáticamente.'.
            '<br />Enviado el ' . date('d/m/Y', time()) .
          '</body></html>';

          $para = 'it@tsiberia.es, rrhh@tsiberia.es';
          $asunto = 'Pase Ford a punto de vencer';

          mail($para,$asunto,$mensaje,$header);
        }
    //Inicializar el array para no accumular los valores
        $a= array();
        //Foreach para fecha de caducidad  dni
      foreach($listaFecha as $fechas){
        //Poner las fechas en format numebr "aammdd"
        $hoy=date("Y-m-d");
        $datetime1 = new DateTime($hoy);
        $datetime2 = new DateTime($fechas['dni']);
        if ($datetime1 < $datetime2) {
          $interval = $datetime1->diff($datetime2);
          $alerta=$interval->format('%a');
          //Poner una condición de si la diferencia es 15 o 10 o 1 enviar correo  y además el empleado está de  alta
           if($alerta<=15){
             $empleados= $empleado->EmpleadoId($fechas['empleado']);
           //Solo los empleados en activo pueden apacer
             if($empleados['alta']==0){
               $a[]= $empleados['nombre'].' '.$empleados['apellidos'].' - '.$fechas['dni'];
               //var_dump($a);
             }

          }
        }
      }


      //enviar correo para avisar de la caducidad si solo existe nombre
       if(empty($a)==false){


          $mail = "robot@tsiberia.es";

          $header = 'From: ' .$mail . " \r\n";
          $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
          $header .= "Mime-Version: 1.0 \r\n";
          //$header .= "Content-Type: text/plain";
          $header .= "Content-Type: text/html; charset=utf-8";

          $mensaje = '<html>' . '<head><title>Email</title>
          <style type="text/css">
          h2 {
              color: black;
              font-family: Impact;
            }
          </style>
          </head>'.
          '<body>
            <h4> <b>La fecha de validez del dni de los siguientes empleados caduca pronto.  </b></h4><br>';

            for($i=0;$i<count($a);$i++){
              $mensaje.=$a[$i] . '<br>';
             }

            $mensaje.= ' <br>'.

            'Por favor, no responda a este correo lo envia un robot automáticamente.'.
            '<br />Enviado el ' . date('d/m/Y', time()) .
          '</body></html>';

          $para = 'it@tsiberia.es, rrhh@tsiberia.es';
          $asunto = 'Dni a punto de vencer';

          mail($para,$asunto,$mensaje,$header);
        }
    //Inicializar el array para no accumular los valores
        $a= array();
        //Foreach para fecha de caducidad  Carnet de conducir
      foreach($listaFecha as $fechas){
        //Poner las fechas en format numebr "aammdd"
        $hoy=date("Y-m-d");
        $datetime1 = new DateTime($hoy);
        $datetime2 = new DateTime($fechas['carnet_conducir']);
        if ($datetime1 < $datetime2) {
          $interval = $datetime1->diff($datetime2);
          $alerta=$interval->format('%a');
          //Poner una condición de si la diferencia es 15 o 10 o 1 enviar correo  y además el empleado está de  alta
           if($alerta<=15){
             $empleados= $empleado->EmpleadoId($fechas['empleado']);
           //Solo los empleados en activo pueden apacer
             if($empleados['alta']==0){
               $a[]= $empleados['nombre'].' '.$empleados['apellidos'].' - '.$fechas['carnet_conducir'];
               //var_dump($a);
             }

          }
        }
      }


      //enviar correo para avisar de la caducidad si solo existe nombre
       if(empty($a)==false){


          $mail = "robot@tsiberia.es";

          $header = 'From: ' .$mail . " \r\n";
          $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
          $header .= "Mime-Version: 1.0 \r\n";
          //$header .= "Content-Type: text/plain";
          $header .= "Content-Type: text/html; charset=utf-8";

          $mensaje = '<html>' . '<head><title>Email</title>
          <style type="text/css">
          h2 {
              color: black;
              font-family: Impact;
            }
          </style>
          </head>'.
          '<body>
            <h4> <b>La fecha de validez del carnet de conducir los siguientes empleados caduca pronto.  </b></h4><br>';

            for($i=0;$i<count($a);$i++){
                $mensaje.=$a[$i] . '<br>';
             }

            $mensaje.= ' <br>'.

            'Por favor, no responda a este correo lo envia un robot automáticamente.'.
            '<br />Enviado el ' . date('d/m/Y', time()) .
          '</body></html>';

          $para = 'it@tsiberia.es, rrhh@tsiberia.es';
          $asunto = 'Carnet de conducir a punto de vencer';

          mail($para,$asunto,$mensaje,$header);
        }
       //Inicializar el array para no accumular los valores
        $a= array();
        //Foreach para caducidad de carnet de conducir Ford
      foreach($listaFecha as $fechas){
        //Poner las fechas en format numebr "aammdd"
        $hoy=date("Y-m-d");
        $datetime1 = new DateTime($hoy);
        $datetime2 = new DateTime($fechas['conducir_ford']);
        if ($datetime1 < $datetime2) {
          $interval = $datetime1->diff($datetime2);
          $alerta=$interval->format('%a');
          //Poner una condición de si la diferencia es 15 o 10 o 1 enviar correo  y además el empleado está de  alta
           if($alerta<=15){
             $empleados= $empleado->EmpleadoId($fechas['empleado']);
           //Solo los empleados en activo pueden apacer
             if($empleados['alta']==0){
               $a[]= $empleados['nombre'].' '.$empleados['apellidos'].' - '.$fechas['conducir_ford'];
               //var_dump($a);
             }

          }
        }
      }

      //enviar correo para avisar de la caducidad si solo existe nombre
       if(empty($a)==false){


          $mail = "robot@tsiberia.es";

          $header = 'From: ' .$mail . " \r\n";
          $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
          $header .= "Mime-Version: 1.0 \r\n";
          //$header .= "Content-Type: text/plain";
          $header .= "Content-Type: text/html; charset=utf-8";

          $mensaje = '<html>' . '<head><title>Email</title>
          <style type="text/css">
          h2 {
              color: black;
              font-family: Impact;
            }
          </style>
          </head>'.
          '<body>
            <h4> <b>La fecha de validez del carnet de conducir Ford de los siguientes empleados caduca pronto.  </b></h4><br>';

            for($i=0;$i<count($a);$i++){
                $mensaje.=$a[$i] . '<br>';
             }

            $mensaje.= ' <br>'.

            'Por favor, no responda a este correo lo envia un robot automáticamente.'.
            '<br />Enviado el ' . date('d/m/Y', time()) .
          '</body></html>';

          $para = 'it@tsiberia.es, rrhh@tsiberia.es';
          $asunto = 'Carnet de conducir Ford a punto de vencer';

          mail($para,$asunto,$mensaje,$header);
        }
     //Inicializar el array para no accumular los valores
        $a= array();
        //Foreach para fecha de caducidad para la revision medica
      foreach($listaFecha as $fechas){
        //Poner las fechas en format numebr "aammdd"
        $hoy=date("Y-m-d");
        $datetime1 = new DateTime($hoy);
        $datetime2 = new DateTime($fechas['medico']);
        if ($datetime1 < $datetime2) {
          $interval = $datetime1->diff($datetime2);
          $alerta=$interval->format('%a');
          //Poner una condición de si la diferencia es 15 o 10 o 1 enviar correo  y además el empleado está de  alta
           if($alerta<=15){
             $empleados= $empleado->EmpleadoId($fechas['empleado']);
           //Solo los empleados en activo pueden apacer
             if($empleados['alta']==0){
               $a[]= $empleados['nombre'].' '.$empleados['apellidos'].' - '.$fechas['medico'];
               //var_dump($a);
             }

          }
        }
      }

      //enviar correo para avisar de la caducidad si solo existe nombre
       if(empty($a)==false){



          $mail = "robot@tsiberia.es";

          $header = 'From: ' .$mail . " \r\n";
          $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
          $header .= "Mime-Version: 1.0 \r\n";
          //$header .= "Content-Type: text/plain";
          $header .= "Content-Type: text/html; charset=utf-8";

          $mensaje = '<html>' . '<head><title>Email</title>
          <style type="text/css">
          h2 {
              color: black;
              font-family: Impact;
            }
          </style>
          </head>'.
          '<body>
            <h4> <b>La fecha de la revision medica de los siguientes empleados es dentro de pocos dias. </b></h4><br>';

            for($i=0;$i<count($a);$i++){
                $mensaje.=$a[$i] . '<br>';
             }

            $mensaje.= '<br>'.

            'Por favor, no responda a este correo lo envia un robot automáticamente.'.
            '<br />Enviado el ' . date('d/m/Y', time()) .
          '</body></html>';

          $para = 'it@tsiberia.es, rrhh@tsiberia.es';
          $asunto = 'Revision medica  a punto de vencer';

          mail($para,$asunto,$mensaje,$header);
        }



?>
