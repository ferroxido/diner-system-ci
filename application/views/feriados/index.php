<div class="page-header">	
	<h1>Feriados Nacionales <small>que se rigen por el Decreto 1584/2010</small></h1>
</div>

<?= form_open('feriados/search', array('class' => 'form-search')); ?>
	<div class="row" style="margin:1em 0;">
		<div class="col-md-4">
			<div class="input-group">
	        <input type="text" class="form-control" placeholder="Buscar por nombre" name="buscar" id="buscar">
	        <div class="input-group-btn">
	            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>  
	        </div>
	    </div>
		</div>
		<div class="col-md-4">
			<div class="input-group">
	       		<?= my_boton_permisos('feriados/create', ' Agregar' ,'btn btn-primary glyphicon glyphicon-plus'); ?>
	    	</div>
		</div>
	</div>
<?= form_close(); ?>

<div class="tablas-propias">
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th> ID </th>
			<th> Descripción </th>
			<th> Fecha </th>
			<th> Creado </th>
			<th> Modificado </th>
			<th> Editar </th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($registros as $registro): ?>
		<tr>
			<td><?= $registro->id; ?></td>
			<td><?= $registro->descripcion; ?></td>
			<td><?= date("d/m/Y",strtotime($registro->fecha)); ?></td>
			<td><?= date("d/m/Y - H:i",strtotime($registro->created)); ?></td>
			<td><?= date("d/m/Y - H:i",strtotime($registro->updated)); ?></td>
			<td><a class="glyphicon glyphicon-pencil" data-toggle="modal" data-target="<?= "#myModal".$registro->id ?>"></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>

<?php foreach ($registros as $registro): ?>
<!-- Modal -->
<div class="modal fade" id="<?= "myModal".$registro->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        	
      	</div>
      	<div class="modal-body">
      		<?= form_open('feriados/update', array('class'=>'form-horizontal')); ?>
				<legend>Editando feriado</legend>

				<?= my_validation_errors(validation_errors()); ?>

				<div class="row">
					<div class="form-group">
						<?= form_label('ID: ', 'id', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-4">
							<input class="form-control" type="text" name="id" value="<?= $registro->id; ?>" disabled/>
						</div>
						<?= form_hidden('id', $registro->id); ?>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<?= form_label('Descripción: ', 'descripcion', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-4">
							<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion','value'=>$registro->descripcion)); ?>
						</div>	
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<?= form_label('Fecha: ', 'fecha', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-4">
							<?= form_input(array('class'=>'form-control fecha','type'=>'text', 'name'=>'fecha','value'=> date("d/m/Y",strtotime($registro->fecha)) )); ?>
						</div>	
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<?= form_label('Creado: ', 'created', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-4">
							<input class="form-control" type="text" name="created" value="<?= date("d/m/Y - H:i",strtotime($registro->created)); ?>" disabled/>
						</div>	
						<?= form_hidden('created', $registro->created); ?>
					</div>
				</div>
		
				<div class="row">
					<div class="form-group">
						<?= form_label('Actualizado: ', 'updated', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-4">
							<input class="form-control" type="text" name="updated" value="<?= date("d/m/Y - H:i",strtotime($registro->updated)); ?>" disabled/>
						</div>		
						<?= form_hidden('updated', $registro->updated); ?>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<div class="col-md-offset-2">
						<div class="col-md-10">
							<?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
							<?= anchor('feriados/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
							<?= anchor('feriados/delete/'.$registro->id, ' Eliminar',array('class'=>'btn btn-danger glyphicon glyphicon-trash', 'onClick'=>"return confirm('¿Estas Seguro?');")); ?>
						</div>				
					</div>
				</div>

			<?= form_close(); ?>
      	</div>
      	<div class="modal-footer">
			
      	</div>
    	</div>
  	</div>
</div>
<?php endforeach; ?>

<script src="<?= base_url('js/feriados.js'); ?>"></script>