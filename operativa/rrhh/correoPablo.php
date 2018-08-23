<?php
require_once '../Classes/PHPMailer/class.phpmailer.php';
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';


  //Indicamos cual es nuestra dirección de correo y el nombre que
  //queremos que vea el usuario que lee nuestro correo
  $mail->From = "robot@tsiberia.es";
  $mail->FromName = "Transport service iberia.";

  //el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
  //una cuenta gratuita, por tanto lo pongo a 30
  $mail->Timeout=300;

  //Indicamos cual es la dirección de destino del correo
  $mail->AddAddress("pablo.moreno.g@ts-iberica.com");
  $mail->AddAddress("acosinga@ford.com");
  $mail->AddAddress("acosinga@tsiberia.es");
  $mail->AddAddress("rrhh@tsiberia.es");
  $mail->AddAddress("jpratval@tsiberia.es");
  $mail->AddAddress("jsanc267@tsiberia.es");
  $mail->AddAddress("ariquelm@tsiberia.es");
  $mail->AddAddress("emart309@tsiberia.es");
  $mail->AddAddress("mdevita1@tsiberia.es");
  $mail->AddAddress("jlluecag@tsiberia.es");
  $mail->AddAddress("jpratval@ford.com");
  $mail->AddAddress("jsanc267@ford.com");
  $mail->AddAddress("ariquelm@ford.com");
  $mail->AddAddress("emart309@ford.com");
  $mail->AddAddress("mdevita1@ford.com");
  $mail->AddAddress("jlluecag@ford.com");
  $mail->AddAddress("vicente.catala.g@ts-iberica.com");
  $mail->AddAddress("aasins@tsiberia.es");

  //transformamos la fecha
  $fecha=explode("-", $_GET['fecha']);
  $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];

  //Asignamos asunto y cuerpo del mensaje
  //El cuerpo del mensaje lo ponemos en formato html, haciendo
  //que se vea en negrita
  $mail->Subject = $fecha." Borrador de personal";
  $mail->Body = "Se adjunta planificacion/borrador del personal por parte de RRHH para el día: ".$fecha;

  //Definimos AltBody por si el destinatario del correo no admite email con formato html
  $mail->AltBody = "Se adjunta planificacion/borrador del personal por parte de RRHH para el día: ".$fecha;

  //  $mail->AddAttachment($url);
  //adjuntar el pdf y ponerle el nombre que quieras
  $url='http://acceso.tsiberia.es/operativa/PDF/files/RRHH.pdf';
  $fichero = file_get_contents($url);
  $mail->addStringAttachment($fichero, $fecha.'_HR.pdf');


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
 <!-- codigo redireccion a correoSupervisores.php-->
<!--<script type="text/javascript">
  window.location = 'correoSupervisores.php?fecha=<?php echo $_GET['fecha']; ?>';
</script>-->
<!-- redireccion a filtroRRHH.php-->
<script type="text/javascript">
  window.location = 'filtroRRHH.php';
</script>
