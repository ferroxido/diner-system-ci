<div class="page-header">	
	<h3>Feriados entre <?= $desde." y el ".$hasta; ?></h3>
	<h4>Paso 2: Agregar los feriados (algunos ya vienen por defecto cargados).Al confirmar se completa la operación y se genera un nuevo calendario.</h4>
</div>

<div class="row" style="margin:1em 0;">
	<div class="col-md-offset-3 col-md-4">
		<?= form_open('calendario/insert');?>
			<?= form_hidden('descripcion', $descripcion); ?>
			<?= form_hidden('desde', $desde); ?>
			<?= form_hidden('hasta', $hasta); ?>
			<?= form_button(array('type'=>'submit', 'content'=>' Confirmar Calendario', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
		<?= form_close(); ?>
	</div>

	<div class="col-md-3">
		<?= form_open('calendario/agregar_feriados');?>
			<?= form_hidden('descripcion', $descripcion); ?>
			<?= form_hidden('desde', $desde); ?>
			<?= form_hidden('hasta', $hasta); ?>
			<?= form_button(array('type'=>'submit', 'content'=>' Agregar Feriado', 'class'=>'btn btn-primary glyphicon glyphicon-plus')); ?>
		<?= form_close(); ?>
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
