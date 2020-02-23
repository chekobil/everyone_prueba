$(document).ready(function() {

	// fecha ahora mismo
	var fecha_now = new Date();
    $('#form-fecha').val(fecha_now.getTime());


  $('#form-registro').submit(function(e) {
  	// no hagas la accion por defecto, es decir, no envies el formulario
    e.preventDefault();
    // data
    data = {}
    // reset error para eliminar el valor anterior
    result = {}; // Object
    result.error = ""
    // define variables
    data.nombre = $('#form-nombre').val()
    data.email = $('#form-email').val()
    data.fecha = $('#form-fecha').val()
    // preguntas
    data.comunidades = $('#form-comunidades').val()
    data.alta = $('input[name=form-alta-radio]:checked', '#form-registro').val()
    data.gustaria = $('input[name=form-gustaria-radio]:checked', '#form-registro').val()
    data.publicidad = $('input[name=form-publicidad-radio]:checked', '#form-registro').val()
    data.acepto = $('input[name=form-acepto-radio]:checked', '#form-registro').val()
    // guarda valores de preguntas en un objeto, solo si tiene valor
    // asi puedes saber cuantas han sido respondidas
    preguntas = []
    if(typeof data.alta !== 'undefined'){
    	preguntas.push(data.alta)
	}
    if(typeof data.gustaria !== 'undefined'){
    	preguntas.push(data.gustaria)
	}
    if(typeof data.publicidad !== 'undefined'){
    	preguntas.push(data.publicidad)
	}

    // valida los valores
    // NOMBRE
    if (data.nombre.length < 1) {
    	console.log('nombre no sirve')
      	result.error = "Por favor, introduce tu nombre para continuar";
      	$('#form-nombre').addClass('input-error')
    }else{
    	$('#form-nombre').removeClass('input-error')
    }
    // EMAIL
    if (data.email.length < 1) {
    	if(result.error == ""){
      		result.error = "Por favor, introduce tu email para continuar";
    	}
      	$('#form-email').addClass('input-error')
    }else{
		var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
     	var emailvalido = regex.test(data.email);
     	if (!emailvalido){
	      	if(result.error == "") {
				result.error = "Por favor, introduce un email valido";
			}
			$('#form-email').addClass('input-error')
     	}else{
    		$('#form-email').removeClass('input-error')
     	}
    }

    // COMUNIDADES, siempre tiene valor, por defecto es 1

    // PREGUNTAS
    if (preguntas.length == 0) {
    	if(result.error == ""){
      		result.error = "Por favor, debes responder al menos una de las preguntas";
    	}
      	$('.form-preguntas').addClass('input-error')
    }else{
    	$('.form-preguntas').removeClass('input-error')
    }

    // ACEPTO BASES
	if(typeof data.acepto === 'undefined'){
    	if(result.error == ""){
      		result.error = "Por favor, debes aceptar las bases legales";
    	}
    	$('.texto_bases_dos').addClass('input-error')
	}else{
		$('.texto_bases_dos').removeClass('input-error')
	}

	// si alguna validacion tira error, es un error
	// si no, procesas el formulario y esperas respuesta
    if (result.error != "") {
		$('#form-alert-error').text(result.error).fadeIn();
		$('#form-alert-success').hide()
	}else{
		$('#form-alert-error').hide()
		// procesa el envío
        $.ajax({
            type: "POST",
            url: '../api/db_conexion.php',
            //data: data.serialize(), // los datos raw del formulario deben ser serialized
            data: data,
            success: function(res){
                var jsondata = JSON.parse(res);
                // recibe respuesta
                if (typeof jsondata.error !== 'undefined'){
                  msg = 'Se ha producido un error ('+jsondata.error+')';
                  $('#form-alert-error').text(msg).fadeIn();
                }else{
                  $('#form-alert-success').text(jsondata.success).fadeIn();
                }
           },
           error: function(res) {
              if(res.statusText){
                ajaxerror = res.statusText
              }else{
                ajaxerror = '-'
              }
              msg = 'Se ha producido un error (ajax_error: '+ajaxerror+')'
              $('#form-alert-error').text(msg).fadeIn();             
           }
       });
	}

  })
});