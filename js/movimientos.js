function filtrar_ajax(){
	var pathArray = window.location.pathname.split( '/' );
	dni = pathArray[4];//3 en produccion
	fecha = $("#buscar_fecha").val();
	$.ajax({
		type: "post",
		url: base_url + "usuarios/filtrar_movimientos",
		cache: false,
		data:{
			dni:dni,
			fecha:fecha,
		},
		success: function(response){
			$('#destino_resultado').html("");
			var obj = JSON.parse(response);
			if(obj.registros.length > 0){
				try{
					var items = [];
					$.each(obj.registros, function(i,val){
						var string_fecha = val.fecha;
						var fecha = string_fecha.substring(8,10) + '/' + string_fecha.substring(5,7) + '/' + string_fecha.substring(0,4);
						var saldoDelta = val.dinero - obj.categoria_importe * val.comprados + obj.categoria_importe * val.anulados;
						if(saldoDelta >= 0){
							saldoDeltaCadena = '+'+saldoDelta;
						}else{
							saldoDeltaCadena = saldoDelta;
						}
						cadena = '<tr>'+'<td>'+ fecha +'</td>'+'<td>'+ val.dinero +'</td>'+'<td>'+ val.comprados +'</td>'+'<td>'+ val.anulados +'</td>'+'<td>'+ saldoDeltaCadena +'</td>' +'</tr>';
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
	$("#buscar_fecha").change(filtrar_ajax);
});


window.onload = function () {
    $("#buscar_fecha").val("");
}
