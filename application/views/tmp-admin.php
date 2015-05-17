<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Comedor</title>
    <link href="<?= base_url('css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('css/estilos-admin.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('css/jquery-ui.css'); ?>" rel="stylesheet" />

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <script src="<?= base_url('js/jquery.js'); ?>"></script>
    <script src="<?= base_url('js/jquery-ui.min.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('js/tmp-admin.js'); ?>"></script>
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

	<div class="contenedor">
		<div class="page-header">
			<div class="row">

				<!-- Menú del sistema -->
		        <div id="contenedor_menu" class="col-md-3">
		        	<div id="menu_usuario">
		        		<?= my_menu_collapse(); ?>
						<div id="footer">
		            	<hr>
		            	<p><?= $this->session->userdata('nombre_usuario'); ?>&copy; Sistema de gestión de tickes UNSA - <?= date('d-m-Y H:i'); ?> </p>							
						</div>
		        	</div> 	
		        </div>
    	
		        <!-- Contenido de la aplicación -->
		        <div class="col-md-9 col-md-offset-3">
		        	<div id="contenido" class="row">
		        		<?= $this->load->view($contenido); ?>
		        	</div>
		        </div>
      		</div>
		</div>
	</div>
</body>
</html>
