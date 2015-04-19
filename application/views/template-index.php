<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Comedor</title>
    <link href="<?= base_url('css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('css/estilos.css'); ?>" rel="stylesheet" />
    
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <script src="<?= base_url('js/jquery.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
   
</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top">
	    <div class="container">
	        <div class="navbar-header">
	          	<a class="navbar-brand" href="#">ComedorUNSa</a>
	          	<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
	            	<span class="icon-bar"></span>
	            	<span class="icon-bar"></span>
	            	<span class="icon-bar"></span>
	          	</button>
	        </div>

	        <div class="navbar-collapse collapse" id="navbar-main">
	          	<ul class="nav navbar-nav">
	          		<?= my_menu_principal(); ?>
	          	</ul>
	          	<ul class="nav navbar-nav navbar-right">
	          		<?= my_menu_principal_derecha(); ?>
	          	</ul>
	        </div>
	    </div>
	</div>

	<div class="container">
		<div class="page-header">
			<div class="row row-same-height">
		        <!-- Menú del sistema -->	        
		        <div class="col-md-4">
		        	<div id="big_logo_unsa" class="jumbotron">
		        		<img src="<?= base_url('img/logo-unsa2.png'); ?>">
		        	</div>
		        </div>
		        <!-- Contenido de la aplicación -->
		        <div class="col-md-8">
		        	<?= $this->load->view($contenido); ?>
		        </div>
      		</div>

  			<div class="row jumbotron info">
				<h2>Características actuales del Sistema</h2>
      			<div class="col-md-6">
      				<ul>
      					<li><h3>Un punto de Venta en cada Facultad</h3></li>
      					<li><h3>Los cajeros pueden recibir billetes de $2, $5, $10, $20, $50 y $100</h3></li>
      					<li><h3>Se pueden comprar Tickets con hasta 2 semanas de anticipación</h3></li>
      					<li><h3>El horario de funcionamiento de los cajeros, será de hs. 8 a 18</h3></li>
      				</ul>
      			</div>
      			<div class="col-md-6">
      				<ul>
      					<li><h3>Los Tickets pueden ser anulados para evitar suspenciones</h3></li>
      					<li><h3>Los cajeros no dan vuelto</h3></li>
      					<li><h3>El sistema utiliza el concepto de monedero virtual</h3></li>
      					<li><h3>Control de consumo de los tickets con códigos de barra</h3></li>
      				</ul>
      			</div>
  			</div>
  			<div class="row jumbotron info">
				<h2>OBLIGATORIO:</h2>
      			<div class="col-md-12">
      				<ul>
      					<li><h3>Tu nombre de <strong>usuario</strong> debe ser tu <strong>número de documento.</strong></h3></li>
      					<li><h3>El número de libreta universitaria, al igual que tu documento, se deben ingresar sin puntos ni comas.</h3></li>
      					<li><h3>Los alumnos que aprobaron 2 materias en los últimos 12 meses, podrán comprar en los cajeros casi de forma automática.</h3></li>
      				</ul>
      			</div>
  			</div>
  			<div class="row jumbotron info">
				<h2>Consejos:</h2>
      			<div class="col-md-12">
      				<ul>
      					<li><h3>Deben completar su perfil, subiendo una fotografía real, y actualizada</h3></li>
      					<li><h3>Ante cualquier inconveniente, deben acercarse a la secretaría de bienestar, con tu D.N.I, libreta Universitaria y toda documentación que acredite tu condición de <strong>alumno regular</strong> y que has aprobado 2 materias en los últimos 12 meses</h3></li>
      					<li><h3>El cajero no entrega vuelto, en caso de quedar saldo a favor del alumno, este se acreditará en su "Monedero Virtual"</h3></li>
      					<li><h3>El Monedero Virtual, es el concepto usado para, administrar los saldos a favor del alumno, que se produzcan de vueltos, tickets anulados, o ingreso de dinero a la cuenta para una futura compra.</h3></li>
      					<li><h3>Controlar los datos del ticket, ficha de servicio, importe, y número de ticket.</h3></li>
      				</ul>
      			</div>
  			</div>
		    <hr>

		    <footer>
		    	<p><?= $this->session->userdata('nombre_usuario'); ?>&copy; Sistema de gestión de tickes UNSA - <?= date('d-m-Y H:i') ?> </p>
		    </footer>
		</div>
	</div>
</body>
</html>