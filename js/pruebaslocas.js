$(document).ready(function() {
  	
	$('.calendario .dia').click(function(){
		//Obtengo el mes y el año del calendario al hacer click en cualquier dia
		fecha = $(this).parents(':eq(2)').find('.encabezado').html().replace(/&nbsp;/gi,'/');
		var tokens = fecha.split('/');
		alert(tokens[0] +'-'+tokens[1]);

		

	});
	

});
