<div id="mensaje">
    <?= my_validation_errors($mensaje); ?>
</div>

<?= form_open_multipart('imports/import', array()); ?>
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" name="userfile" id="exampleInputFile">
    <p class="help-block">Solo se puede importar archivos de tipo csv.</p>
  </div>
  <button type="submit" class="btn btn-primary">Importar</button>
<?= form_close(); ?>

<div class="mimodal"></div>
<h2>Archivos</h2>

<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th> Nombre Archivo </th>
            <th> Tamaño </th>
            <th> Fecha </th>
            <th> Procesar </th>
        </tr>
    </thead>

    <tbody id="destino_resultado">
        <?php foreach ($files_imported_data as $data): ?>
        <tr>
            <td><?= $data['name']; ?></td>
            <td><?= $data['size'].' bytes'; ?></td>
            <td><?= date("d/m/Y H:i:s",$data['date']); ?></td>
            <td><?= anchor('imports/procesar/'.$data['name'], ' ', array('class' => 'glyphicon glyphicon-play-circle procesar')); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script src="<?= base_url('js/imports.js')?>"></script>
