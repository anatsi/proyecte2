<?php
require_once '../Classes/PHPMailer/class.phpmailer.php';
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';


  //Indicamos cual es nuestra dirección de correo y el nombre que
  //queremos que vea el usuario que lee nuestro correo
  $mail->From = "robot@tsiberia.es";
  $mail->FromName = "Transport service iberia.";


  $mail->Timeout=300;

  //Indicamos cual es la dirección de destino del correo
  $mail->AddAddress("pablo.moreno.g@ts-iberica.com");
  $mail->AddAddress("acosinga@ford.com");
  $mail->AddAddress("acosinga@tsiberia.es");
  $mail->AddAddress("rrhh@tsiberia.es");
  $mail->AddAddress("aasins@tsiberia.es");
  $mail->AddAddress("vicente.catala.g@ts-iberica.com");

  //$mail->AddAddress('aasins@tsiberia.es');

  //transformamos la fecha
  $fecha=explode("-", $_GET['fecha']);
  $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
  if ($_GET['turno'] == 'tm') {
    $turno = 'Mañana';
  }elseif ($_GET['turno'] == 'tt') {
    $turno = 'Tarde';
  }elseif ($_GET['turno'] == 'tn') {
    $turno = 'Noche';
  }

  //Asignamos asunto y cuerpo del mensaje
  //El cuerpo del mensaje lo ponemos en formato html, haciendo
  //que se vea en negrita
  $asunto = "Personal final dia: ".$fecha.", turno ".$turno;
  //FUNCION PARA QUE EN EL ASUNTO SALGAN LAS Ñ Y LOS ACENTOS
  $mail->Subject =mb_encode_mimeheader($asunto,"UTF-8");
  //definimos el body del correo
  $mail->Body = "Se adjunta planificacion del personal por parte del jefe de turno. <br> Turno: ".$turno." <br>Día: ".$fecha;

  //Definimos AltBody por si el destinatario del correo no admite email con formato html
  $mail->AltBody = "Se adjunta planificacion del personal por parte del supervisor del turno: ".$turno." para el día: ".$fecha;

  //  $mail->AddAttachment($url);
  //adjuntar el pdf y ponerle el nombre que quieras
  $url='http://acceso.tsiberia.es/operativa/PDF/files/supervisor_'.$_GET['turno'].'.pdf';
  $fichero = file_get_contents($url);
  $mail->addStringAttachment($fichero, $fecha.'_'.$_GET['turno'].'.pdf');


  //se envia el mensaje, si no ha habido problemas
  //la variable $exito tendra el valor true
  $exito = $mail->Send();

  //Si el mensaje no ha podido ser enviado se realizaran 4 intentos mas como mucho
  //para intentar enviar el mensaje, cada intento se hara 5 segundos despues
  //del anterior, para ello se usa la funcion sleep
  $intentos=1;
  while ((!$exito) && ($intentos < 5)) {
	     sleep(5);
     	//echo $mail->ErrorInfo;
     	$exito = $mail->Send();
     	$intentos=$intentos+1;

   }


   if(!$exito){
	    echo "Problemas enviando correo electrónico a ".$valor;
	    echo "<br/>".$mail->ErrorInfo;
   }else{
	    echo "Mensaje enviado correctamente";
   }


 ?>
 <script type="text/javascript">
   window.location = '../PDF/files/supervisor_<?php echo $_GET['turno']; ?>.pdf';
 </script>
