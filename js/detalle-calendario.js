$(document).ready(function() {
	
	$('.calendario .dia').click(function(){
		//Obtengo el mes y el año del calendario al hacer click en cualquier dia
		fecha = $(this).parents(':eq(2)').find('.encabezado').html().replace(/&nbsp;/gi,'/');
		var tokens = fecha.split('/');

		var pathArray = window.location.pathname.split( '/' );
		var idCalendario = pathArray[3];//3 en produccion

		//Datos obtenidos del calendario	
		var diaNum = $(this).find('.dia_num').html();
		var month = tokens[0];
		var year = tokens[1];

		if(diaNum != null && month != null && year != null && idCalendario != null){
			$.ajax({
				type: "post",
				url: base_url + "calendario/mostrar_info",
				cache: false,
				data: {
					dia:diaNum,
					month:month,
					year:year,
					idCalendario:idCalendario
				},
				success: function(response){
					$('#encabezado_modal').html("");
					$('#form_modal').html("");

					var obj = JSON.parse(response);
					if(obj.length > 0){
						try{
							var items = [];
							$.each(obj, function(i,val){
								//Formateamos la fecha
								var string_fecha = val.fecha;
								var fecha = string_fecha.substring(8,10) + '-' + string_fecha.substring(5,7) + '-' + string_fecha.substring(0,4);

								//Fecha
								$('#encabezado_modal').append('Información del ' + fecha);
								
								contenidohtml = '<div class="row"><div class="form-group">';
								contenidohtml = contenidohtml + "<input name='fecha' type='hidden' class='form-control' value='" + val.fecha + "'>";
								contenidohtml = contenidohtml + '</div></div>';

								//Tickets totales
								contenidohtml = contenidohtml + '<div class="row"><div class="form-group">';
								contenidohtml = contenidohtml + '<label for="tickets_totales" class="col-md-offset-2 col-md-3 control-label">Tickets totales: </label>'
								contenidohtml = contenidohtml + '<div class="col-md-4">';
								contenidohtml = contenidohtml + '<input name="tickets_totales" class="form-control" value="' + val.tickets_totales + '">';
								contenidohtml = contenidohtml + '</div></div></div>';

								//Tickets vendidos
								contenidohtml = contenidohtml + '<div class="row"><div class="form-group">';
								contenidohtml = contenidohtml + '<label for="tickets_vendidos" class="col-md-offset-2 col-md-3 control-label">Tickets Vendidos: </label>'
								contenidohtml = contenidohtml + '<div class="col-md-4">';
								contenidohtml = contenidohtml + '<input name="tickets_vendidos" class="form-control" disabled value="' + val.tickets_vendidos + '">';
								contenidohtml = contenidohtml + '<input type="hidden" name="tickets_vendidos" value="' + val.tickets_vendidos + '">';
								contenidohtml = contenidohtml + '</div></div></div>';

								//Evento
								contenidohtml = contenidohtml + '<div class="row"><div class="form-group">';
								contenidohtml = contenidohtml + '<label for="evento" class="col-md-offset-2 col-md-3 control-label">Evento: </label>'
								contenidohtml = contenidohtml + '<div class="col-md-4">';
								contenidohtml = contenidohtml + '<textarea name="evento" class="form-control" rows="3">' + val.evento + '</textarea>';
								contenidohtml = contenidohtml + '</div></div></div>';

								//Radio button
								contenidohtml = contenidohtml + '<div class="row"><div class="form-group">';
								contenidohtml = contenidohtml + '<label class="col-md-offset-2 col-md-3 control-label">Marcar como: </label>';
								contenidohtml = contenidohtml + '<div class="col-md-4">';
								if (val.estado == 1){
									contenidohtml = contenidohtml + '<div><label><input name="estado" value="1" type="radio" checked/> Habilitado</label></div>';
									contenidohtml = contenidohtml + '<div><label><input name="estado" value="0" type="radio"/> Feriado</label></div>';
								}else if (val.estado == 0){
									contenidohtml = contenidohtml + '<div><label><input name="estado" value="1" type="radio"/> Habilitado</label></div>';
									contenidohtml = contenidohtml + '<div><label><input name="estado" value="0" type="radio" checked/> Feriado</label></div>';
								}
								contenidohtml = contenidohtml + '</div></div></div>';

								//Boton actualizar y boton hidden
								ruta = "'" + base_url + "calendario/anular/" + fecha +"'";
								contenidohtml = contenidohtml + '<div class="form-group"><div class="col-md-offset-7"><a href='+ruta+' id="boton_anular" class="btn btn-primary" style="visibility:hidden;">Anular</a> <input type="submit" class="btn btn-success" value="Actualizar"></div></div>';

								items.push(contenidohtml);
							});	
							$('#form_modal').append.apply($('#form_modal'), items);
						}catch(e) {
							console.log('Error try/catch');
						}
					}else{
						$('#form_modal').html($('<h3/>').text(" No hay información disponible"));
					}
					$('#myModal').modal('show');
					//alert($('#myModal').hasClass('in'));
				},
				error: function(){
					console.log('error en la respuesta');
				}
			});
		}//fin del if
	});//fin evento click

});//fin document ready


$(document).ready(function() {
	$('#form_modal').submit(function(event){
		$('#mensaje_exito').fadeIn(1500);
	  	$('#mensaje_exito').css({"padding":"0.5em"});
		$.ajax({
			type: 'post',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data){
				$("#mensaje_exito").html(data);
				$("#mensaje_exito").fadeOut(3000);
			},
			error: function(){						
				console.log('error en la respuesta');
			}
		});
		event.preventDefault();
	});
});

$(document).ready(function() {
	var pathArray = window.location.pathname.split( '/' );
	var idCalendario = pathArray[3];//En producción cambiar a 3.

	$.ajax({
		type: "POST",
		url: base_url + "calendario/get_dias_feriados",
		data: {
			idCalendario:idCalendario
		},
		success: function(data){

			var obj = JSON.parse(data);
			if(obj.length > 0){
				try{
					var items = []; 	
					$.each(obj, function(i,val){
						cadena = 'td:contains('+ val.dia +')';
						$(cadena).first().css('background', '#FFB9C4');
					});			
				}catch(e) {
					console.log('error ajax');
				}
			}
		},
		error: function(){						
			console.log('error en la respuesta');
		}
	});	
});

var lastKeyUpAt = 0;

$(document).on('keydown', function(event) {

	codeX = 88;
	if(event.which == codeX){
	    // Set key down time to the current time
	    var keyDownAt = new Date();

	    // Use a timeout with 1000ms (this would be your X variable)
	    setTimeout(function() {
	        // Compare key down time with key up time
	        if (+keyDownAt > +lastKeyUpAt){
	            // Key has been held down for x seconds
	        	$('#boton_anular').css({"visibility":"visible"});
	        }
	    }, 3000);
		
	}
});

$(document).on('keyup', function() {
    // Set lastKeyUpAt to hold the time the last key up event was fired
    lastKeyUpAt = new Date();
});
