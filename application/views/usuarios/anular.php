<div class="col-md-6">
	<div id="tickets-anulables" class="jumbotron">
		<legend>Anular Tickets</legend>
		<table class="table table-condensed table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th> Número </th>
					<th> Fecha </th>
					<th> Importe </th>
					<th> Estado </th>
					<th> Anular </th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($tickets_proximos as $ticket): ?>
				<tr>
					<td><?= $ticket->id; ?></td>
					<td><?= $ticket->fecha_ticket; ?></td>
					<td><?= $ticket->importe; ?></td>
					<td><?= ($ticket->estado == 1)?"Activo":"Anulado"; ?></td>
					<td><?= anchor('usuarios/anulando_ticket/'.$ticket->id, ' Anular',array('class'=>'btn btn-danger glyphicon glyphicon-trash', 'onClick'=>"return confirm('¿Estas Seguro?');")); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-offset-9">
				<?= anchor('usuarios/alumno', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left')); ?>
			</div>
		</div>
	</div>
</div>