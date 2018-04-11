//funciones para esconder o mostrar los timelines dependiendo del boton que se pulse
function timeline1() {
  document.getElementById('timeline1').className='shown';
  document.getElementById('timeline2').className='hidden';
}
function timeline2() {
  document.getElementById('timeline2').className='shown';
  document.getElementById('timeline1').className='hidden';
}
