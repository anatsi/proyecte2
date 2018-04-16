<?php

//incluimos los archivos necesarios
require_once '../ddbb/sesiones.php';
require_once '../ddbb/users.php';
$sesion= new Sesiones();
$usuario= new User();

$buscar = $_POST['b'];

if(!empty($buscar)) {
      buscar($buscar);
}else{


  require_once '../ddbb/db2.php';
  $db= new db2();

    $consulta = "SELECT name, surname, email, tlf, movil, extMovil FROM users";
    $resultado = $db -> realizarConsulta($consulta);
  echo "
    <table id='tablamod'>
    <thead id='theadmod'>
      <tr id='trmod'>
        <th scope='col' id='thmod'>NOMBRE</th>
        <th scope='col' id='thmod'>CORREO</th>
        <th scope='col' id='thmod'>MOVIL</th>
        <th scope='col' id='thmod'>EXT. MOVIL</th>
        <th scope='col' id='thmod'>TLF. FIJO</th>
      </tr>
    </thead><tbody id='tbodymod'>

    "; while($fila = $resultado -> fetch_row()) {
      $nombre = $fila[0];
      $surname = $fila[1];
      $nombre = $nombre.' '.$surname;
      $email = $fila[2];
      $fijo = $fila[3];
      $movil = $fila[4];
      $ext_movil = $fila[5];
       echo "
          <tr id='trmod'>
            <td data-label='NOMBRE' id='tdmod'>".$nombre."</td>
            <td data-label='CORREO' id='tdmod'>".$email."</td>
            <td data-label='MOVIL' id='tdmod'><a href='tel:".$movil."'>".$movil."</a></td>
            <td data-label='MOVIL' id='tdmod'><a href='tel:".$ext_movil."'>".$ext_movil."</a></td>
            <td data-label='TLF FIJO' id='tdmod'><a href='tel:".$fijo."'>".$fijo."</a></td>
          </tr>

    ";} echo "</tbody></table></div></body></html>";
}

function buscar($b) {
  require_once '../ddbb/sesiones.php';
  require_once '../ddbb/users.php';
  $sesion= new Sesiones();
  $usuario= new User();

  require_once '../ddbb/db2.php';
  $db= new db2();

  $consulta = "SELECT name, surname, email, tlf, movil, extMovil FROM users WHERE name LIKE '%".$b."%' OR surname LIKE '%".$b."%'";
  $resultado = $db -> realizarConsulta($consulta);
    echo "
      <table id='tablamod'>
      <thead id='theadmod'>
        <tr id='trmod'>
          <th scope='col' id='thmod'>NOMBRE</th>
          <th scope='col' id='thmod'>CORREO</th>
          <th scope='col' id='thmod'>MOVIL</th>
          <th scope='col' id='thmod'>EXT. MOVIL</th>
          <th scope='col' id='thmod'>TLF. FIJO</th>
        </tr>
      </thead><tbody id='tbodymod'>

      ";  while($fila = $resultado -> fetch_row()) {
        $nombre = $fila[0];
        $surname = $fila[1];
        $nombre = $nombre.' '.$surname;
        $email = $fila[2];
        $fijo = $fila[3];
        $movil = $fila[4];
        $ext_movil = $fila[5];
         echo "
            <tr id='trmod'>
              <td data-label='NOMBRE' id='tdmod'>".$nombre."</td>
              <td data-label='CORREO' id='tdmod'>".$email."</td>
              <td data-label='MOVIL' id='tdmod'><a href='tel:".$movil."'>".$movil."</a></td>
              <td data-label='MOVIL' id='tdmod'><a href='tel:".$ext_movil."'>".$ext_movil."</a></td>
              <td data-label='TLF FIJO' id='tdmod'><a href='tel:".$fijo."'>".$fijo."</a></td>
            </tr>

      ";} echo "</tbody></table></div></body></html>";
}
 ?>
