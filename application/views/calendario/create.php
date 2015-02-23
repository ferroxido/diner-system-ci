<?= form_open('calendario/feriados', array('class'=>'form-horizontal jumbotron')); ?>
	<legend>Agregando un nuevo calendario Académico</legend>
	<h4>Paso 1: Ingrese una descripción e indique un Inicio y un Final para su calendario</h4>

	<?= my_validation_errors(validation_errors()); ?>
	
	<div class="row">
		<div class="form-group">
			<?= form_label('Descripcion: ', 'descripcion', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'data-toggle'=>'popover','data-trigger'=>'focus','title'=>'Ejemplos de descripciones:','data-content'=>'Calendario académico 2015 ó febrero-agosto 2016','id'=>'descripcion','value'=>set_value('descripcion'))); ?>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Desde: ', 'desde', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'desde', 'id'=>'desde','value'=>set_value('desde'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Hasta: ', 'hasta', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'hasta', 'id'=>'hasta','value'=>set_value('hasta'))); ?>
			</div>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="form-group">
			<div class="col-md-offset-3">
				<div class="col-md-6">
					<?= form_button(array('type'=>'submit', 'content'=>' Siguiente', 'class'=>'btn btn-success glyphicon glyphicon-arrow-right')); ?>

					<?= anchor('calendario/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
				</div>
			</div>
		</div>	
	</div>
<?= form_close(); ?>

<script type="text/javascript">
	base_url = '<?=base_url(); ?>';
	$('#descripcion').popover();
</script>
<script src="<?= base_url('js/calendario.js'); ?>"></script>