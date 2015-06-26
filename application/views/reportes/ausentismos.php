<div class="page-header">   
    <h1> Ausentes <small>del <?= $fecha; ?></small></h1>
</div>
<div class="row">    
    <div id="paginacion">
        <input id="numeroPaginas" type="hidden" value='<?= $numeroPaginas; ?>'>
    </div>
    
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

        <tbody id="destino_resultado">
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
        <input name="fecha" type="hidden" value="<?= $fecha; ?>">
        <input type="submit" class="btn btn-primary glyphicon glyphicon-print" value=" Descargar PDF" name="PDF5">
    <?= form_close(); ?>
</div>
<script type="text/javascript" src="<?= base_url('js/jquery.bootpag.min.js'); ?>"></script>
<script type="text/javascript">
    base_url = '<?=base_url(); ?>';
</script>
<script src="<?= base_url('js/ausentismos.js'); ?>"></script>