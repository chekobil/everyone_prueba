$(document).ready(function() {

  $('#alert-warning').hide()
  $('#alert-ganador').hide()
  $('#resolver_sorteo').hide()

// GET_GANADOR
    $.ajax({
        type: "POST",
        url: '../api/get_ganador.php',
        success: function(res){
          var jsondata = JSON.parse(res);
          if (jsondata.result == 0){
            // CORS no permitido
            $('#alert-warning').text(jsondata.msg).fadeIn(500);
          }else{
            if (jsondata == false){
              msg = 'El sorteo aún no ha sido resuelto';
              $('#resolver_sorteo').fadeIn(1000)
              $('#alert-warning').text(msg).fadeIn(900);
            }else{
                $('#alert-ganador .nombre').text(jsondata[0].nombre);
                $('#alert-ganador .email').text(jsondata[0].email);
                $('#alert-ganador .fecha').text(jsondata[0].fecha);
                $('#alert-ganador').fadeIn(900);
                $('#delete_ganador').fadeIn(1500);
            }
          }
       },
       error: function(res) {
          if(res.statusText){
            ajaxerror = res.statusText
          }else{
            ajaxerror = '-'
          }
          msg = 'Se ha producido un error (ajax_error: '+ajaxerror+')'
          $('#alert-warning').text(msg).fadeIn();             
       }
    });

// DELETE_GANADOR
  $('#delete_ganador').click(function(e) {
      $.ajax({
          type: "POST",
          url: '../api/delete_ganador.php',
          success: function(res){
            var jsondata = JSON.parse(res);
            if (jsondata.result == 0){
              // CORS no permitido
              $('#alert-warning').text(jsondata.msg).fadeIn(500);
            }else{
              if (jsondata == false){

              }else{
                location.reload();
              }
            }
         },
         error: function(res) {
            if(res.statusText){
              ajaxerror = res.statusText
            }else{
              ajaxerror = '-'
            }
            msg = 'Se ha producido un error (ajax_error: '+ajaxerror+')'
            $('#alert-warning').text(msg).fadeIn();           
         }
      });
  });

});