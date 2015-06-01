<div class="page-header">
	<h1>Transacciones por día</h1>
</div>

<div class="row">
	<div class="filtros col-md-12">
		<fieldset>
			<div class="row filtro filtrolu">
				<div class="form-group">
					<label for="buscar_fecha" class="col-md-1">Fecha: </label>
					<div class="col-md-4">
						<div class="input-group">						
							<input id="buscar_fecha" name="buscar_fecha" type="text" class="form-control" placeholder="Filtrar por fecha" />
							<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
						</div>
					</div>
					<div class="col-md-offset-9">
						<a href="javascript:window.history.go(-1);" class="btn btn-primary glyphicon glyphicon-arrow-left"> Volver</a>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
</div>

<br>

<div class="tablas-propias">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th> Fecha </th>
				<th> Dinero ingresado </th>
				<th> Tickets Comprados </th>
				<th> Tickets Anulados </th>
				<th> Delta de Saldo </th>
			</tr>
		</thead>

		<tbody id="destino_resultado">
			<?php foreach ($registros as $registro): ?>
			<tr>
				<td><?= date("d/m/Y",strtotime($registro->fecha)); ?></td>
				<td><?= $registro->dinero; ?></td>
				<td><?= $registro->comprados; ?></td>
				<td><?= $registro->anulados; ?></td>
				<td><?= (($resultado = ($registro->dinero - $registro->comprados * $categoria_importe + $registro->anulados * $categoria_importe)) >= 0)? "+".$resultado:$resultado; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	base_url = '<?=base_url(); ?>';
</script>
<script src="<?= base_url('js/movimientos.js')?>"></script>
