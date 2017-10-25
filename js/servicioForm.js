//inicializamos un contador
var i=0;
  //funcion que añadira los input del nuevo horario
  function nuevo() {
    if (i>4) {
      //si hay mas de 4 no le dejamos entrar, ya que no queremos mas
      return false;
    }else if (i==4) {
      //si hay 4 aparte de sacar un input eliminamos el boton
      //subimos el contador
      i++;
      //creamos el elemento que queremos añadir
      var nuevo= document.createElement("p");
      //cogemos dos elementos de referencia para saber donde colocar el nuestro
      var contenedor= document.getElementById('contenedor');
      var seleccionado= document.getElementById('enviar');
      //colocamos el nuevo elemento dentro del contenedor de referencia y delante del otro elemento
      contenedor.insertBefore(nuevo, seleccionado);
      //le ponemos el html al elemento creado
      nuevo.innerHTML="<label><i class='fa fa-qestion-circle'></i>Otro turno</label><input class='threeinputs' type='time' name='f"+i+"'/><input class='threeinputs2' type='time' name='i"+i+"'/><input class='threeinputs1' type='number' min='0' name='o"+i+"'/>";
      //cogemos el boton que queremos borrar
      var borrar= document.getElementById('nuevoServicio');
      //borramos el boton que habiamos cogido
      borrar.parentNode.removeChild(borrar);
    }else {
      //si hay menos de 4 solo sacamos el nuevo elemento.
      //subimos el contador
      i++;
      //creamos el elemento que queremos añadir.
      var nuevo= document.createElement("p");
      //cogemos dos elementos de referencia para saber donde colocar el nuestro
      var contenedor= document.getElementById('contenedor');
      var seleccionado= document.getElementById('enviar');
      //colocamos el nuevo elemento dentro del contenedor de referencia y delante del otro elemento
      contenedor.insertBefore(nuevo, seleccionado);
      //le ponemos el html al elemento seleccionado
      nuevo.innerHTML="<label><i class='fa fa-qestion-circle'></i>Otro turno</label><input class='threeinputs' type='time' name='f"+i+"'/><input class='threeinputs2' type='time' name='i"+i+"'/><input class='threeinputs1' type='number' min='0' name='o"+i+"'/>";
    }
  }
