<div class="col-md-8">
	<div id="tickets_anulables">
		<legend>Tickets Disponibles</legend>

		<div class="tablas-propias">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th> Número </th>
						<th> Fecha </th>
						<th> Importe </th>
						<th> Anular </th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($tickets as $ticket): ?>
					<tr>
						<td><?= $ticket->id_ticket; ?></td>
						<td><?= date("d/m/Y",strtotime($ticket->fecha)); ?></td>
						<td><?= $ticket->importe; ?></td>
						<td><?= anchor('usuarios/anulando/'.$ticket->id_ticket, ' ',array('class'=>'glyphicon glyphicon-remove', 'onClick'=>"return confirm('¿Estas Seguro?');")); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-offset-9">
				<?= anchor('usuarios/alumno', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left')); ?>
			</div>
		</div>
	</div>
</div>