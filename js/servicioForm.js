//inicializamos un contador
var i=0;
var z=1;
  //funcion que añadira los input del nuevo horario
  function nuevo() {
    if (i>5) {
      //si hay mas de 4 no le dejamos entrar, ya que no queremos mas
      return false;
    }else if (i==5) {
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
      nuevo.innerHTML="<label><i class='fa fa-qestion-circle'></i>Otro turno</label><input class='threeinputs' type='time' name='i"+i+"'/><input class='threeinputs2' type='time' name='f"+i+"'/><input class='threeinputs1' type='number' value='0' min='0' id='in"+i+"' onclick='suma();' onkeyup='suma();' name='o"+i+"'/>";
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
      nuevo.innerHTML="<label><i class='fa fa-qestion-circle'></i>Otro turno</label><input class='threeinputs' type='time' name='i"+i+"'/><input class='threeinputs2' type='time' name='f"+i+"'/><input class='threeinputs1' type='number' value='0' onclick='suma();' onkeyup='suma();' id='in"+i+"' min='0' name='o"+i+"'/>";
    }
  }
//funcion para ir sumando los recursos
  function suma() {
    //creamor la variable que servira para almacenar la suma
    var suma;
    //asignamos el valor de los inputs, que por defecto es 0, a variales
    var contenido1 = parseInt(document.getElementById('tm').value);
    var contenido2= parseInt(document.getElementById('tt').value);
    var contenido3= parseInt(document.getElementById('tn').value);
    var contenido4= parseInt(document.getElementById('tc').value);
    //si los inputs todavia no existen, ponemos la variable a 0
    if (document.getElementById('in1')) {
      var contenido5=parseInt(document.getElementById('in1').value);
    }else {
      var contenido5=0;
    }
    if (document.getElementById('in2')) {
      var contenido6=parseInt(document.getElementById('in2').value);
    }else {
      var contenido6=0;
    }
    if (document.getElementById('in3')) {
      var contenido7= parseInt(document.getElementById('in3').value);
    }else {
      var contenido7=0;
    }
    if (document.getElementById('in4')) {
      var contenido8= parseInt(document.getElementById('in4').value);
    }else {
      var contenido8=0;
    }
    if (document.getElementById('in5')) {
      var contenido9= parseInt(document.getElementById('in5').value);
    }else {
      var contenido9=0;
    }
    if (document.getElementById('in6')) {
      var contenido10= parseInt(document.getElementById('in6').value);
    }else {
      var contenido10=0;
    }
    //una vez creadas todas las variables, las sumamos
    suma = contenido1 + contenido2 + contenido3 + contenido4 + contenido5 + contenido6 + contenido7 + contenido8 + contenido9;
    //apuntamos el resultado de la suma en el input del total
    document.getElementById('total').value= suma;
}



//funcion para sacar las personas aproximadas necesarias
function calculoPersonas() {
  var segundos= parseInt(document.getElementById('segundos').value);
  var coches= parseInt(document.getElementById('coches').value);

  var resultado= segundos*coches;
  resultado= resultado/27900;

  document.getElementById('calculo').value=Math.ceil(resultado);
}

function archivos() {
  if (z>4) {
    return false;
  }else if (z==4) {
    //si hay 4 aparte de sacar un input eliminamos el boton
    //subimos el contador
    z++;
    //creamos el elemento que queremos añadir
    var nuevo= document.createElement("p");
    //cogemos dos elcementos de referencia para saber donde colocar el nuestro
    var contenedor= document.getElementById('archivos');
    //colocamos el nuevo elemento dentro del contenedor de referencia
    contenedor.appendChild(nuevo);
    //le ponemos el html al elemento creado
    nuevo.innerHTML="<input type='file' name='archivo"+z+"' value='archivo"+z+"' id='archivo"+z+"'>";
    //cogemos el boton que queremos borrar
    var borrar= document.getElementById('nuevoarchivo');
    //borramos el boton que habiamos cogido
    borrar.parentNode.removeChild(borrar);
  }else {
    //subimos el contador
    z++;
    //creamos el elemento que queremos añadir
    var nuevo= document.createElement("p");
    //cogemos dos elcementos de referencia para saber donde colocar el nuestro
    var contenedor= document.getElementById('archivos');
    //colocamos el nuevo elemento dentro del contenedor de referencia
    contenedor.appendChild(nuevo);
    //le ponemos el html al elemento creado
    nuevo.innerHTML="<input type='file' name='archivo"+z+"' value='archivo"+z+"' id='archivo"+z+"'>";
    //cogemos el boton que queremos borrar
    var borrar= document.getElementById('nuevoarchivo');
  }
}
