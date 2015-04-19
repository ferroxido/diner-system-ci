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
			<div class="row">
				<!-- Información del alumno -->	
				<div class="col-md-6">
					<div id="info-perfil" class="jumbotron">
						<div class="row">
							<legend>Información personal</legend>
							<fieldset>
							<div id="info_perfil_foto" class="col-xs-5">
								<a href="#" class="thumbnail">
									<?= form_open_multipart('usuarios/subir_foto', array('id'=>'form-foto')); ?>
						      			<input type="file" name="userfile" style="visibility:hidden;position:absolute;top:0;"/>
						      		<?= form_close(); ?>
						      		<img class="img-subir" data-src="holder.js/100%x180" style="width: 100%;" src="<?= $registro->ruta_foto; ?>">
								</a>
							</div>
							<div id="info_perfil_datos" class="col-xs-7">
								<div class="form-group group-label">
									<label class="label-titulo">Nombre: </label>
									<label class="mostrar-info"><?= $registro->nombre; ?></label>
								</div>

								<div class="form-group group-label">
									<label class="label-titulo">DNI: </label>
									<label class="mostrar-info"><?= $registro->dni; ?></label>
								</div>

								<div class="form-group group-label">
									<label class="label-titulo">Facultad: </label>
									<label class="mostrar-info"><?= $registro->facultad_nombre; ?></label>
								</div>
 
								<div class="form-group group-label">
									<label class="label-titulo">Provincia: </label>
									<label class="mostrar-info"><?= $registro->provincia_nombre; ?></label>
								</div>

								<div class="form-group group-label">
									<label class="label-titulo">Categoría: </label>
									<label class="mostrar-info"><?= $registro->categoria_nombre; ?></label>
								</div>

								<div class="form-group group-label">
									<label class="label-titulo">Saldo: </label>
									<label class="mostrar-info"><?= '$ '.$registro->saldo; ?></label>
								</div>
							</div>
							</fieldset>
						</div>
					</div>
				</div>
				
				<!-- Información variable -->
				<?= $this->load->view($contenido); ?>
				
			</div>
	      	<hr>

	      	<footer>
	      		<p><?= $this->session->userdata('nombre_usuario'); ?>&copy; Sistema de gestión de tickes UNSA - <?= date('d-m-Y H:i') ?> </p>
	      	</footer>
		</div>
	</div>
</body>
</html>
