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
                                <td><?= $day->entrada; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td>Principal</td>
                            <?php foreach ($days_of_week as $day): ?>
                                <td><?= $day->entrada; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td>Postre</td>
                            <?php foreach ($days_of_week as $day): ?>
                                <td><?= $day->entrada; ?></td>
                            <?php endforeach; ?>
                        </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<br>
<div id="botones-agregar-comidas">
    <a class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#entrada">Agregar Entrada</a>
    <a href="#" class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#principal">Agregar Principal</a>
    <a href="#" class="btn btn-primary glyphicon glyphicon-plus" data-toggle="modal" data-target="#postre">Agregar Postre</a>
</div>
<!-- Modal -->
<div class="modal fade" id="entrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/insert', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Entrada</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-4">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre')); ?>
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

<!-- Modal -->
<div class="modal fade" id="principal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/insert', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Plato Principal</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-4">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre')); ?>
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

<!-- Modal -->
<div class="modal fade" id="postre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            
        </div>
        <div class="modal-body">
            <?= form_open('almuerzos/insert', array('class'=>'form-horizontal')); ?>
                <legend>Agregar Postre</legend>

                <?= my_validation_errors(validation_errors()); ?>

                <div class="row">
                    <div class="form-group">
                        <?= form_label('Nombre: ', 'nombre', array('class'=>'col-md-3 control-label')); ?>
                        <div class="col-md-4">
                            <?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'nombre', 'id'=>'nombre')); ?>
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