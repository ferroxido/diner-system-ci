function filtrar_ajax(){
	accion = $('#drop_down').val();
	buscar_dni = $("#buscar_dni").val();
	
	$.ajax({
		type: "post",
		url: base_url + "log_usuarios/filtrar",
		cache: false,
		data:{
			accion:accion,
			buscar_dni:buscar_dni
		},
		success: function(response){
			$('#destino_resultado').html("");
			var obj = JSON.parse(response);
			if(obj.length > 0){
				try{
					var items = [];
					$.each(obj, function(i,val){
						cadena = '<tr>'+'<td>'+ val.id +'</td>'+'<td>'+ val.dni +'</td>'+'<td>'+ val.nombre +'</td>'+'<td>'+ val.fecha +'</td>'+'<td>'+ val.accion +'</td>'+'<td>'+ val.lugar +'</td>'+'<td>'+ val.descripcion +'</td>'+'</tr>';
						items.push(cadena);
					});
					$('#destino_resultado').append.apply($('#destino_resultado'), items);
				}catch(e) {
					console.log('error en el try/catch');
				}
			}else{
				$('#destino_resultado').html($('<tr/>').text(" No Se encontraron registros"));
			}		
			
		},
		error: function(){						
			console.log('error en la respuesta');
		}
	});
}

function get_total_pages(){
	accion = $('#drop_down').val();
	buscar_dni = $("#buscar_dni").val();
	$.ajax({
		type: "post",
		url: base_url + "log_usuarios/get_total_pages",
		cache: false,
		data:{
			accion:accion,
			buscar_dni:buscar_dni
		},
		success: function(response){
			$('#paginacion').bootpag({ total:response});
		}
	});	
}

$(document).ready(function() {
	$('#drop_down').change(filtrar_ajax);
	$('#drop_down').change(get_total_pages);
	$("#buscar_dni").keyup(filtrar_ajax);
	$("#buscar_dni").keyup(get_total_pages);
});


window.onload = function () { 
    $("#buscar_dni").val("");
    $("#drop_down").val("0");
}

$(document).ready(function() {
	numeroPaginas = $('#numeroPaginas').val();
    $('#paginacion').bootpag({
    total: numeroPaginas,
    page: 1,
    maxVisible: 10
    }).on('page', function(event, num){
    	//Contenido ajax.
	 	accion = $('#drop_down').val();
		buscar_dni = $("#buscar_dni").val();
		
		$.ajax({
			type: "post",
			url: base_url + "log_usuarios/filtrar",
			cache: false,
			data:{
				accion:accion,
				buscar_dni:buscar_dni,
				page_num: num
			},
			success: function(response){
				$('#destino_resultado').html("");
				
				var obj = JSON.parse(response);
				if(obj.length > 0){
					try{
						var items = [];
						$.each(obj, function(i,val){
							cadena = '<tr>'+'<td>'+ val.id +'</td>'+'<td>'+ val.dni +'</td>'+'<td>'+ val.nombre +'</td>'+'<td>'+ val.fecha +'</td>'+'<td>'+ val.accion +'</td>'+'<td>'+ val.lugar +'</td>'+'<td>'+ val.descripcion +'</td>'+'</tr>';
							items.push(cadena);
						});
						$('#destino_resultado').append.apply($('#destino_resultado'), items);
					}catch(e) {
						console.log('error en el try/catch');
					}
				}else{
					$('#destino_resultado').html($('<tr/>').text(" No Se encontraron registros"));
				}
				
			},
			error: function(){
				console.log('error en la respuesta');
			}

		});
    });
});
