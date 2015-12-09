<div class="row">
	<h3>Informes Estadísticos</h3>
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#informe1" aria-controls="informe1" role="tab" data-toggle="tab">Clasificación de Usuarios</a></li>
			<li role="presentation"><a href="#informe2" aria-controls="informe2" role="tab" data-toggle="tab">Servicios por Facultad</a></li>
			<li role="presentation"><a href="#informe3" aria-controls="informe3" role="tab" data-toggle="tab">Clasificación de Tickets</a></li>
			<li role="presentation"><a href="#informe4" aria-controls="informe4" role="tab" data-toggle="tab">Control de Consumo</a></li>
			<li role="presentation"><a href="#informe5" aria-controls="informe5" role="tab" data-toggle="tab">Ranking de Ausentismos</a></li>
			<li role="presentation"><a href="#informe6" aria-controls="informe6" role="tab" data-toggle="tab">Saldos</a></li>
		</ul>

		<div class="tab-content">
			<!-- Tabla para el primer reporte -->
			<div role="tabpanel" class="tab-pane active" id="informe1">
				<br>
				
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Facultades </th>
							<th> Becados </th>
							<th> Regulares </th>
							<th> Gratuitos </th>
							<th> Total de Usuarios </th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($registros as $registro): ?>
						<tr>
							<td><?= $registro->facultad; ?></td>
							<td><?= $registro->becados; ?></td>
							<td><?= $registro->regulares; ?></td>
							<td><?= $registro->gratuitos; ?></td>
							<td id="usuarios_<?= $registro->id_facultad; ?>"><?= $registro->total_usuarios; ?></td>
						</tr>
						<?php endforeach; ?>
						<tr class="info">
							<td>Totales: </td>
							<td><?= $totales->becados; ?></td>
							<td><?= $totales->regulares; ?></td>
							<td><?= $totales->gratuitos; ?></td>
							<td><?= $totales->total_usuarios; ?></td>
						</tr>
					</tbody>
				</table>
				
				<div class="col-md-offset-9">
					<?= form_open('reportes/generar_pdf'); ?>
						<input type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF1">
					<?= form_close(); ?>
				</div>
			</div>
			<!-- Tabla para el segundo reporte -->
			<div role="tabpanel" class="tab-pane" id="informe2">
				<br>
				<div class="filtros">
					<fieldset>
						<?= form_open('reportes/generar_pdf', array('id'=>'form_reporte')); ?>
						<div class="row">
							<div class="form-group">
								<div class="col-md-6">
									<label for="radio_dia">Por Día</label>&nbsp;
									<input type="radio" id="radio_dia" name="filtro_radio" value="filtrodia" checked="checked">&nbsp;
									<label for="radio_intervalo">Por intervalo</label>&nbsp;	
									<input type="radio" id="radio_intervalo" name="filtro_radio" value="filtrointervalo">
								</div>
							</div>
						</div>

						<br>
						
						<div class="row filtro filtrodia">
							<div class="form-group">
								<label for="dia" class="col-md-2">Por Día: </label>
								<div class="col-md-4">
									<input id="dia" name="dia" type="text" class="form-control" value="<?= $desde; ?>"/>
								</div>
								<div class="col-md-4">
									<button type="button" class="btn btn-primary boton-filtrar glyphicon glyphicon-leaf"> Filtrar</button>
								</div>
							</div>
							
						</div>
						
						<div class="filtro filtrointervalo">
							<div class="row">
								<div class="form-group">
									<label for="desde" class="col-md-2">Desde: </label>
									<div class="col-md-4">
										<input id="desde" name="desde" type="text" class="form-control" />
									</div>
								</div>
							</div>

							<br>

							<div class="row">
								<div class="form-group">
									<label for="hasta" class="col-md-2">Hasta: </label>
									<div class="col-md-4">
										<input id="hasta" name="hasta" type="text" class="form-control" />
									</div>
									<div class="col-md-4">
										<button type="button" class="btn btn-primary boton-filtrar glyphicon glyphicon-leaf"> Filtrar</button>	
									</div>
								</div>
							</div>
						</div>
						<?= form_close(); ?>
					</fieldset>
				</div>

				<br>
				
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Facultades </th>
							<th> Becados </th>
							<th> Regulares </th>
							<th> Gratuitos </th>
							<th> Total Tickets </th>
							<th> Total en $ </th>
						</tr>
					</thead>
					<tbody id="resultado_tickets_tabla">
						<?php foreach ($registros2 as $registro2): ?>
						<tr>
							<td><?= $registro2->facultad; ?></td>
							<td><?= $registro2->becados; ?></td>
							<td><?= $registro2->regulares; ?></td>
							<td><?= $registro2->gratuitos; ?></td>
							<td><?= $registro2->total_tickets; ?></td>
							<td><?= $registro2->total_pesos; ?></td>
						</tr>
						<?php endforeach; ?>
						<tr class="info">
							<td>Totales: </td>
							<td><?= $totales2->becados; ?></td>
							<td><?= $totales2->regulares; ?></td>
							<td><?= $totales2->gratuitos; ?></td>
							<td><?= $totales2->total_tickets; ?></td>
							<td><?= $totales2->total_importe; ?></td>
						</tr>
					</tbody>
				</table>
				
				<div class="col-md-offset-9">
					<input form="form_reporte" type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF2">
				</div>
			</div>

			<!-- Tabla para el tercer reporte -->
			<div role="tabpanel" class="tab-pane" id="informe3">
				<br>
				<div class="filtros">
					<fieldset>
						<?= form_open('reportes/generar_pdf', array('id'=>'form_reporte2')); ?>
						<div class="row">
							<div class="form-group">
								<div class="col-md-6">
									<label for="radio_dia2">Por Día</label>&nbsp;
									<input type="radio" id="radio_dia2" name="filtro_radio2" value="filtrodia2" checked="checked">&nbsp;
									<label for="radio_intervalo2">Por intervalo</label>&nbsp;	
									<input type="radio" id="radio_intervalo" name="filtro_radio2" value="filtrointervalo2">
								</div>
							</div>
						</div>

						<br>
						
						<div class="row filtro2 filtrodia2">
							<div class="form-group">
								<label for="dia2" class="col-md-2">Por Día: </label>
								<div class="col-md-4">
									<input id="dia2" name="dia2" type="text" class="form-control" value="<?= $desde; ?>"/>
								</div>
								<div class="col-md-4">
									<button type="button" class="btn btn-primary boton-filtrar2 glyphicon glyphicon-leaf"> Filtrar</button>
								</div>
							</div>
						</div>
						
						<div class="filtro2 filtrointervalo2">
							<div class="row">
								<div class="form-group">
									<label for="desde2" class="col-md-2">Desde: </label>
									<div class="col-md-4">
										<input id="desde2" name="desde2" type="text" class="form-control" />
									</div>
								</div>
							</div>

							<br>

							<div class="row">
								<div class="form-group">
									<label for="hasta2" class="col-md-2">Hasta: </label>
									<div class="col-md-4">
										<input id="hasta2" name="hasta2" type="text" class="form-control" />
									</div>
									<div class="col-md-4">
										<button type="button" class="btn btn-primary boton-filtrar2 glyphicon glyphicon-leaf"> Filtrar</button>
									</div>
								</div>
							</div>
						</div>
						<?= form_close(); ?>
					</fieldset>
				</div>

				<br>
				
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Facultades </th>
							<th> Anulados </th>
							<th> Impresos </th>
							<th> Consumidos </th>
							<th> Total de Tickets </th>
						</tr>
					</thead>
					<tbody id="resultado_tickets_tabla2">
						<?php foreach ($registros3 as $registro3): ?>
						<tr>
							<td><?= $registro3->facultad; ?></td>
							<td><?= $registro3->anulados; ?></td>
							<td><?= $registro3->impresos; ?></td>
							<td><?= $registro3->consumidos; ?></td>
							<td><?= $registro3->total_tickets; ?></td>
						</tr>
						<?php endforeach; ?>
						<tr class="info">
							<td>Totales: </td>
							<td><?= $totales3->anulados; ?></td>
							<td><?= $totales3->activos; ?></td>
							<td><?= $totales3->impresos; ?></td>
							<td><?= $totales3->consumidos; ?></td>
							<td><?= $totales3->total_tickets; ?></td>
						</tr>
					</tbody>
				</table>
				
				<div class="col-md-offset-9">
					<input form="form_reporte2" type="submit" class="btn btn-primary glyphicon glyphicon-download" value=" Descargar PDF" name="PDF3">
				</div>
			</div>
			<!-- Tabla para el cuarto reporte -->
			<div role="tabpanel" class="tab-pane" id="informe4">
				<br>
				<div class="filtros">
					<fieldset>
						<?= form_open('reportes/generar_pdf', array('id'=>'form_reporte3')); ?>
							<div class="row">
								<div class="form-group">
									<label for="mes" class="col-md-1">Meses: </label>
									<div class="col-md-4">
										<?= form_dropdown('mes', $meses, 12, "id='drop_down' class='form-control'"); ?>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="form-group">
									<label for="fecha" class="col-md-1">Fecha: </label>
									<div class="col-md-4">
										<div class="input-group">
											<input id="fecha" name="fecha" type="text" class="form-control" placeholder="Filtrar por fecha" />
											<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
										</div>
									</div>
								</div>
							</div>
						<?= form_close(); ?>
					</fieldset>
				</div>

				<br>
				<div class="tablas-propias">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Días </th>
							<th> Consumidos </th>
							<th> No Consumidos </th>
							<th> Anulados </th>
							<th> Ausentismos </th>
						</tr>
					</thead>
					<tbody id="resultado_tickets_tabla4">
						<?php foreach ($registros4 as $registro4): ?>
						<tr>
							<td><?= date("d/m/Y",strtotime($registro4->fecha)); ?></td>
							<td><?= $registro4->consumidos; ?></td>
							<td><?= ($registro4->impresos + $registro4->vencidos); ?></td>
							<td><?= $registro4->anulados; ?></td>
							<td><?= anchor('reportes/ausentismos/'.date("d-m-Y",strtotime($registro4->fecha)), ' ', array('class' => 'glyphicon glyphicon-search')); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				</div>
				<br>
				<div class="col-md-offset-9">
					<input form="form_reporte3" type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF4">
				</div>
			</div>
			<!-- Tabla para el quinto reporte -->
			<div role="tabpanel" class="tab-pane" id="informe5">
				<br>
				<div class="tablas-propias">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Cantidad de Ausentismos </th>
							<th> Cantidad de Personas </th>
							<th> Detalle </th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($registros5 as $registro5): ?>
						<tr>
							<td><?= $registro5->cantidad_ausentismos; ?></td>
							<td><?= $registro5->cantidad_personas; ?></td>
							<td><?= anchor('reportes/detalle_ranking/'.$registro5->cantidad_ausentismos, ' ', array('class' => 'glyphicon glyphicon-search')); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				</div>
				<br>
				<div class="col-md-offset-9">
					<?= form_open('reportes/generar_pdf', array('id'=>'form_reporte6')); ?>
						<input form="form_reporte6" type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF6">
					<?= form_close(); ?>
				</div>
			</div>
			<!-- Tabla para el sexto reporte -->
			<div role="tabpanel" class="tab-pane" id="informe6">
				<br>
				<div class="tablas-propias">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Dni </th>
							<th> L.u </th>
							<th> Facultad </th>
							<th> Categoria </th>
							<th> Saldo </th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($registros6 as $registro6): ?>
						<tr>
							<td><?= $registro6->dni; ?></td>
							<td><?= $registro6->lu; ?></td>
							<td><?= $registro6->facultad; ?></td>
							<td><?= $registro6->categoria; ?></td>
							<td><?= $registro6->saldo; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				</div>
				<br>
				<div id="paginacion">
					<input id="numeroPaginas" type="hidden" value='<?= $numeroPaginas; ?>'>
				</div>
				<br>
				<div class="col-md-offset-9">
					<?= form_open('reportes/generar_pdf', array('id'=>'form_reporte7')); ?>
						<input form="form_reporte7" type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF8">
					<?= form_close(); ?>
				</div>
			</div>
		</div><!-- tab content -->
	</div>
</div>
<script type="text/javascript">
	base_url = '<?=base_url(); ?>';
</script>
<script src="<?= base_url('js/reportes.js'); ?>"></script>