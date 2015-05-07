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

$(document).ready(function(){
	set_alturas_columnas();
});


