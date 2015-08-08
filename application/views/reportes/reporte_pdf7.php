<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css/estilos-pdf.css">
</head>
<body>
    <header>
        <div id="img-unsa-logo">
            <img src="./img/logomejorado.png">
        </div>
        <div id="info-encabezado">
            <h3>Universidad Nacional de Salta</h3>
            <h3>Comedor Universitario</h3>
            <h5>Avda. Bolivia 5150-Salta-4400</h5>
            <h5>Tel. 54-0387-425521</h5>
            <h5>Correo Electrónico: seccosu@unsa.edu.ar</h5>
        </div>
    </header>
    <hr>

    <h3> Alumnos con <?= $cantidadAusentismo; ?> aunsentes</h3>
    <br>
    <div id="footer">
        <p><?= date('d-m-Y H:i'); ?> - <?= $this->session->userdata('nombre_usuario'); ?></p>
    </div>

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
    <div id="blanco">
        
    </div>
</body>
</html>