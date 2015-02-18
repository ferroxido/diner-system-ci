$(document).ready(function() {
  	
	$('.calendario .dia').click(function(){
		//Obtengo el mes y el año del calendario al hacer click en cualquier dia
		fecha = $(this).parents(':eq(2)').find('.encabezado').html().replace(/&nbsp;/gi,'/');
		var tokens = fecha.split('/');

		var pathArray = window.location.pathname.split( '/' );
		var idCalendario = pathArray[4];//

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
					//$('#cuerpo_modal').html("");
					$('#pie_modal').html("");
					var obj = JSON.parse(response);
					if(obj.length > 0){
						try{
							var items = []; 	
							$.each(obj, function(i,val){
								//Formateamos la fecha
								var string_fecha = val.fecha;
								var fecha = string_fecha.substring(8,10) + '/' + string_fecha.substring(5,7) + '/' + string_fecha.substring(0,4);
								
								$('#encabezado_modal').append('Información del ' + fecha);

								$('#fecha_dia').html("");
								$('#fecha_dia').append("<input name='fecha' type='hidden' class='form-control' value='" + val.fecha + "'>");

								$('#tickets_totales').html("");
								$('#tickets_totales').append('<input name="tickets_totales" class="form-control" value="' + val.tickets_totales + '">');

								$('#tickets_vendidos').html("");
								$('#tickets_vendidos').append('<input name="tickets_vendidos" class="form-control" disabled value="' + val.tickets_vendidos + '">');

								$('#textarea_evento').html("");
								$('#textarea_evento').append('<textarea name="evento" class="form-control" rows="3">' + val.evento + '</textarea>');

								$('#radio_button_estado').html("");
								$('#radio_button_estado').append('<div><label><input name="estado" id="habilitado" value="habilitado" type="radio"/> Habilitado</label></div>');
								$('#radio_button_estado').append('<div><label><input name="estado" id="feriado" value="feriado" type="radio"/> Feriado</label></div>');

								$('#boton_actualizar').html("");
								$('#boton_actualizar').append('<input type="submit" class="btn btn-success" value="Actualizar">');
								
							});	
							
						}catch(e) {
							alert('Error');
						}
					}else{
						$('#cuerpo_modal').html($('<h3/>').text(" No hay información disponible"));
					}
					$('#myModal').modal('show');
				},
				error: function(){
					alert('Error en la respuesta');
				}
			});
		}//fin del if
	});//fin evento click
	

});//fin document ready
