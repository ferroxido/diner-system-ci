<div class="jumbotron">
	<div class="row">
		<div class="col-md-8">
			<h2>Calendario académico</h2>
		</div>
		<div class="col-md-4">
			
		</div>
	</div>
	<script type="text/javascript">
		base_url = '<?=base_url(); ?>';
	</script>
	<script src="<?= base_url('js/pruebaslocas.js'); ?>"></script>
	<div id="contenedor_calendario" class="row">
		<?php foreach ($calendario as $cal): ?>
			<div class="meses">
				<?= $cal; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<!--
	<div class="row">
		<div class="col-md-7">
			<?= $calendario; ?>
			<script type="text/javascript">
				base_url = '<?=base_url(); ?>';
			</script>
			<script src="<?= base_url('js/calendario.js'); ?>"></script>
			<br>
			<?= anchor('calendario/index', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left')); ?>
		</div>
		<div class="col-md-5">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Panel info</h3>
				</div>
				<div class="panel-body">
					<?= form_open('index.php/calendario/actualizar', array('id'=>'resultadoCalendario')); ?>
					<?= form_close(); ?>
					<br>
					<span id="mensaje"></span>
				</div>
			</div>
		</div>
	</div>
	-->
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>        	
	        	<h3 id="encabezado_modal"></h3>
	      	</div>
	      	<div id="cuerpo_modal" class="modal-body">
			<?= form_open('calendario/actualizar', array('id'=>'resultado_calendario', 'class'=>'form-horizontal')); ?>
				
				<div class="row">
					<div class="form-group">
						<div id="fecha_dia" class="col-md-4">
							
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<?= form_label('Tickets totales: ', 'totales', array('class'=>'col-md-offset-2 col-md-3 control-label')); ?>
						<div id="tickets_totales" class="col-md-4">

						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<?= form_label('Tickets Vendidos: ', 'vendidos', array('class'=>'col-md-offset-2 col-md-3 control-label')); ?>
						<div id="tickets_vendidos" class="col-md-4">
							
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<?= form_label('Evento: ', 'evento', array('class'=>'col-md-offset-2 col-md-3 control-label')); ?>
						<div id="textarea_evento" class="col-md-4">
							
						</div>
					</div>
				</div>	
				
				<!--
				<div class="row">
					<div class="form-group">
			      		<label class="col-md-offset-2 col-md-3 control-label">Marcar como: </label>
			      		<div id="radio_button_estado" class="col-md-4">

			      		</div>
					</div>
				</div>
				-->
				<div class="form-group">
					<div id="boton_actualizar" class="col-sm-offset-8">

					</div>
				</div>
				
			<?= form_close(); ?>
	      	</div>
	      	<div id="pie_modal" class="modal-footer">
				
	      	</div>
    	</div>
  	</div>
</div>