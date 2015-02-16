$(document).ready(function() {
  	
	$('.calendario .dia').click(function(){
		//Obtengo el mes y el año del calendario al hacer click en cualquier dia
		fecha = $(this).parents(':eq(2)').find('.encabezado').html().replace(/&nbsp;/gi,'/');
		var elem = fecha.split('/');
		alert(elem[0] +'-'+elem[1]);
	});
	

});
