function cliente() {
  document.getElementById('formCliente').className='shown';
  document.getElementById('formResponsable').className='hidden';
}

function responsable() {
  document.getElementById('formCliente').className='hidden';
  document.getElementById('formResponsable').className='shown';
}
