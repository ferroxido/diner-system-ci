<h2>Sistema de gestión de tickets del comedor universitario.</h2>
<div id="info_maquinas">
	<legend>Terminales de Compra</legend>
	<div class="row">
		<?php foreach ($maquinas as $maquina): ?>
		<div class="col-md-4">
			<div class="info-maquinas">
			<label for="">Terminal <?= $maquina->id." ".$maquina->facultad?></label>
			<label for="">Estado: <?= $maquina->estado?></label>
			<?php 
				if ($maquina->estado_num == 1 || $maquina->estado_num == 4) {
					echo '<span class="glyphicon glyphicon-ok text-success"></span>';
				}else{
					echo '<span class="glyphicon glyphicon-remove text-danger"></span>';
				}
			?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<h4>Mi sabiduría viene de esta tierra</h4>
