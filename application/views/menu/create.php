<?= form_open('menu/insert', array('class'=>'form-horizontal')); ?>
	<legend>Agregando un Registro</legend>

	<?= my_validation_errors(validation_errors()); ?>

	<div class="row">
		<div class="form-group">
			<?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre','value'=>set_value('nombre'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?= form_label('Orden: ', 'orden', array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?= form_input(array('class'=>'form-control','type'=>'number', 'name'=>'orden', 'id'=>'orden','value'=>set_value('orden'))); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
      		<label class="col-md-2 control-label">Marcar como: </label>
      		<div class="col-md-4">
        		<div class="radio">
          			<label>
            		<input name="estado" id="visible" value="1" type="radio" checked>
            		Visible
          			</label>
        		</div>
        		<div class="radio">
          			<label>
           	 		<input name="estado" id="novisible" value="0" type="radio">
            		No Visible
          			</label>
        		</div>
      		</div>
		</div>
	</div>

	<hr>

	<div class="form-group">
		<div class="col-md-offset-2">
			<?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
			<?= anchor('menu/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
		</div>
	</div>
<?= form_close(); ?>