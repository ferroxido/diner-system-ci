<h2>Sistema de gestión de tickets del comedor universitario.</h2>
<div id="info_maquinas">
	<legend>Terminales de Compra</legend>
	<div class="row">
		<?php foreach ($maquinas as $maquina): ?>
		<div class="col-md-4">
			<?php
				$className = "";
				switch ($maquina->estado_num) {
					case 1:
						$className = "verde";
						break;
					
					case 4:
						$className = "amarillo";
						break;

					default:
						$className = "rojo";
						break;
				}
			?>
			<div class="info-maquinas <?= $className?>">
				<label>Terminal <?= $maquina->id." ".$maquina->facultad?></label>
				<label>Estado: <?= $maquina->estado?></label>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<h4>Mi sabiduría viene de esta tierra</h4>
