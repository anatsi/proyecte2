<?php
  //transformamos la fecha
  $fecha=explode("-", $_GET['fecha']);
  $fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
  //enviar correo para vaisar de que el personal ya esta disponible.
    // Enviar el email
    $mail = "robot@tsiberia.es";

    $header = 'From: ' . $mail . " \r\n";
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
    </head>' .
    '<body>
      <h4>
        <b>RRHH ha actualizado la planificación del personal para el dia '.$fecha.'</b>
        <br>
        <a href="acceso.tsiberia.es">acceso.tsiberia.es</a>
      </h4><br />' .
      'Por favor, no responda a este correo lo envia un robot automáticamente.'.
      '<br />Enviado el ' . date('d/m/Y', time()) .
    '</body></html>';

    $para = 'aasins@tsiberia.es, jpratval@tsiberia.es, jsanc267@tsiberia.es, jlluecag@tsiberia.es, mdevita1@tsiberia.es, ariquelme@tsiberia.es, emart309@tsiberia.es';
    $asunto = 'Personal para el ' .$fecha;

    mail($para, $asunto, $mensaje, $header);


 ?>
<script type="text/javascript">
  window.location = 'filtroRRHH.php';
</script>
