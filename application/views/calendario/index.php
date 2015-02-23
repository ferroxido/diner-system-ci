<div class="page-header">	
	<h1>Calendarios <small>mantenimiento de registros</small></h1>
</div>

<div class="row" style="margin:1em 0;">
	<div class="col-lg-4">
		<div class="input-group">
        <input id="buscar" type="text" class="form-control" placeholder="Buscar por nombre" name="buscar" id="buscar">
        <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
    </div>
	</div>
	<div class="col-lg-4">
		<div class="input-group">
       		<?= my_boton_permisos('calendario/create', ' Agregar', 'btn btn-primary glyphicon glyphicon-plus'); ?>
    	</div>
	</div>
</div>

<div class="tablas-propias">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th> ID </th>
				<th> Descripción </th>
				<th> Desde </th>
				<th> Hasta </th>
				<th> Ver Detalle </th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($registros as $registro): ?>
			<tr>
				<td><?= $registro->id; ?></td>
				<td><?= $registro->descripcion; ?></td>
				<td><?= date("d/m/Y",strtotime($registro->desde)); ?></td>
				<td><?= date("d/m/Y",strtotime($registro->hasta)); ?></td>
				<td><?= anchor('calendario/detalle/'.$registro->id, ' ', array('class' => 'glyphicon glyphicon-search'));?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
