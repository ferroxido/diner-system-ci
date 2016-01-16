<div class="row">
	<h3>Informes Contables</h3>
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#informe1" aria-controls="informe1" role="tab" data-toggle="tab">Tickets vendidos</a></li>
			<li role="presentation"><a href="#informe2" aria-controls="informe2" role="tab" data-toggle="tab">Servicios por Facultad</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="informe1">
				<br>
				<div class="tablas-propias">
					<table class="table table-condensed table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th> Días </th>
								<th> Cantidad Becados </th>
								<th> Cantidad Regulares </th>
								<th> Cantidad Gratuitos </th>
								<th> Cantidad Total </th>
								<th> Subtotal Becados $</th>
								<th> Subtotal Regulares $</th>
								<th> Subtotal Gratuitos $</th>
								<th> Total Tickets $</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($registros as $registro): ?>
							<tr>
								<td><?= date("d",strtotime($registro->dias)); ?></td>
								<td><?= $registro->cantidad_becados; ?></td>
								<td><?= $registro->cantidad_regulares; ?></td>
								<td><?= $registro->cantidad_gratuitos; ?></td>
								<td><?= $registro->cantidad_tickets; ?></td>
								<td><?= $registro->subtotal_becados; ?></td>
								<td><?= $registro->subtotal_regulares; ?></td>
								<td><?= $registro->subtotal_gratuitos; ?></td>
								<td><?= $registro->total_tickets; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="informe2">
				<h1>prueba</h1>
			</div>
		</div>
	</div>	
</div>
