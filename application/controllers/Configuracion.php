<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_configuraciones');
		$this->load->library('usuarioLib');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('is_natural', '%s debe ser un valor numérico natural');
		$this->form_validation->set_message('validar_dni', '%s no es válido.');
		$this->form_validation->set_message('parametros_permitidos_generar_clave', 'Usted esta mandando parámetros extras.');
	}

	public function index(){
		$data['contenido'] = 'configuraciones/index';
		$data['registro'] = $this->Model_configuraciones->all();
		$this->load->view('tmp-admin', $data);
	}

	public function clave(){
		$data['contenido'] = 'configuraciones/clave';
		$data['mostrar_mensaje'] = false;
		$data['exito'] = true;
		$data['mensaje'] = "";
		$this->load->view('tmp-admin', $data);		
	}

	public function validar_dni(){
		$dni = $this->input->post('dni');
		return $this->usuariolib->validar_dni($dni);
	}

	public function parametros_permitidos_generar_clave(){
		$registro = $this->input->post();
		return $this->usuariolib->parametros_permitidos($registro, 1);
	}

	public function generando_clave(){
		$dni = $this->input->post('dni');

		$this->form_validation->set_rules('dni', 'Usuario', 'required|is_natural|callback_validar_dni|callback_parametros_permitidos_generar_clave');

		if($this->form_validation->run() == FALSE){
			$this->clave();
		}else{
			$password_generada = $this->usuariolib->generarPassword(6);	
			$nombre = 'Super Admin';
			$email = 'ferroxido@gmail.com';
			if($this->usuariolib->enviar_email($nombre, $email, $password_generada)){
				$registro = array();
				$registro['id'] = 0;
				$registro['clave'] = $this->usuariolib->encriptar($password_generada);
				$this->Model_configuraciones->update($registro);

				$mensaje = "Una nueva clave secreta se ha enviado a su dirección de correo";
				$exito = true;
			}else{
				$mensaje = "Lo sentimos, no se pudo generar una nueva clave secreta, intentelo de nuevo más tarde";
				$exito = false;
			}
			
			//Redireccionamos al ingreso.
			$data['contenido'] = 'configuraciones/clave';
			$data['mostrar_mensaje'] = TRUE;
			$data['exito'] = $exito;
			$data['mensaje'] = $mensaje;
			$this->load->view('tmp-index', $data);//Cargamos la vista y el tmp
		}
	}	

}
