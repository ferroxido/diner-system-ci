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
	<script src="<?= base_url('js/detalle-calendario.js'); ?>"></script>
	<div id="contenedor_calendario" class="row">
		<?php foreach ($calendario as $cal): ?>
			<div class="meses">
				<?= $cal; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>        	
	        	<h3 id="encabezado_modal"></h3>
	      	</div>
	      	<div class="modal-body">
			<?= form_open('calendario/actualizar', array('id'=>'form_modal', 'class'=>'form-horizontal')); ?>
			<?= form_close(); ?>
	      	</div>
	      	<div class="modal-footer">
				<div class="row">
					<h4 id="mensaje_exito" class="bg-primary"></h4>
				</div>
	      	</div>
    	</div>
  	</div>
</div>
