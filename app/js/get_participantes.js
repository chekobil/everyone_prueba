$(document).ready(function() {

$('#lista_participantes').hide()
$('#preguntas').hide()

    $.ajax({
        type: "POST",
        url: '../api/get_participantes.php',
        success: function(res){
            var jsondata = JSON.parse(res);
            // recibe respuesta
            if (jsondata === false){
              msg = 'No hay participantes';
              $('#alert-warning-participantes').text(msg);
            }else{
              //console.log(jsondata)
              msg = "Han participado "+jsondata.length+" personas"
              $('#alert-warning-participantes').text(msg);
              $('#preguntas').fadeIn(2000)
              $('#lista_participantes').fadeIn(3000)

              jQuery.each(jsondata, function(i, val) {

                // preguntas NULL
                if( val.comunidades == null ){
                  val.comunidades = ''
                }
                if( val.alta == null ){
                  val.alta = ''
                }
                if( val.gustaria == null ){
                  val.gustaria = ''
                }
                if( val.publicidad == null ){
                  val.publicidad = ''
                }
                if( val.acepto == null ){
                  val.acepto = ''
                }

                this_fila  = "<tr>"
                this_fila += "<th scope='row'>"+val.id+"</th>"
                this_fila += "<td>"+val.nombre+"</td>"
                this_fila += "<td>"+val.email+"</td>"
                this_fila += "<td>"+val.fecha+"</td>"
                // PREGUNTAS
                // 'comunidades','alta','gustaria', 'publicidad', 'acepto'
                this_fila += "<td>"+val.comunidades+"</td>"
                this_fila += "<td>"+val.alta+"</td>"
                this_fila += "<td>"+val.gustaria+"</td>"
                this_fila += "<td>"+val.publicidad+"</td>"
                this_fila += "<td>"+val.acepto+"</td>"
                // GANADOR
                this_fila += "<td>"+val.es_ganador+"</td>"

                this_fila += "</tr>"
                $("#lista_participantes tbody").append(this_fila);
          

              });
            }
       },
       error: function(res) {
          if(res.statusText){
            ajaxerror = res.statusText
          }else{
            ajaxerror = '-'
          }
          msg = 'Se ha producido un error (ajax_error: '+ajaxerror+')'
          $('#alert-warning-participantes').text(msg).fadeIn();             
       }
    });

});