<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('my_validation_errors'))
{
	function my_validation_errors($errors)
	{
		$salida = "";
		if($errors){
			$salida = "<div class='alert alert-dismissable alert-danger'>";
			$salida = $salida."<button type='button' class='close' data-dismiss='alert'>×</button>";
			$salida = $salida."<h4>Mensaje de validación</h4>";
			$salida = $salida."<strong>".$errors."</strong>";
			$salida = $salida."</div>";
		}
		return $salida;
	}
}

if ( ! function_exists('my_mensaje_confirmacion'))
{
	function my_mensaje_confirmacion($mostrar_mensaje)
	{
		$salida = "";
		if($mostrar_mensaje){
			$salida = "<div class='alert alert-dismissable alert-success'>";
			$salida = $salida."<button type='button' class='close' data-dismiss='alert'>×</button>";
			$salida = $salida."<h4>Mensaje de validación</h4>";
			$salida = $salida."<strong>"."Registración exitosa, revise su correo para obtener la contraseña"."</strong>";
			$salida = $salida."</div>";
		}
		return $salida;
	}
}

if ( ! function_exists('my_mensaje_error_upload'))
{
	function my_mensaje_upload($mostrar,$error)
	{
		$salida = "";
		if($mostrar){
			$salida = "<div class='alert alert-dismissable alert-danger'>";
			$salida = $salida."<button type='button' class='close' data-dismiss='alert'>×</button>";
			$salida = $salida."<h4>Mensaje de validación</h4>";
			$salida = $salida."<strong>".$error."</strong>";
			$salida = $salida."</div>";
		}else{
			$salida = "<div class='alert alert-dismissable alert-success'>";
			$salida = $salida."<button type='button' class='close' data-dismiss='alert'>×</button>";
			$salida = $salida."<h4>Mensaje de validación</h4>";
			$salida = $salida."<strong>Se subió correctamente el archivo</strong>";
			$salida = $salida."</div>";
		}
		return $salida;
	}
}

if( ! function_exists('my_menu_principal'))
{
	function my_menu_principal()
	{	
		$opciones = '';
		if(get_instance()->session->userdata('perfil_nombre') === 'Alumno'){
			$opciones = '<li>'.anchor('usuarios/alumno', 'Inicio').'</li>';	
		}else if(get_instance()->session->userdata('perfil_nombre') === 'Super Administrador' || get_instance()->session->userdata('perfil_nombre') === 'Administrador'){
			$opciones = '<li>'.anchor('usuarios/admin', 'Inicio').'</li>';
		}else if(get_instance()->session->userdata('perfil_nombre') === 'Control'){
			$opciones = '<li>'.anchor('usuarios/control', 'Inicio').'</li>';
		}else{
			$opciones = '<li>'.anchor('home/index', 'Inicio').'</li>';
		}
		return $opciones;
	}
}

if( ! function_exists('my_menu_principal_derecha'))
{
	function my_menu_principal_derecha()
	{
		$opciones = '';
		if(get_instance()->session->userdata('dni_usuario')){
			$opciones = $opciones.'<li>'.anchor('home/cambiar_clave', 'Cambiar Clave').'</li>';
			$opciones = $opciones.'<li>'.anchor('home/salir', 'Salir').'</li>';
		}else{
			$opciones = $opciones.'<li>'.anchor('home/ingreso', 'Ingreso').'</li>';
			$opciones = $opciones.'<li>'.anchor('home/registro', 'Registrarse').'</li>';
		}
		return $opciones;
	}
}

if( ! function_exists('my_menu_aplicacion'))
{
	function my_menu_aplicacion()
	{
		$opciones = null;
		if(get_instance()->session->userdata('dni_usuario')){
			$opciones = '';
			get_instance()->load->model('Model_Menu');
			$query = get_instance()->Model_Menu->allForMenu();

			foreach($query as $opcion){
				if($opcion->url != ''){
					$irA = $opcion->url;
					$parametros = array('target'=>'blank');
				}else{
					$irA = $opcion->controlador.'/'.$opcion->accion;
					$parametros = array();
				}
				$opciones = $opciones.'<li>'.anchor($irA, $opcion->nombre, $parametros).'</li>';
			}
		}
		return $opciones;
	}
}

