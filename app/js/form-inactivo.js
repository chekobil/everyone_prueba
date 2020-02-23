$(document).ready(function() {


  $('#form-registro-inactivo').submit(function(e) {
  	// no hagas la accion por defecto, es decir, no envies el formulario
    e.preventDefault();
    var msg = 'este formulario no hace nada'
	$('#form-alert-info').text(msg).fadeIn();
    console.log(msg)

  })
});