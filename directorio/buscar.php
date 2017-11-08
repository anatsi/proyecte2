<?php
      
      $buscar = $_POST['b'];
       
      if(!empty($buscar)) {
            buscar($buscar);
      }else{

              require_once 'mysql-login.php';
              $mysqli = new mysqli($hostname, $username, $password, $database);
              $acentos = $mysqli->query("SET NAMES 'utf8'");
              if ($mysqli -> connect_errno) {
                die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() . ") " . $mysqli -> mysqli_connect_error());
              }
              else{
                
                $consulta = "SELECT name, surname, email, tlf, movil FROM users";
                $resultado = $mysqli -> query($consulta);
                
                echo "
                  <table id='tablamod'>
                  <thead id='theadmod'>
                    <tr id='trmod'>
                      <th scope='col' id='thmod'>NOMBRE</th>
                      <th scope='col' id='thmod'>CORREO</th>
                      <th scope='col' id='thmod'>MOVIL</th>
                      <th scope='col' id='thmod'>TLF FIJO</th>
                    </tr>
                  </thead><tbody id='tbodymod'>

                  "; while($fila = $resultado -> fetch_row()) {
                    $nombre = $fila[0];
                    $surname = $fila[1];
                    $nombre = $nombre.' '.$surname;
                    $email = $fila[2];
                    $fijo = $fila[3];
                    $movil = $fila[4];
                     echo "
                        <tr id='trmod'>
                          <td data-label='NOMBRE' id='tdmod'>".$nombre."</td>
                          <td data-label='CORREO' id='tdmod'>".$email."</td>
                          <td data-label='MOVIL' id='tdmod'><a href='tel:".$movil."'>".$movil."</a></td>
                          <td data-label='TLF FIJO' id='tdmod'><a href='tel:".$fijo."'>".$fijo."</a></td>
                        </tr>
                   
                  ";} echo "</tbody></table>";
                  }
      }
       
      function buscar($b) {
            require_once 'mysql-login.php';
            $mysqli = new mysqli($hostname, $username, $password, $database);
            $acentos = $mysqli->query("SET NAMES 'utf8'");

            $consulta = "SELECT name, surname, email, tlf, movil FROM users WHERE name LIKE '%".$b."%'";
            $resultado = $mysqli -> query($consulta);
             
            $row_cnt = $resultado -> num_rows;

            //printf("Result set has %d rows.\n", $row_cnt);
             
            if($row_cnt == 0){
                  echo "No se han encontrado resultados para '<b>".$b."</b>'.";
            }else{
                  echo "
                  <table id='tablamod'>
                  <thead id='theadmod'>
                    <tr id='trmod'>
                      <th scope='col' id='thmod'>NOMBRE</th>
                      <th scope='col' id='thmod'>CORREO</th>
                      <th scope='col' id='thmod'>MOVIL</th>
                      <th scope='col' id='thmod'>TLF FIJO</th>
                    </tr>
                  </thead><tbody id='tbodymod'>

                  "; while($fila = $resultado -> fetch_row()) {
                    $nombre = $fila[0];
                    $surname = $fila[1];
                    $nombre = $nombre.' '.$surname;
                    $email = $fila[2];
                    $fijo = $fila[3];
                    $movil = $fila[4];
                     echo "
                        <tr id='trmod'>
                          <td data-label='NOMBRE' id='tdmod'>".$nombre."</td>
                          <td data-label='CORREO' id='tdmod'>".$email."</td>
                          <td data-label='MOVIL' id='tdmod'><a href='tel:".$movil."'>".$movil."</a></td>
                          <td data-label='TLF FIJO' id='tdmod'><a href='tel:".$fijo."'>".$fijo."</a></td>
                        </tr>
                   
                  ";} echo "</tbody></table></div></body></html>";
                  
            }
      }     
?>