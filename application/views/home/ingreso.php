<?= form_open('home/ingresar', array('class'=>'form-horizontal')); ?>
	<legend>Ingreso al sistema</legend>

	<?= my_validation_errors(validation_errors()); ?>
	<?= my_mensaje_confirmacion($mensaje, $mostrar_mensaje, $exito)?>

	<div class="row">
		<div class="form-group">
			<?= form_label('Usuario: ', 'dni', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'dni', 'id'=>'dni', 'placeholder'=>'Tu usuario (DNI)', 'value'=>set_value('dni'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Contraseña: ', 'label_password', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'password', 'name'=>'password', 'id'=>'password', 'placeholder'=>'Tu contraseña', 'value'=>set_value('password'))); ?>
			</div>	
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-offset-3">
			<?= anchor('home/recordar_clave', '¿Has olvidado tu contraseña?'); ?>	
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="form-group">
			<div class="col-md-offset-3">
				<div class="col-md-7 col-xs-12">
					<?= form_button(array('type'=>'submit', 'content'=>' Ingresar', 'class'=>'btn btn-primary glyphicon glyphicon-log-in')); ?>
			
					<?= anchor('home/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
				</div>	
			</div>
		</div>
	</div>
<?= form_close(); ?>