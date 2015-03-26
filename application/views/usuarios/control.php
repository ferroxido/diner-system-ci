<div class="container">
	<div class="row page-header">
		<script type="text/javascript">
			base_url = '<?=base_url(); ?>';
		</script>
		<script src="<?= base_url('js/control-tickets.js'); ?>"></script>
		<div class="col-md-4">
			<div id="barcode" class="mijumbotron">
				<legend>Código de Barra</legend>
				<fieldset>
					<label for="barcode">Código de Barra</label>
					<div class="col-sm-8">
						<input id="barcode" type="text" name="barcode" class="form-control">
					</div>
					<div class="col-sm-2">
					<button id="enviar_barcode" type="button" class="btn btn-primary boton-grande">Enviar</button>
						
					</div>		
				</fieldset>
			</div>
			<br>
			<div id="historial-tickets" class="mijumbotron">
				<legend>Últimos Tickets. Total: <span id="total_tickets"><?= $totalConsumidosHoy; ?></span></legend>
				<fieldset>
				<div class="tablas-propias" style="height:250px;">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th> Número Ticket </th>
								<th> L.U </th>
							</tr>
						</thead>
						<tbody id="historial_tickets">

						</tbody>
					</table>
				</div>
				</fieldset>
			</div>
		</div>

		<div class="col-md-4">
			<div class="mijumbotron">
				<legend>Información del Usuario</legend>
				<fieldset>
				<div id="info-usuario">
					
				</div>
				</fieldset>
			</div>
		</div>

		<div class="col-md-4">
			<div class="mijumbotron">
				<legend>Información del Ticket</legend>
				<fieldset>
				<div id="info-ticket">
					
				</div>
				</fieldset>
			</div>
		</div>

	</div>
</div>