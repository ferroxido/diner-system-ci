function filtrar_ajax(){
	nombre = $('#buscar_nombre').val();
	dni = $("#buscar_dni").val();
	id = $("#buscar_id").val();
	fecha = $("#buscar_fecha").val();
	estado = $('#drop_down').val();
	primeraPagina = 1;
	$.ajax({
		type: "post",
		url: base_url + "tickets/filtrar/" + primeraPagina,
		cache: false,
		data:{
			nombre:nombre,
			dni:dni,
			id:id,
			fecha:fecha,
			estado:estado
		},
		success: function(response){
			$('#destino_resultado').html("");
			var obj = JSON.parse(response);
			if(obj.length > 0){
				try{
					var items = [];
					$.each(obj, function(i,val){
						var string_fecha = val.fecha;
						var fecha = string_fecha.substring(8,10) + '/' + string_fecha.substring(5,7) + '/' + string_fecha.substring(0,4);
						cadena = '<tr>'+'<td>'+ val.id_ticket +'</td>'+'<td>'+ fecha +'</td>'+'<td>'+ val.importe_ticket +'</td>'+'<td>'+ val.estado_ticket +'</td>'+'<td>'+ val.dni +'</td>'+'<td>'+ val.nombre_usuario +'</td>';
						ruta = "'"+base_url+ "tickets/detalles/" +val.id_ticket +"'";
						cadena = cadena + '<td><a class="glyphicon glyphicon-search" href='+ ruta +'"> </a></td>'+'</tr>';
						items.push(cadena);
					});
					$('#destino_resultado').append.apply($('#destino_resultado'), items);
				}catch(e) {
					console.log('Error try/catch');
				}
			}else{
				$('#destino_resultado').html($('<tr/>').text(" No Se encontraron registros"));
			}					
		},
		error: function(){
			console.log('Error en la respuesta');
		}
	});
}

function get_total_pages(){
	nombre = $('#buscar_nombre').val();
	dni = $("#buscar_dni").val();
	id = $("#buscar_id").val();
	fecha = $("#buscar_fecha").val();
	estado = $('#drop_down').val();
	
	$.ajax({
		type: "post",
		url: base_url + "tickets/get_total_pages",
		cache: false,
		data:{
			nombre:nombre,
			dni:dni,
			id:id,
			fecha:fecha,
			estado:estado
		},
		success: function(response){
			$('#paginacion').bootpag({ total:response});
		}
	});	
}

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
  	$("#buscar_fecha").datepicker({
   		changeMonth: true
   	});
  	
   	$('#buscar_nombre').keyup(filtrar_ajax);
   	$('#buscar_nombre').keyup(get_total_pages);
   	
	$("#buscar_dni").keyup(filtrar_ajax);
	$("#buscar_dni").keyup(get_total_pages);
	
	$("#buscar_id").keyup(filtrar_ajax);
	$("#buscar_id").keyup(get_total_pages);
	
	$("#buscar_fecha").change(filtrar_ajax);
	$("#buscar_fecha").change(get_total_pages);
	
	$('#drop_down').change(filtrar_ajax);
	$('#drop_down').change(get_total_pages);
});


window.onload = function () { 
    $("#buscar_nombre").val("");
    $("#buscar_dni").val("");
    $("#buscar_id").val("");
    $("#buscar_fecha").val("");
    $("#drop_down").val("10");
}

$(document).ready(function() {
	numeroPaginas = $('#numeroPaginas').val();
    $('#paginacion').bootpag({
    total: numeroPaginas,
    page: 1,
    maxVisible: 8
    }).on('page', function(event, num){
    	page_num = num;
		nombre = $('#buscar_nombre').val();
		dni = $("#buscar_dni").val();
		id = $("#buscar_id").val();
		fecha = $("#buscar_fecha").val();
		estado = $('#drop_down').val();
		
		$.ajax({
			type: "post",
			url: base_url + "tickets/filtrar/" + num,
			cache: false,
			data:{
				nombre:nombre,
				dni:dni,
				id:id,
				fecha:fecha,
				estado:estado
			},
			success: function(response){

				$('#destino_resultado').html("");
				var obj = JSON.parse(response);
				if(obj.length > 0){
					try{
						var items = [];
						$.each(obj, function(i,val){
							var string_fecha = val.fecha;
							var fecha = string_fecha.substring(8,10) + '/' + string_fecha.substring(5,7) + '/' + string_fecha.substring(0,4);
							cadena = '<tr>'+'<td>'+ val.id_ticket +'</td>'+'<td>'+ fecha +'</td>'+'<td>'+ val.importe_ticket +'</td>'+'<td>'+ val.estado_ticket +'</td>'+'<td>'+ val.dni +'</td>'+'<td>'+ val.nombre_usuario +'</td>';
							ruta = "'"+base_url+ "tickets/detalles/" +val.id_ticket +"'";
							cadena = cadena + '<td><a class="glyphicon glyphicon-search" href='+ ruta +'"> </a></td>'+'</tr>';
							items.push(cadena);
						});
						$('#destino_resultado').append.apply($('#destino_resultado'), items);
					}catch(e) {
						console.log('Error try/catch');
					}
				}else{
					$('#destino_resultado').html($('<tr/>').text(" No Se encontraron registros"));
				}					
			},
			error: function(){						
				console.log('Error en la respuesta');
			}
		});
    });
});