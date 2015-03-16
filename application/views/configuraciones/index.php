<?= form_open('configuracion/update', array('class'=>'form-horizontal jumbotron')); ?>
	<legend>Datos de Configuración del sistema del comedor</legend>

	<?= my_validation_errors(validation_errors()); ?>

	<div class="row">
		<div class="form-group">
			<?= form_label('Email: ', 'email', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-8">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'email', 'id'=>'email','value'=>$registro->email)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Password: ', 'password', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-8">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'password', 'id'=>'password','value'=>$registro->password)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Puerto: ', 'puerto', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'puerto', 'id'=>'puerto','value'=>$registro->puerto)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Mensaje Email: ', 'mensaje_email', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-8">
				<textarea name="mensaje_email" id="mensaje_email" cols="6" rows="4" class="form-control"><?= $registro->mensaje_email; ?></textarea>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Smtp: ', 'smtp', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-8">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'smtp', 'id'=>'smtp','value'=>$registro->smtp)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Asunto: ', 'asunto', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-8">
				<textarea name="asunto" id="asunto" cols="6" rows="2" class="form-control"><?= $registro->asunto; ?></textarea>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Hora de Anulación: ', 'hora_anulacion', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'hora_anulacion', 'id'=>'hora_anulacion','value'=>$registro->hora_anulacion)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Hora de Compra: ', 'hora_compra', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'hora_compra', 'id'=>'hora_compra','value'=>$registro->hora_compra)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Saldo Máximo: ', 'saldo_maximo', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'saldo_maximo', 'id'=>'saldo_maximo','value'=>$registro->saldo_maximo)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Tiempo de Session: ', 'session_time', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'session_time', 'id'=>'session_time','value'=>$registro->session_time)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Máxima Longitud de Password: ', 'max_length_pass', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'max_length_pass', 'id'=>'max_length_pass','value'=>$registro->max_length_pass)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Máxima Longitud de Nombre: ', 'max_length_nombre', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'max_length_nombre', 'id'=>'max_length_nombre','value'=>$registro->max_length_nombre)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Máxima Longitud de DNI: ', 'max_length_dni', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'max_length_dni', 'id'=>'max_length_dni','value'=>$registro->max_length_dni)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Máxima Longitud de L.U: ', 'max_length_lu', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'max_length_lu', 'id'=>'max_length_lu','value'=>$registro->max_length_lu)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Máxima Longitud de Email: ', 'max_length_mail', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-3">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'max_length_mail', 'id'=>'max_length_mail','value'=>$registro->max_length_mail)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Caracteres Permitidos: ', 'caracteres_permitidos', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-8">
				<textarea name="caracteres_permitidos" id="caracteres_permitidos" cols="8" rows="3" class="form-control"><?= $registro->caracteres_permitidos; ?></textarea>
				
			</div>
		</div>
	</div>

	<hr>

	<div class="form-group">
		<div class="col-sm-offset-2">
			<?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
			<?= anchor('usuarios/admin', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
		</div>
	</div>
<?= form_close(); ?>