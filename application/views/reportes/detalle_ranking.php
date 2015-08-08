<div class="page-header">   
    <h1> Alumnos <small>con <?= $cantidadAusentismo; ?> aunsentes</small></h1>
</div>
<div class="row">
    <a href="javascript:window.history.go(-1);" class="btn btn-primary glyphicon glyphicon-arrow-left"> Volver</a>
</div>

<br>

<div class="tabla-scroll">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th> Nombre Usuario </th>
                <th> DNI </th>
                <th> Libreta </th>
                <th> Facultad </th>
                <th> Categoría </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($registros as $registro): ?>
            <tr>
                <td><?= $registro->nombre; ?></td>
                <td><?= $registro->dni; ?></td>
                <td><?= $registro->lu; ?></td>
                <td><?= $registro->facultad; ?></td>
                <td><?= $registro->categoria; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<br>
<div class="col-md-offset-9">
    <?= form_open('reportes/generar_pdf'); ?>
        <input name="cantidad_ausentismo" type="hidden" value="<?= $cantidadAusentismo; ?>">
        <input type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF7">
    <?= form_close(); ?>
</div>