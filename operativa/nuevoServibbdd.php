<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen" type="text/css">
      body{
        color: white;
      }
    </style>
  </head>
  <body>
    <?php
    require_once 'servicio.php';
    require_once 'recursos.php';
    $servicio=new Servicio();
    $recursos= new Recursos();
    //comprbamos que realmente haya rellenado algunos campos
      if (isset($_POST['descripcion'])) {
        //juntamos todos los modelos en una variable
        $modelos="";
        $arrayModelos=$_POST['sel'];
        for ($i=0; $i < count($arrayModelos); $i++) {
          if ($i==0) {
            $modelos=$arrayModelos[$i];
          }else {
            $modelos= $modelos .", ".$arrayModelos[$i];
          }
        }

        //guardamos las rutas de los archivos.
        $ruta1=NULL;
        $ruta2=NULL;
        $ruta3=NULL;
        $ruta4=NULL;
        $ruta5=NULL;
        if ($_FILES['archivo1']['name']!="") {
          $ruta1="/operativa/files/".$_FILES['archivo1']['name'];
        }
        if ($_FILES['archivo2']['name']!="") {
          $ruta2="/operativa/files/".$_FILES['archivo2']['name'];
        }
        if ($_FILES['archivo3']['name']!="") {
          $ruta3="/operativa/files/".$_FILES['archivo3']['name'];
        }
        if ($_FILES['archivo4']['name']!="") {
          $ruta4="/operativa/files/".$_FILES['archivo4']['name'];
        }
        if ($_FILES['archivo5']['name']!="") {
          $ruta5="/operativa/files/".$_FILES['archivo5']['name'];
        }
        //si los ha rellenado, llamamos a la función de insertar el servicio y le pasamos los datos.
        $nuevoServicio=$servicio->nuevoServicio($_POST['descripcion'], $modelos, $_POST['recursos'], $_POST['finicio'], $_POST['cliente'], $_POST['responsable'], $_POST['telefono'], $_POST['correo'], $_POST['csup'],
         $_POST['crrhh'], $_POST['caf'], $_POST['cdo'], $_POST['relacion'], $ruta1, $ruta2, $ruta3, $ruta4, $ruta5);
        //comprobamos que se haya registrado.
        if ($nuevoServicio==null) {
          //si no se ha registrado le saca un mensaje avisandole
          ?>
            <script type="text/javascript">
              alert("Error al registrar la actividad. Servicio");
            </script>
          <?php
        }else {
          //actualizamos la actividad con la que lo vamos a relacionar para hacer la relacion tmbn
          if ($_POST['relacion']!=0) {
            $ultimoid=$servicio->ultimoServicio();
            foreach ($ultimoid as $lastid) {
              $relacionServicio=$servicio->RelacionActividad($_POST['relacion'], $lastid['id']);
            }
          }
          //si se ha registrado el servicio cogemos su id para regstrar los recursos
          $ultimo= $servicio-> ultimoServicio();
          foreach ($ultimo as $servicio) {
            $nuevorecurso=$recursos->nuevoRecurso($servicio['id'], $_POST['recursos'], $_POST['tm'], $_POST['tt'], $_POST['tn'], $_POST['tc'], $_POST['o1'], $_POST['i1'], $_POST['f1'], $_POST['o2'], $_POST['i2'],
             $_POST['f2'], $_POST['o3'], $_POST['i3'], $_POST['f3'], $_POST['o4'], $_POST['i4'], $_POST['f4'], $_POST['o5'], $_POST['i5'], $_POST['f5'],
              $_POST['o6'], $_POST['i6'], $_POST['f6']);
          }
           if ($nuevorecurso==null) {
             //si no se ha registrado le saca un mensaje avisandole
             ?>
               <script type="text/javascript">
                 alert("Error al registrar la actividad. Recursos");
               </script>
             <?php
           }else {

             if ($_FILES['archivo1']['name']!="" || $_FILES['archivo2']['name']!="" || $_FILES['archivo3']['name']!="") {
               //por ultimo subimos los archivos al servidor
               for ($i=1; $i < 6; $i++) {
                   if ($_FILES['archivo'.$i]['name']!="") {
                     $nombre_archivo = $_FILES['archivo'.$i]['name'];
                     $size_archivo =  $_FILES['archivo'.$i]['size'];

                     //ruta en el servidor donde guardar el archivo
                     $ruta="files/".$nombre_archivo;

                     if(is_uploaded_file($_FILES['archivo'.$i]['tmp_name'])) {
                       if(move_uploaded_file($_FILES['archivo'.$i]['tmp_name'], $ruta)) {
                         //si se ha registrado le saca un mensaje avisandole
                         ?>
                           <script type="text/javascript">
                             alert("Nueva actividad registrada.");
                             window.location='nuevoServicio.php';
                           </script>
                         <?php
                        }
                     }else {
                       ?>
                         <script type="text/javascript">
                           alert("Error al registrar la actividad. Archivos");
                         </script>
                       <?php
                     }
                   }
               }
             }else {
               ?>
                 <script type="text/javascript">
                   alert("Nuevo servicio registrado.");
                   window.location='nuevoServicio.php';
                 </script>
               <?php
             }

           }
        }
      }
     ?>

  </body>
</html>
