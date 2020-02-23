$(document).ready(function() {

  $('#alert-resolver').hide()
  $('#alert-resuelto').hide()
  $("#resolver_sorteo").click(function() {

    $.ajax({
        type: "POST",
        url: '../api/elegir_ganador.php',
        data: {"token":"12345"},
        success: function(res){
            var jsondata = JSON.parse(res);
             //console.log(jsondata)
            if (jsondata == false){
              msg = 'El sorteo no se ha podido resolver';
              $('#alert-resolver').text(msg).fadeIn(900);
              $('#alert-resuelto').hide()
                $('#alert-warning').hide()
            }else{
              msg = 'El sorteo se ha realizado, recarga la página para ver el resultado';
              $('#alert-resuelto').text(msg).fadeIn(900);
              $('#alert-resolver').hide()
              $("#resolver_sorteo").hide()
              $('#alert-warning').hide()
              location.reload();     
            }
       },
       error: function(res) {
           
       }
    });

  });
});