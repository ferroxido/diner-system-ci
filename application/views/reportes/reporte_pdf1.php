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

	<div id="footer">
		<hr>
		<p><?= date('d-m-Y H:i'); ?> - <?= $this->session->userdata('nombre_usuario'); ?></p>
	</div>
</body>
</html>