if( ! function_exists('my_menu_collapse'))
{
	function my_menu_collapse()
	{
		$opciones = null;
		get_instance()->load->model('Model_Menu');
		get_instance()->load->model('Model_Tipos_Operaciones');
		if(get_instance()->session->userdata('dni_usuario')){
			$opciones = '';
			$query = get_instance()->Model_Menu->allForMenu();			

			foreach($query as $opcion){
				
				$contenido = '<ul>';

				$operaciones = get_instance()->Model_Tipos_Operaciones->get_operaciones($opcion->id);
				foreach ($operaciones as $operacion) {
					$irA = $operacion->controlador.'/'.$operacion->accion;
					$contenido = $contenido.'<li>'.anchor($irA, $operacion->nombre).'</li>';
				}
				$contenido = $contenido.'</ul>';
				$opciones = $opciones.'<div class="panel panel-default">';
				$opciones = $opciones.'<div id="menu_collapse" class="panel-heading">';
				$opciones = $opciones.'<h4 class="panel-title">';
				$opciones = $opciones.'<a data-toggle="collapse" data-parent="#accordion" href="#'.$opcion->id.'" >';
				$opciones = $opciones.$opcion->nombre.'</a></h4></div>';
				$opciones = $opciones.'<div id="'.$opcion->id.'" class="panel-collapse collapse">';
				$opciones = $opciones.'<div class="panel-body">';
				$opciones = $opciones.$contenido.'</div></div></div>';
			}
		}else{

		}
		return $opciones;
	}
}

if( ! function_exists('my_botonera_home'))
{
	function my_botonera_home()
	{
		$botones = "";
		$botones = $botones."<div class='col-md-8 col-xs-12'>";
		$botones = $botones.anchor('home/ingreso', ' Ingresar', array('class' => 'btn btn-primary glyphicon glyphicon-log-in'))."<span>&nbsp</span>";
		$botones = $botones.anchor('home/registro', ' Registrarse', array('class' => 'btn btn-info glyphicon glyphicon-book'))."</div>";
		return $botones;
	}
}

if( ! function_exists('my_cambiar_clave_cancelar'))
{
	function my_cambiar_clave_cancelar()
	{
		$boton = "";
		if(get_instance()->session->userdata('estado_usuario') == 2){
			if(get_instance()->session->userdata('perfil_nombre') === 'Alumno'){
				$boton = $boton.anchor('usuarios/alumno', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove'));
			}else if(get_instance()->session->userdata('perfil_nombre') === 'Control'){
				$boton = $boton.anchor('usuarios/control', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove'));
			}else{
				$boton = $boton.anchor('usuarios/admin', ' Cancelar',array('class'=>'btn btn-default glyphicon glyphicon-remove'));
			}
		}
		return $boton;
	}
}

if( ! function_exists('my_boton_permisos'))
{
	function my_boton_permisos($destino, $nombre_boton ,$claseHTML)
	{
		$boton = "";
		if(get_instance()->session->userdata('estado_usuario') == 2){
			if(get_instance()->session->userdata('perfil_nombre') === 'Super Administrador' || get_instance()->session->userdata('perfil_nombre') === 'Administrador'){
				$boton = $boton.anchor($destino, $nombre_boton,array('class'=>$claseHTML));
			}else if(get_instance()->session->userdata('perfil_nombre') === 'Consulta'){
				$boton = $boton.anchor($destino, $nombre_boton,array('class'=>$claseHTML, 'disabled'=>'disabled'));
			}
		}
		return $boton;
	}
}

