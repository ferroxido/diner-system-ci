$(document).ready(function(){

	$('.menu-list li a').click(function(){
		$('.menu-list li a').addClass('a-noactivo');
		$(this).removeClass('a-noactivo');
	    $('.a-activo')	.removeClass('a-activo');
		$(this).addClass('a-activo');
	});

});