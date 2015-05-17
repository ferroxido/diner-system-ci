<?= form_open('configuracion/generando_clave', array('class'=>'form-horizontal jumbotron')); ?>
	<legend>Generar Nueva Clave de Seguridad</legend>

	<?= my_validation_errors(validation_errors()); ?>
	<?= my_mensaje_confirmacion($mensaje, $mostrar_mensaje, $exito)?>

	<div class="row">
		<div class="form-group">
			<?= form_label('DNI: ', 'dni', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'dni', 'id'=>'dni','value'=>set_value('dni'))); ?>
			</div>
		</div>
	</div>

	<hr>

	<div class="form-group">
		<div class="col-sm-offset-2">
			<div class="col-md-6">
				<?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
				<?= anchor('usuarios/admin', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
			</div>
		</div>
	</div>
<?= form_close(); ?>