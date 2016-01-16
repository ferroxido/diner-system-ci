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
			$salida = $salida."<h5>".$errors."</h5>";
			$salida = $salida."</div>";
		}
		return $salida;
	}
}

if ( ! function_exists('my_mensaje_confirmacion'))
{
	function my_mensaje_confirmacion($mensaje, $mostrar_mensaje, $exito)
	{
		$salida = "";
		if($mostrar_mensaje){
			if($exito){
				//Mensaje amistoso.
				$salida = "<div class='alert alert-dismissable alert-success'>";
				$salida = $salida."<button type='button' class='close' data-dismiss='alert'>×</button>";
				$salida = $salida."<h4>Mensaje de validación</h4>";
				$salida = $salida."<strong>".$mensaje."</strong>";
				$salida = $salida."</div>";
			}else{
				//Mensaje no amistoso.
				$salida = "<div class='alert alert-dismissable alert-danger'>";
				$salida = $salida."<button type='button' class='close' data-dismiss='alert'>×</button>";
				$salida = $salida."<h4>Mensaje de validación</h4>";
				$salida = $salida."<strong>".$mensaje."</strong>";
				$salida = $salida."</div>";
			}
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
		}
		return $salida;
	}
}

if( ! function_exists('my_menu_principal'))
{
	function my_menu_principal()
	{	
		$estadoBloqueado = 0;
		$estadoActivo = 2;
		$opciones = '';
		if(get_instance()->session->userdata('estado_usuario') == $estadoActivo){
			if(get_instance()->session->userdata('perfil_nombre') === 'Alumno'){
				$opciones = '<li>'.anchor('usuarios/alumno', 'Inicio').'</li>';	
			}else if(get_instance()->session->userdata('perfil_nombre') === 'Super Administrador' || get_instance()->session->userdata('perfil_nombre') === 'Administrador'){
				$opciones = '<li>'.anchor('usuarios/admin', 'Inicio').'</li>';
			}else if(get_instance()->session->userdata('perfil_nombre') === 'Control'){
				$opciones = '<li>'.anchor('usuarios/control', 'Inicio').'</li>';
			}else{
				$opciones = '<li>'.anchor('home/index', 'Inicio').'</li>';
			}
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
		get_instance()->load->model('Model_tipos_operaciones');
		if(get_instance()->session->userdata('dni_usuario')){
			//Obtenemos el perfil para saber que operaciones puede realizar
			$idPerfil = get_instance()->session->userdata('id_perfil');

			$contenido = '<ul class="menu-list">';
			$operaciones = get_instance()->Model_tipos_operaciones->get_operaciones_activas($idPerfil);
			foreach ($operaciones as $operacion) {
				$irA = $operacion->controlador.'/'.$operacion->accion;
				$contenido = $contenido.'<li>'.anchor($irA, $operacion->nombre, array('class'=>'a-noactivo')).'</li>';
			}
			$contenido = $contenido.'</ul>';
		}
		return $contenido;
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

if( ! function_exists('my_volver_editar_perfil'))
{
	function my_volver_editar_perfil()
	{
		$boton = "";
		if(get_instance()->session->userdata('dni_usuario')){
			$perfil = get_instance()->session->userdata('perfil_nombre');
			switch ($perfil) {
				case 'Alumno':
					$boton .= anchor('usuarios/alumno', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left'));
					break;
				case 'Administrador':
					$boton .= anchor('usuarios/admin', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left'));
					break;
				case 'Super Administrador':
					$boton .= anchor('usuarios/admin', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left'));
					break;	
			}
		}else{
			$boton .= anchor('home/index', ' Volver',array('class'=>'btn btn-primary glyphicon glyphicon-arrow-left'));
		}
		return $boton;
	}
}

if( ! function_exists('my_boton_permisos'))
{
	function my_boton_permisos($destino, $nombre_boton ,$claseHTML)
	{
		$boton = "";
		$estado_activo = 2;
		if(get_instance()->session->userdata('estado_usuario') == $estado_activo){
			if(get_instance()->session->userdata('perfil_nombre') === 'Super Administrador' || get_instance()->session->userdata('perfil_nombre') === 'Administrador'){
				$boton = $boton.anchor($destino, $nombre_boton,array('class'=>$claseHTML));
			}else if(get_instance()->session->userdata('perfil_nombre') === 'Consulta'){
				$boton = $boton.anchor($destino, $nombre_boton,array('class'=>$claseHTML, 'disabled'=>'disabled'));
			}
		}
		return $boton;
	}
}

