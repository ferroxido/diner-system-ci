<?= form_open('tipos_operaciones/insert', array('class'=>'form-horizontal')); ?>
	<legend>Agregando un Registro</legend>

	<?= my_validation_errors(validation_errors()); ?>

	<div class="row">
		<div class="form-group">
			<?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre','value'=>set_value('nombre'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Controlador: ', 'controlador', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'controlador', 'id'=>'controlador','value'=>set_value('controlador'))); ?>
			</div>			
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Acción: ', 'accion', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'accion', 'id'=>'accion','value'=>set_value('accion'))); ?>
			</div>		
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Pertenece al Menu: ', 'id_menu', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_dropdown('id_menu', $menu, 0, "class='form-control'"); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Estado: ', 'estado', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'number', 'name'=>'estado', 'id'=>'estado')); ?>
			</div>
		</div>
	</div>

	<hr>

	<div class="form-group">
		<div class="col-md-offset-2">
			<div class="col-md-6">
				<?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
				<?= anchor('tipos_operaciones/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
			</div>
		</div>
	</div>
<?= form_close(); ?>