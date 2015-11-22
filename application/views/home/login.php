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
	<div class="navbar navbar-inverse navbar-fixed-top">
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
		<div id="login" class="formulario">
			<div class="row">
				<h2 id="titulo">Comedor Universitario</h2>
			</div>
			<div class="row">
				<?= form_open('home/ingresar'); ?>
					<h3>Ingreso al Sistema</h3>
					<?= my_validation_errors(validation_errors()); ?>
					<?= my_mensaje_confirmacion($mensaje, $mostrar_mensaje, $exito)?>
					
					<div class="row">
						<div class="form-group">
							<?= form_label('DNI: ', 'dni', array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-7">
								<?= form_input(array('class'=>'form-control','type'=>'text', 'name'=>'dni', 'id'=>'dni', 'placeholder'=>'Tu usuario (DNI)', 'value'=>set_value('dni'))); ?>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="form-group">
							<?= form_label('Contraseña: ', 'label_password', array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-7">
								<?= form_input(array('class'=>'form-control','type'=>'password', 'name'=>'password', 'id'=>'password', 'placeholder'=>'Tu contraseña', 'value'=>set_value('password'))); ?>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-offset-3">
							<?= anchor('home/recordar_clave', '¿Has olvidado tu contraseña?'); ?>
						</div>
					</div>
					
					<div class="row">
						<div class="form-group">
							<div class="col-md-offset-7">
								<div class="col-md-4 col-xs-12">
									<?= form_button(array('type'=>'submit', 'content'=>' Ingresar', 'class'=>'btn btn-primary glyphicon glyphicon-log-in')); ?>
								</div>	
							</div>
						</div>
					</div>
					
				<?= form_close(); ?>
			</div>
		</div>
		<div id="fondo">
		</div>
		<div id="info_unsa">
			<div class="row">
				<div class="col-md-4">
					<div id="logo_unsa">
						<img src="<?= base_url('img/logo-unsa2.png'); ?>">
					</div>
				</div>
				<div class="col-md-8">
					<div id="texto_unsa">
						<h4>
							Sistema de gestión de Tickets del comedor, implementado por <a href="">La Universidad Nacional de Salta</a>. Compre sus tickets en las terminales de venta ubicadas en cada facultad en el horario de 8 a 18 hs.
						</h4>
					</div>
				</div>
			</div>
		</div>
		<div id="info_sistema">
			<div class="row">
				<div class="col-md-4 contenedor-info">
					<div id="caracteristicas">
						<h4 class="titulos">Características</h4>
						<ul>
							<li>Los cajeros pueden recibir billetes de $2, $5, $10, $20, $50 y $100</li>
							<li>El sistema utiliza el concepto de monedero virtual</li>
							<li>Los Tickets pueden ser anulados para evitar suspenciones</li>
							<li>Los cajeros no dan vuelto</li>
							<li>Control de consumo de los tickets con códigos de barra</li>
						</ul>
						<div class="image">
							<img src="<?= base_url('img/features.png'); ?>">
						</div>
					</div>
				</div>
				<div class="col-md-4 contenedor-info">
					<div id="obligatorio">
						<h4 class="titulos">Obligatorio</h4>
						<ul>
							<li>Tu nombre de usuario debe ser tu número de documento.</li>
							<li>El número de libreta universitaria, al igual que tu documento, se deben ingresar sin puntos ni comas.</li>
							<li>Los alumnos que aprobaron 2 materias en los últimos 12 meses, podrán comprar en los cajeros casi de forma automática.</li>
						</ul>
						<div class="image">
							<img src="<?= base_url('img/obligatorio.png'); ?>">
						</div>
					</div>
				</div>
				<div class="col-md-4 contenedor-info">
					<div id="sugerencias">
						<h4 class="titulos">Sugerencias</h4>
						<ul>
							<li>Deben completar su perfil, subiendo una fotografía real, y actualizada.</li>
							<li>Ante cualquier inconveniente, deben acercarse a la secretaría de bienestar, con tu D.N.I, libreta Universitaria y toda documentación que acredite tu condición de alumno regular y que has aprobado 2 materias en los últimos 12 meses</li>
							<li>Controlar los datos del ticket, ficha de servicio, importe, y número de ticket.</li>
						</ul>
						<div class="image">
							<img src="<?= base_url('img/sugerencia.png'); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer>
		<p><?= $this->session->userdata('nombre_usuario'); ?>&copy; Sistema de gestión de tickes UNSA - <?= date('d-m-Y H:i') ?> </p>
	</footer>
</body>
</html>