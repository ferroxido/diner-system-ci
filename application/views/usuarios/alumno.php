<div class="col-md-6">
	<div id="botonera" class="jumbotron">
		<legend>Acciones Disponibles</legend>
		<div class="par-botones">
			<input class="boton-disabled" type="button" value="Comprar" style="background-image: url('../img/ticket.png');" onclick="location.href='<?php echo base_url();?>usuarios/comprar_tickets'" disabled>
			<input class="boton-disabled" type="button" value="Anular" style="background-image: url('../img/anular.png');" onclick="location.href='<?php echo base_url();?>usuarios/anular'" disabled>
		</div>
		<div class="par-botones">
			<input class="boton-disabled" type="button" value="Imprimir" style="background-image: url('../img/printer.png');" onclick="location.href='<?php echo base_url();?>usuarios/imprimir'" disabled>
			<input type="button" value="Editar Perfil" style="background-image: url('../img/editar.png');" onclick="location.href='<?php echo base_url();?>usuarios/editar_perfil'">
		</div>
	</div>
</div>