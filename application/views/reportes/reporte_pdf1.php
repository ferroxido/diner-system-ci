<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="./css/estilos-pdf.css">
	<link rel="stylesheet" href="./css/jquery.jqplot.min.css">
	<!-- <link href="<?= base_url('css/estilos-pdf.css'); ?>" rel="stylesheet" /> -->
	<!-- <link href="<?= base_url('css/jquery.jqplot.min.css'); ?>" rel="stylesheet" /> -->
</head>
<body>
	<header>
		<div id="img-unsa-logo">
			<img src="./img/logomejorado.png">
		</div>
		<div id="info-encabezado">
			<h3>Universidad Nacional de Salta</h3>
			<h3>Comedor Universitario</h3>
			<h5>Avda. Bolivia 5150-Salta-4400</h5>
			<h5>Tel. 54-0387-425521</h5>
			<h5>Correo Electrónico: seccosu@unsa.edu.ar</h5>
		</div>
	</header>
	<hr>

	<h3>Informe de cantidad  de usuarios por Facultad y categoría de comensal</h3>
	<br />
	<br />

	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th> Facultades </th>
				<th> Becados </th>
				<th> Regulares </th>
				<th> Gratuitos </th>
				<th> Total de Usuarios </th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($registros as $registro): ?>
			<tr>
				<td><?= $registro->facultad; ?></td>
				<td><?= $registro->becados; ?></td>
				<td><?= $registro->regulares; ?></td>
				<td><?= $registro->gratuitos; ?></td>
				<td id="usuarios_<?= $registro->id_facultad; ?>"><?= $registro->total_usuarios; ?></td>
			</tr>
			<?php endforeach; ?>
			<tr class="info">
				<td>Totales: </td>
				<td><?= $totales->becados; ?></td>
				<td><?= $totales->regulares; ?></td>
				<td><?= $totales->gratuitos; ?></td>
				<td><?= $totales->total_usuarios; ?></td>
			</tr>
		</tbody>
	</table>
	<br>
		
	<div id="imgChart1"></div>
	<div id="chart1" style="margin-top:20px;"></div>

	<div id="footer">
		<hr>
		<p><?= date('d-m-Y H:i'); ?> - <?= $this->session->userdata('nombre_usuario'); ?></p>
	</div>
</body>
</html>	

<script src="<?= base_url('js/jquery.js'); ?>"></script>
<script src="<?= base_url('js/jquery.jqplot.min.js'); ?>"></script>
<script src="<?= base_url('js/jqplot.donutRenderer.min.js'); ?>"></script>
<script src="<?= base_url('js/jqplot.pieRenderer.min.js'); ?>"></script>
<script>
	// $(document).ready(function() {
	// 	alert('alert');
	//     var usuarios_facultad = {
	//         exa: parseInt($('#usuarios_6').text()),
	//         ing: parseInt($('#usuarios_10').text()),
	//         sal: parseInt($('#usuarios_12').text()),
	//         nat: parseInt($('#usuarios_8').text()),
	//         hum: parseInt($('#usuarios_9').text()),
	//         eco: parseInt($('#usuarios_7').text()),
	//         iem: parseInt($('#usuarios_13').text()),
	//         rec: parseInt($('#usuarios_11').text())
	//     };


	//     jQuery.jqplot.config.enablePlugins = true;
	//     plot1 = jQuery.jqplot('chart1',
	//         [[
	//             ['Cs. Exactas', usuarios_facultad.exa],
	//             ['Ingeniería', usuarios_facultad.ing], 
	//             ['Cs. de la Salud', usuarios_facultad.sal],
	//             ['Cs. Naturales', usuarios_facultad.nat],
	//             ['Humanidades', usuarios_facultad.hum], 
	//             ['Cs Económicas Jurídicas y Sociales', usuarios_facultad.eco], 
	//             ['IEM', usuarios_facultad.iem], 
	//             ['Rectorado', usuarios_facultad.rec]
	//         ]], 
	//         {
	//             title: {
	//                 text: 'Usuarios por Facultad',
	//                 show: true
	//             },
	//             grid: {
	//                 drawGridLines: true,
	//                 gridLineColor: '#000',
	//                 background: '#EEEDEB',
	//                 borderColor: '#EEEDEB',
	//                 borderWidth: 2.0,
	//                 shadow: false,
	//                 shadowAngle: 45,
	//                 shadowOffset: 1.5,
	//                 shadowWidth: 3,
	//                 shadowDepth: 3,
	//                 shadowAlpha: 0.07,
	//                 renderer: $.jqplot.CanvasGridRenderer
	//             },
	//             seriesDefaults: {
	//                 shadow: false,
	//                 renderer: jQuery.jqplot.PieRenderer,
	//                 rendererOptions: { padding: 2, sliceMargin: 2, showDataLabels: true }
	//             }, 
	//             legend: { show:true, location: 'e', fontSize: '1.2em' },
	//             seriesColors: [ "#DD4814", "#000000", "#77216F", "#0166A5", "#333333", "#A2B2D6", "#29AAFB", "#EA4E4F"]
	//         }
	//     );
	    
	//     //var imgData = $('#chart1').jqplotToImageStr({});

	//     var imgelem = $('#chart1').jqplotToImageElem();
	//     var imageSrc = imgelem.src; // this stores the base64 image url in imagesrc    
	//     var imgElem = $('<img/>').attr('src', imageSrc);
	//     //open(imageSrc);
	//     $('#imgChart1').append(imgElem);
	// });
</script>