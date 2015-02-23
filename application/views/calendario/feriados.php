<div class="page-header">	
	<h3>Feriados entre <?= $desde." y el ".$hasta; ?></h3>
	<h4>Paso 2: Agregar los feriados (algunos ya vienen por defecto cargados)</h4>
</div>


<div class="row" style="margin:1em 0;">

	<div class="col-md-offset-5 col-md-2">
		<!-- Este insert es diferente a todos los demas, mirar el codigo de la clase calendario-->
       	<?= my_boton_permisos('calendario/insert', ' Confirmar' ,'btn btn-success glyphicon glyphicon-ok'); ?>
	</div>

	<div class="col-md-3">
       	<?= my_boton_permisos('calendario/agregar_feriados', ' Agregar Feriado' ,'btn btn-primary glyphicon glyphicon-plus'); ?>
	</div>
	
	<div class="col-md-2">
       	<?= my_boton_permisos('calendario/create', ' Cancelar' ,'btn btn-default glyphicon glyphicon-remove'); ?>
	</div>
</div>

<div class="tablas-propias">
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th> ID </th>
			<th> Fecha </th>
			<th> Descripción </th>
			<th> Creado </th>
			<th> Modificado </th>
			<th> Editar </th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($feriados as $feriado): ?>
		<tr>
			<td><?= $feriado->id; ?></td>
			<td><?= date("d/m/Y",strtotime($feriado->fecha)); ?></td>
			<td><?= $feriado->descripcion; ?></td>
			<td><?= date("d/m/Y - H:i",strtotime($feriado->created)); ?></td>
			<td><?= date("d/m/Y - H:i",strtotime($feriado->updated)); ?></td>
			<td><a class="glyphicon glyphicon-pencil" data-toggle="modal" data-target="<?= "#myModal".$feriado->id ?>"></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>
