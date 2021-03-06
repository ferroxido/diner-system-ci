<div class="page-header">	
	<h1>Log de Usuarios <small>Consulta de operaciones</small></h1>
</div>
<div class="row">
	<div class="filtros col-md-12">
		<fieldset>
		<div class="row">
			<div class="form-group">
				<label for="buscar_acciones" class="col-md-2 col-sm-2">Por Acciones: </label>
				<div class="col-md-4 col-sm-4">
					<?= form_dropdown('id_accion', $acciones, 0,"id='drop_down' class='form-control'"); ?>
				</div>
			</div>
		</div>
		
		<br>

		<div class="row">
			<div class="form-group">
				<label for="buscar_dni" class="col-md-2 col-sm-2">Por dni: </label>
				<div class="col-md-4 col-sm-4">
					<div class="input-group">
						<input id="buscar_dni" name="buscar_dni" type="text" class="form-control" />
						<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="paginacion">
			<input id="numeroPaginas" type="hidden" value='<?= $numeroPaginas; ?>'>
		</div>
		</fieldset>
	</div>
</div>

<br>

<div class="tablas-propias">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th> ID </th>
				<th> DNI </th>
				<th> Nombre </th>
				<th> Fecha </th>
				<th> Acción </th>
				<th> Lugar </th>
				<th> Descripción </th>
			</tr>
		</thead>

		<tbody id="destino_resultado">
			<?php foreach ($registros as $registro): ?>
			<tr>
				<td><?= $registro->id; ?></td>
				<td><?= $registro->dni; ?></td>
				<td><?= $registro->nombre; ?></td>
				<td><?= date("d/m/Y H:i:s",strtotime($registro->fecha)); ?></td>
				<td><?= $registro->accion; ?></td>
				<td><?= ($registro->lugar == 0)? 'Web':'Máquina '.$registro->lugar; ?></td>
				<td><?= $registro->descripcion; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?= base_url('js/jquery.bootpag.min.js'); ?>"></script>
<script type="text/javascript">
	base_url = '<?=base_url(); ?>';
</script>
<script src="<?= base_url('js/log_usuarios.js'); ?>"></script>