<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_Configuraciones');
		$this->load->library('usuarioLib');
	}

	public function index(){
		$data['contenido'] = 'configuraciones/index';
		$data['registro'] = $this->Model_Configuraciones->all();
		$this->load->view('template-admin', $data);
	}

	public function clave(){
		$data['contenido'] = 'configuraciones/clave';
		$data['mostrar_mensaje'] = false;
		$data['exito'] = true;
		$data['mensaje'] = "";
		$this->load->view('template-admin', $data);		
	}

	public function validar_dni(){
		$dni = $this->input->post('dni');
		return $this->usuariolib->validar_dni($dni);
	}

	public function generando_clave(){
		$dni = $this->input->post('dni');

		$this->form_validation->set_rules('dni', 'Usuario', 'required|callback_validar_dni');

		if($this->form_validation->run() == FALSE){
			$this->clave();
		}else{
			$password_generada = $this->usuariolib->generarPassword(4);	
			$nombre = 'Super Admin';
			$email = 'ferroxido@gmail.com';
			if($this->usuariolib->enviar_email($nombre, $email, $password_generada)){
				$registro = array();
				$registro['id'] = 0;
				$registro['clave'] = $this->usuariolib->encriptar($password_generada);
				$this->Model_Configuraciones->update($registro);

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
			$this->load->view('template-index', $data);//Cargamos la vista y el template
		}
	}	

	public function update(){
		if($this->input->post()){
			$registro = $this->input->post();

			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('puerto', 'Puerto', 'required|numeric');
			$this->form_validation->set_rules('mensaje_email', 'Mensaje Email', 'required|max_length[300]');
			$this->form_validation->set_rules('smtp', 'Smtp', 'required');
			$this->form_validation->set_rules('asunto', 'Asunto', 'required|max_length[100]');
			$this->form_validation->set_rules('hora_anulacion', 'Hora Anulacion', 'required|is_natural');
			$this->form_validation->set_rules('hora_compra', 'Hora Compra', 'required|is_natural');
			$this->form_validation->set_rules('saldo_maximo', 'Saldo Maximo', 'required|is_natural');
			$this->form_validation->set_rules('session_time', 'Tiempo de Sesion', 'required|is_numeric');
			$this->form_validation->set_rules('max_length_pass', 'maxima longitud pass', 'required|is_natural');
			$this->form_validation->set_rules('max_length_nombre', 'maxima longitud nombre', 'required|is_natural');
			$this->form_validation->set_rules('max_length_mail', 'maxima longitud mail', 'required|is_natural');
			$this->form_validation->set_rules('max_length_dni', 'maxima longitud dni', 'required|is_natural');
			$this->form_validation->set_rules('max_length_lu', 'maxima longitud lu', 'required|is_natural');
			$this->form_validation->set_rules('caracteres_permitidos', 'Caracteres permitidos', 'required|max_length[100]');

			if($this->form_validation->run() == FALSE){
				//Si no cumplio alguna de las reglas
				$this->index();
			}else{
				//El registro está ok
				$this->Model_Configuraciones->update($registro);
				redirect('usuarios/admin');
			}
		}else{
			show_404();
		}
	}
}