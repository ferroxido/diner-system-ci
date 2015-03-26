function ajax_barcode(event){
    event.preventDefault();

    datos = $('input#barcode').val();

    $('input#barcode').val("");//Borrar texto en el input text.
    
   	var q = new Date();
	var m = q.getMonth();
	var d = q.getDate();
	var y = q.getFullYear();
	var hoy = new Date(y,m,d);
	
    if( datos != ''){

		$.ajax({
			type: "post",
			url: base_url + "usuarios/procesar_barcode",
			cache: false,
			data: {
				barcode:datos
			},
			success: function(response){
				$('div#info-usuario').html("");
				$('div#info-ticket').html("");
				var obj = JSON.parse(response);
				
				if(obj.length > 0){
					try{
						var items = [];
						$.each(obj, function(i,val){

							//Acomodo las fechas
							var string_fecha = val.fecha;
							y = string_fecha.substring(0,4);
							m = string_fecha.substring(5,7);
							d = string_fecha.substring(8,10);
							var fecha = d + '/' + m + '/' + y;
							string_fecha = val.fecha_log;
							var fecha_log = string_fecha.substring(8,10) + '/' + string_fecha.substring(5,7) + '/' + string_fecha.substring(0,4);

							html_info_usuario = '<div class="img-control"><img data-src="holder.js/100%x180" style="width: 70%;" src="' + val.ruta + '"></div><br />';
							html_info_usuario = html_info_usuario + '<label>DNI: ' + val.dni + '</label><br />';
							html_info_usuario = html_info_usuario + '<label>Nombre: ' + val.usuario_nombre + '</label><br />';
							html_info_usuario = html_info_usuario + '<label>L.U: '+ val.usuario_lu +'</label><br />';
							html_info_usuario = html_info_usuario + '<label>Facultad: '+ val.facultad_nombre +'</label><br />';
							html_info_usuario = html_info_usuario + '<label>Categoria: '+ val.categoria_nombre +'</label><br />';
							
							if (hoy < new Date(y, m-1, d)){
								$('div#info-ticket').append('<h2>Ticket No Valido</h2><br /><h3>No es del día de hoy</h3>');
							}else{
								html_info_ticket = '<label>Número Ticket: ' + val.id_ticket + '</label><br />';
								html_info_ticket = html_info_ticket + '<label>Fecha: ' + fecha + '</label><br />';
								html_info_ticket = html_info_ticket + '<label>Importe: $' + val.importe + '</label><br />';
								html_info_ticket = html_info_ticket + '<label>Estado: ' + val.estado + '</label><br />';
								if(val.estado == 2){
									html_info_ticket = html_info_ticket + '<h1 class="valido">VÁLIDO</h1>';
									$('#historial_tickets').append('<tr><td>'+ val.id_ticket +'</td><td>'+ val.usuario_lu +'</td></tr>');
									//Incrementar contador
									totalCosumidosHoy = parseInt($('span#total_tickets').html());
									$('span#total_tickets').html(totalCosumidosHoy + 1);
								}else if(val.estado == 3) {
									html_info_ticket = html_info_ticket + '<div class="novalido"><h1>NO VÁLIDO</h1><h4>Consumido el '+ fecha +'</h4></div>';
								}else if(val.estado == 0){
									html_info_ticket = html_info_ticket + '<div class="novalido"><h1>NO VÁLIDO</h1><h4>Anulado el '+ fecha_log +'</h4></div>';
								}else if(val.estado == 4){
									html_info_ticket = html_info_ticket + '<div class="novalido"><h1>NO VÁLIDO</h1><h4>Vencido el '+ fecha +'</h4></div>';
								}
								$('div#info-ticket').append(html_info_ticket);
							}
						});
						$('div#info-usuario').append(html_info_usuario);
						
					}catch(e) {
						console.log('Error try/catch');
					}
				}else{
					
				}
			},
			error: function(){
				console.log('Error en la respuesta');
			}
		});//fin ajax
	}	
}

$(document).ready(function(){
	$('input#barcode').focus();

	$(document).keypress(function(event){
		codeEnter = 13;
		if (event.which == codeEnter) {
			ajax_barcode(event);
		}
	});

	$('#enviar_barcode').click(ajax_barcode);

});

