$(function($){
	$.datepicker.regional['es'] = {
	closeText: 'Cerrar',
	prevText: '&#x3c;Ant',
	nextText: 'Sig&#x3e;',
	currentText: 'Hoy',
	monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
	'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
	monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
	'Jul','Ago','Sep','Oct','Nov','Dic'],
	dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
	dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
	dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
	weekHeader: 'Sm',
	dateFormat: 'yy/mm/dd',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});

$(document).ready(function() {
  	
  	$("#desde").datepicker({
   		changeMonth: true
   	});

  	$("#hasta").datepicker({
   		changeMonth: true
   	});

	//Para los botones de previous y next
	$('.calendario tr a').click(function(){
		nueva_url = window.location.protocol + "://" + window.location.host + "/" + window.location.pathname;
		var pathArray = window.location.pathname.split( '/' );
		var nueva_href = $(this).attr('href') + '/' + pathArray[6];
		$(this).attr('href', nueva_href);
	});

	$('.calendario .dia').click(function(){
		dia_num = $(this).find('.dia_num').html();
		nueva_url = window.location.protocol + "://" + window.location.host + "/" + window.location.pathname;
		var pathArray = window.location.pathname.split( '/' );
		year = pathArray[4];
		month = pathArray[5];
		
		if(year == null){
			var f = new Date();
			month = f.getMonth() + 1;
			year = f.getFullYear();
		}

		id_calendario = pathArray[6];
		
		$.ajax({
			type: "post",
			url: base_url + "calendario/mostrar_info_dia/" + year + '/' + month + '/' + id_calendario,
			cache: false,
			data: {
				dia:dia_num
			},
			success: function(response){
				$('#resultadoCalendario').html("");
				var obj = JSON.parse(response);
				if(obj.length > 0){
					try{
						var items = []; 	
						$.each(obj, function(i,val){
							var string_fecha = val.fecha;
							var fecha = string_fecha.substring(8,10) + '/' + string_fecha.substring(5,7) + '/' + string_fecha.substring(0,4);
							
							cadena = '<label>Fecha:</label>';
							cadena = cadena + "<input class='form-control' value='" + fecha + "' disabled>";
							cadena = cadena + "<input name='fecha' type='hidden' class='form-control' value='" + val.fecha + "'>";
							cadena = cadena + '</br>';

							cadena = cadena + '<label>Tickets Totales:</label>';
							cadena = cadena + '<input name="tickets_totales" class="form-control" value="' + val.tickets_totales + '">';
							cadena = cadena + '</br>';

							cadena = cadena + '<label>Tickets Vendidos:</label>';
							cadena = cadena + '<input name="tickets_vendidos" class="form-control" disabled value="' + val.tickets_vendidos + '">';
							cadena = cadena + '</br>';

							cadena = cadena + '<label>Evento:</label>';
							cadena = cadena + '<textarea name="evento" class="form-control" rows="3">' + val.evento + '</textarea>';
							
							cadena = cadena + '</br>';							
							cadena = cadena + '<input type="submit" class="btn btn-success" value="Actualizar">';

							items.push(cadena);
						});	
						$('#resultadoCalendario').append.apply($('#resultadoCalendario'), items);
					}catch(e) {
						alert('Error');
					}
				}else{
					$('#destinoResultado').html($('<tr/>').text(" No Se encontraron registros"));
				}
			},
			error: function(){
				alert('Error en la respuesta');
			}
		});
	});
	
	$("#resultadoCalendario").submit(function(event){
		$("#mensaje").fadeIn(1500);
	  	$("#mensaje").css({"background-color":"yellow","padding":"0.5em"});
		$.ajax({
			type: "POST",
			url: $(this).attr("action"),
			data: $(this).serializeArray(),
			success: function(data){
				$("#mensaje").html(data);
				$("#mensaje").fadeOut(2000);
			},
			error: function(){						
				alert('Error en la respuesta');
			}
		});
		event.preventDefault();
	});

});
