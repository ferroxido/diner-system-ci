<?= form_open('usuarios/insert', array('class'=>'form-horizontal')); ?>
	<legend>Editando un Registro</legend>

	<?= my_validation_errors(validation_errors()); ?>

	<div class="row">
		<div class="form-group">
			<?= form_label('DNI: ', 'dni', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'dni', 'id'=>'dni', 'placeholder'=>'Número de documento', 'value'=>set_value('dni'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Nombre completo: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre', 'placeholder'=>'Nombre Completo', 'value'=>set_value('nombre'))); ?>		
			</div>	
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Email: ', 'email', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'email', 'name'=>'email', 'id'=>'email', 'placeholder'=>'Email', 'value'=>set_value('email'))); ?>
			</div>	
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Libreta Universitaria: ', 'lu', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'lu', 'id'=>'lu', 'placeholder'=>'Libreta Universitaria', 'value'=>set_value('lu'))); ?>		
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

	<div class="row">
		<div class="form-group">
			<?= form_label('Perfil: ', 'id_perfil', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_dropdown('id_perfil', $perfiles, 4,"class='form-control'"); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Categoría: ', 'id_categoria', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_dropdown('id_categoria', $categorias, 2,"class='form-control'"); ?>
			</div>
		</div>
	</div>	

	<hr>

	<div class="form-group">
		<div class="col-sm-offset-3">
			<?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
			<?= anchor('usuarios/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
		</div>
	</div>

<?= form_close(); ?>