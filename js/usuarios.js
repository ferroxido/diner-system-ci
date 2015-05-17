function buscar_ajax(){
	if($("#buscar_nombre").val().length >= 0 || $("#buscar_dni").val().length >= 0 || $("#buscar_lu").val().length >= 0){

		nombre = $("#buscar_nombre").val();
		dni = $("#buscar_dni").val();
		lu = $("#buscar_lu").val();
		num = 1;

		$.ajax({
			type: "post",
			url: base_url + "usuarios/search/" + num,
			cache: false,				
			data:{
				nombre:nombre,
				dni:dni,
				lu:lu
			},
			success: function(response){
				$('#destino_resultado').html("");
				
				var obj = JSON.parse(response);
				if(obj.length > 0){
					try{
						var items = []; 	
						$.each(obj, function(i,val){
							if(val.estado == '0'){
								clase = 'danger';
							}else if(val.estado == '3'){
								clase = 'warning';
							}else{
								clase = '';
							}
							cadena = '<tr class="' + clase + '">'+'<td>'+ val.dni +'</td>'+'<td>'+ val.nombre +'</td>'+'<td>'+ val.lu +'</td>'+'<td>'+ val.facultad +'</td>'+'<td>'+ val.categoria +'</td>'+'<td>'+ val.saldo +'</td>';
							ruta = "'"+base_url+ "usuarios/edit/" +val.dni +"'";
							cadena = cadena + '<td><a class="glyphicon glyphicon-pencil" href='+ ruta +'"> </a></td>'+'</tr>';
							items.push(cadena);
						});	
						$('#destino_resultado').append.apply($('#destino_resultado'), items);
					}catch(e) {		
						console.log('Error try/catch');
					}		
				}else{
					$('#destino_resultado').html($('<tr/>').text("No Se encontraron registros"));
				}		
				
			},
			error: function(){						
				console.log('Error en la respuesta');
			}
		});
	}
	//return false;	
}

function get_total_pages(){
	nombre = $("#buscar_nombre").val();
	dni = $("#buscar_dni").val();
	lu = $("#buscar_lu").val();
	$.ajax({
		type: "post",
		url: base_url + "usuarios/get_total_pages",
		cache: false,
		data:{
			nombre:nombre,
			dni:dni,
			lu:lu
		},
		success: function(response){
			$('#paginacion').bootpag({ total:response});
		}
	});	
}

$(document).ready(function(){
	$("#buscar_nombre").keyup(buscar_ajax);
	$("#buscar_nombre").keyup(get_total_pages);
	$("#buscar_dni").keyup(buscar_ajax);
	$("#buscar_dni").keyup(get_total_pages);
	$("#buscar_lu").keyup(buscar_ajax);
	$("#buscar_lu").keyup(get_total_pages);
});

window.onload = function () {
 	$("#buscar_nombre").val("");
    $("#buscar_dni").val("");
    $("#buscar_lu").val("");
}

$(document).ready(function() {
	numeroPaginas = $('#numeroPaginas').val();
    $('#paginacion').bootpag({
    total: numeroPaginas,
    page: 1,
    maxVisible: 10
    }).on('page', function(event, num){
    	//Contenido ajax.
		nombre = $("#buscar_nombre").val();
		dni = $("#buscar_dni").val();
		lu = $("#buscar_lu").val();
		
		$.ajax({
			type: "post",
			url: base_url + "usuarios/search/" + num,
			cache: false,				
			data:{
				nombre:nombre,
				dni:dni,
				lu:lu
			},
			success: function(response){
				$('#destino_resultado').html("");
				var obj = JSON.parse(response);
				if(obj.length > 0){
					try{
						var items = []; 	
						$.each(obj, function(i,val){
							if(val.estado == '0'){
								clase = 'danger';
							}else if(val.estado == '3'){
								clase = 'warning';
							}else{
								clase = '';
							}
							cadena = '<tr class="' + clase + '">'+'<td>'+ val.dni +'</td>'+'<td>'+ val.nombre +'</td>'+'<td>'+ val.lu +'</td>'+'<td>'+ val.facultad +'</td>'+'<td>'+ val.categoria +'</td>'+'<td>'+ val.saldo +'</td>';
							ruta = "'"+base_url+ "usuarios/edit/" +val.dni +"'";
							cadena = cadena + '<td><a class="glyphicon glyphicon-pencil" href='+ ruta +'"> </a></td>'+'</tr>';
							items.push(cadena);
						});	
						$('#destino_resultado').append.apply($('#destino_resultado'), items);
					}catch(e) {		
						console.log('Error try/catch');
					}		
				}else{
					$('#destino_resultado').html($('<tr/>').text("No Se encontraron registros"));
				}		
				
			},
			error: function(){
				console.log('Error en la respuesta');
			}
		});
    });
});
