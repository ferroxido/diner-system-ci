<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="./css/estilos-pdf.css">
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

	<h3>Reporte de consumo</h3>
	<h4><?= $titulo = ($mes == 13)? 'Todos los meses':'Mes de '.$meses[$mes-1]; ?></h4>
	<br />
	<br />
	<div id="footer">
		<hr>
		<p><?= date('d-m-Y H:i'); ?> - <?= $this->session->userdata('nombre_usuario'); ?></p>
	</div>

	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th> Días </th>
				<th> Consumidos </th>
				<th> No Consumidos </th>
				<th> Anulados </th>
			</tr>
		</thead>
		<tbody id="resultado_tickets_tabla4">
			<?php foreach ($registros4 as $registro4): ?>
			<tr>
				<td><?= $dias[date('w',strtotime($registro4->fecha))]." ".date('d',strtotime($registro4->fecha))." de ".$meses[date('n',strtotime($registro4->fecha))-1]. " del ".date('Y',strtotime($registro4->fecha)); ?></td>
				<td><?= $registro4->consumidos; ?></td>
				<td><?= ($registro4->impresos + $registro4->vencidos); ?></td>
				<td><?= $registro4->anulados; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div id="blanco">
		
	</div>
</body>
</html>