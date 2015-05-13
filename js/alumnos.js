function set_alturas_columnas(){
	//Divs con igual altura.
	var alturaCol1 = document.getElementById('col1').offsetHeight;
	var alturaCol2 = document.getElementById('col2').offsetHeight;
	var alturaCol3 = document.getElementById('col3').offsetHeight;
	if(alturaCol1 >= alturaCol2 && alturaCol1 >= alturaCol3) {
		document.getElementById('col2').style.height = alturaCol1 + 'px';
		document.getElementById('col3').style.height = alturaCol1 + 'px';
	} else if(alturaCol2 > alturaCol1 && alturaCol2 > alturaCol3){
		document.getElementById('col1').style.height = alturaCol2 + 'px';
		document.getElementById('col3').style.height = alturaCol2 + 'px';
	}else if(alturaCol3 > alturaCol1 && alturaCol3 > alturaCol2){
		document.getElementById('col1').style.height = alturaCol3 + 'px';
		document.getElementById('col2').style.height = alturaCol3 + 'px';
	}
}


function filtrar_ajax(){
	estado = $('#drop_down').val();
	$.ajax({
		type: "post",
		url: base_url + "usuarios/filtrar_tickets_alumno",
		cache: false,
		data:{
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
						cadena = '<tr>'+'<td>'+ val.id_ticket +'</td>'+'<td>'+ fecha +'</td>'+'<td>'+ val.estado +'</td>' +'</tr>';
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
			alert('error respuesta');
			console.log('Error en la respuesta');
		}
	});
}

$(document).ready(function(){
	set_alturas_columnas();
	$('#drop_down').change(filtrar_ajax);
});

window.onload = function () {
    $("#drop_down").val("5");
}

