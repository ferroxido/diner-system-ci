<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('autentificar'))
{
	function autentificar()
	{
		$CI = & get_instance();

		$controlador = $CI->uri->segment(1);
		$accion = $CI->uri->segment(2);
		$url = $controlador.'/'.$accion;

		$libres = array(
			'/',
			'home/index',
			'home/acceso_denegado',
			'home/acerca_de',
			'home/ingreso',
			'home/ingresar',
			'home/recordar_clave',
			'home/recordando_clave',
			'home/registro',
			'home/registrarse',
			'home/cambiar_clave',
			'home/cambiando_clave',
			'home/salir'
		);

		if(in_array($url, $libres) || $CI->input->is_ajax_request()){
			echo $CI->output->get_output();
		}else{
			//Si esta logueado le damos más acceso
			if($CI->session->userdata('dni_usuario')){
				if(autorizar()){
					echo $CI->output->get_output();
				}else{
					redirect('home/acceso_denegado');
				}
			}else{
				redirect('home/acceso_denegado');
			}
		}
	}
}

function autorizar(){
	$CI = & get_instance();

	//El perfil del usuario ya está logueado
	$id_perfil = $CI->session->userdata('id_perfil');//Ya tengo su perfil

	//Con el controlador buscar el tipo de operacion
	$CI->load->library('TiposoperacionesLib');
	$controlador = $CI->uri->segment(1);
	$accion = $CI->uri->segment(2);
	$id_tipo_operacion = $CI->tiposoperacioneslib->findByController($controlador, $accion)->id;

	if(!$id_tipo_operacion) {
        return FALSE;
    }

    $CI->load->library('perfilesLib');
    $acceso = $CI->perfileslib->findByTipoOperacionAndPerfil($id_tipo_operacion, $id_perfil);

    if(!$acceso) {
        return FALSE;
    }
    return TRUE;
}