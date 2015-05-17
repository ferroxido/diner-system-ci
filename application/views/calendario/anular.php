<?= form_open('calendario/anulando', array('class'=>'form-horizontal')); ?>
	<legend>Anular todos los tickets del día <?= $fecha;?></legend>

	<?= my_validation_errors(validation_errors()); ?>

	<div class="row">
		<div class="form-group">
			<?= form_label('Clave: ', 'clave', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'password', 'name'=>'clave', 'id'=>'clave','value'=>set_value('clave'))); ?>
				<?= form_hidden('fecha', $fecha); ?>
			</div>
		</div>
	</div>

	<hr>

	<div class="form-group">
		<div class="col-sm-offset-2">
			<div class="col-md-6">
				<?= form_button(array('type'=>'submit', 'content'=>' Confirmar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
				<?= anchor('calendario/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
			</div>
		</div>
	</div>
<?= form_close(); ?>