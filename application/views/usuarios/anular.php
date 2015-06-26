<div class="col-md-8">
	<div id="tickets_anulables">
		<legend>Tickets Disponibles</legend>
		
		<?= $mensaje; ?>
		
		<div class="tablas-propias">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th> Número </th>
						<th> Fecha </th>
						<th> Importe </th>
						<th> Anular </th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($tickets as $ticket): ?>
					<tr>
						<td><?= $ticket->id_ticket; ?></td>
						<td><?= date("d/m/Y",strtotime($ticket->fecha)); ?></td>
						<td><?= $ticket->importe; ?></td>
						<td><?= anchor('usuarios/anulando/'.$ticket->id_ticket, ' ',array('class'=>'glyphicon glyphicon-remove confirmLink')); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div id="dialog" title="Confirmación para anular">
				¿Está seguro de anular su ticket?
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-offset-9">
				<?= anchor('usuarios/alumno', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left')); ?>
			</div>
		</div>
	</div>
</div>
<style>
	.ui-dialog-titlebar {
		background: #DD4814;
	}
</style>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dialog").dialog({
   		autoOpen: false,
    	modal: true,
    	resizable: false
    });
  });

  $(".confirmLink").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog").dialog({
      buttons : {
        "Confirmar" : function() {
        	//$( this ).dialog( "close" );
        	window.location.href = targetUrl;
        },
        "Cancelar" : function() {
        	$(this).dialog("close");

        }
      }
    });

    $("#dialog").dialog("open");
  });
</script>