$body = $("body");
$(document).on({
    ajaxStart: function() { $body.addClass("loading"); },
    ajaxStop: function() { $body.removeClass("loading"); }
});

$(document).ready(function() {
    $('a.procesar').click(function (event){
         event.preventDefault();
         $.ajax({
            url: $(this).attr('href'),
            success: function(response) {
                $('div#mensaje').html("");
                mensaje = 'Proceso finalizado exitosamente, ' + response + ' nuevos registros insertados';
                salida = "";
                salida = "<div class='alert alert-dismissable alert-success'>";
                salida = salida + "<button type='button' class='close' data-dismiss='alert'>×</button>";
                salida = salida + "<h4>Mensaje de validación</h4>";
                salida = salida + "<h5>"+mensaje+"</h5>";
                salida = salida + "</div>";
                $('div#mensaje').append(salida);
            }
        });
    });
});
