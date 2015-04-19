<?= form_open('home/registrarse', array('class'=>'form-horizontal jumbotron')); ?>
	<legend>Registro al sistema</legend>

	<?= my_validation_errors(validation_errors()); ?>

	<div class="row">
		<div class="form-group">
			<?= form_label('DNI: ', 'dni', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'dni', 'id'=>'dni', 'placeholder'=>'Tu número de documento', 'value'=>set_value('dni'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Nombre completo: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre', 'placeholder'=>'Tu Nombre', 'value'=>set_value('nombre'))); ?>		
			</div>	
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Email: ', 'email', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'email', 'name'=>'email', 'id'=>'email', 'placeholder'=>'Tu dirección de correo', 'value'=>set_value('email'))); ?>
			</div>	
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Libreta Universitaria: ', 'lu', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'lu', 'id'=>'lu', 'placeholder'=>'Tu libreta Universitaria', 'value'=>set_value('lu'))); ?>		
			</div>	
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Provincia: ', 'id_provincia', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_dropdown('id_provincia', $provincias, 15, "class='form-control'"); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Facultad: ', 'id_facultad', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_dropdown('id_facultad', $facultades, 6, "class='form-control'"); ?>
			</div>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="form-group">
			<div class="col-md-offset-2">
				<div class="col-md-6">
					<?= form_button(array('type'=>'submit', 'content'=>' Confirmar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
				
					<?= anchor('home/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
				</div>
			</div>
		</div>
	</div>


<?= form_close(); ?>