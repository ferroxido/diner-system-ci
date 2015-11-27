<div class="page-header">
    <h1>Almuerzos <small>para los próximos días</small></h1>
</div>
<div id="almuerzos">
    <div id="paginador-semanas" class="row">
        <a href=""><i class="glyphicon glyphicon-arrow-left"></i><h4>Prev</h4></a><h4>Semana  </h4><a href=""><h4>Next</h4><i class="glyphicon glyphicon-arrow-right"></i></a>
    </div>
    <div class="row">
        <form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>Entrada</td>
                            <?php foreach ($days_of_week as $day): ?>
                                <td><?= isset($day->entrada)?$day->entrada:'Falta cargar'; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td>Principal</td>
                            <?php foreach ($days_of_week as $day): ?>
                                <td><?= isset($day->entrada)?$day->entrada:'Falta cargar'; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td>Postre</td>
                            <?php foreach ($days_of_week as $day): ?>
                                <td><?= isset($day->entrada)?$day->entrada:'Falta cargar'; ?></td>
                            <?php endforeach; ?>
                        </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-4">
        <h3>Entradas</h3>
        <a class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#entrada">Agregar</a>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entradas as $entrada): ?>
                <tr>
                    <td><?= $entrada->id; ?></td>
                    <td><?= $entrada->descripcion; ?></td>
                    <td><a href="" class="glyphicon glyphicon-pencil" data-toggle="modal" data-target="<?= "#entradas".$entrada->id ?>"></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h3>Platos principales</h3>
        <a class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#principal">Agregar</a>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($principales as $principal): ?>
                <tr>
                    <td><?= $principal->id; ?></td>
                    <td><?= $principal->descripcion; ?></td>
                    <td><a href="" class="glyphicon glyphicon-pencil" data-toggle="modal" data-target="<?= "#principales".$principal->id ?>"></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h3>Postres</h3>
        <a class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#postre">Agregar</a>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postres as $postre): ?>
                <tr>
                    <td><?= $postre->id; ?></td>
                    <td><?= $postre->descripcion; ?></td>
                    <td><a href="" class="glyphicon glyphicon-pencil" data-toggle="modal" data-target="<?= "#postres".$postre->id ?>"></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modalales para agregar nuevas comidas -->
<div class="modal fade" id="entrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/insert_food/entradas', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Entrada</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'id'=>'nombre')); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-offset-2">
                        <div class="col-md-10">
                            <?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
                            <?= anchor('almuerzos/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
                        </div>
                    </div>
                </div>

            <?= form_close(); ?>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="principal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/insert_food/platos_principales', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Plato Principal</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'id'=>'nombre')); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-offset-2">
                        <div class="col-md-10">
                            <?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
                            <?= anchor('almuerzos/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
                        </div>
                    </div>
                </div>

            <?= form_close(); ?>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="postre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/insert_food/postres', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Postre</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'id'=>'nombre')); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-offset-2">
                        <div class="col-md-10">
                            <?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
                            <?= anchor('almuerzos/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
                        </div>
                    </div>
                </div>

            <?= form_close(); ?>
        </div>
        </div>
    </div>
</div>

<!-- Modalales para agregar editar comidas -->
<?php foreach ($entradas as $entrada): ?>
<div class="modal fade" id="<?= "entradas".$entrada->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/update_food/entradas', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Postre</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <?= form_hidden('id', $entrada->id); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'id'=>'nombre', 'value'=>$entrada->descripcion)); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-offset-2">
                        <div class="col-md-10">
                            <?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
                            <?= anchor('almuerzos/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
                        </div>
                    </div>
                </div>

            <?= form_close(); ?>
        </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modalales para agregar editar comidas -->
<?php foreach ($principales as $principal): ?>
<div class="modal fade" id="<?= "principales".$principal->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/update_food/platos_principales', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Postre</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <?= form_hidden('id', $principal->id); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'id'=>'nombre', 'value'=>$principal->descripcion)); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-offset-2">
                        <div class="col-md-10">
                            <?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
                            <?= anchor('almuerzos/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
                        </div>
                    </div>
                </div>

            <?= form_close(); ?>
        </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modalales para agregar editar comidas -->
<?php foreach ($postres as $postre): ?>
<div class="modal fade" id="<?= "postres".$postre->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/update_food/postres', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Postre</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <?= form_hidden('id', $postre->id); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'descripcion', 'id'=>'nombre', 'value'=>$postre->descripcion)); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-offset-2">
                        <div class="col-md-10">
                            <?= form_button(array('type'=>'submit', 'content'=>' Aceptar', 'class'=>'btn btn-success glyphicon glyphicon-ok')); ?>
                            <?= anchor('almuerzos/index', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove')); ?>
                        </div>
                    </div>
                </div>

            <?= form_close(); ?>
        </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
