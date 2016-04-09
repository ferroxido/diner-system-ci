<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipos_Operaciones extends CI_Controller {

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_tipos_operaciones');
		$this->load->library('tiposoperacionesLib');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('numeric', '$s debe ser un número');
		$this->form_validation->set_message('is_natural', '%s debe ser un número natural mayor que cero');
	}

	public function index(){
		$data['contenido'] = 'tipos_operaciones/index';
		$data['titulo'] = 'Tipos de Operaciones';
		$data['registros'] = $this->Model_tipos_operaciones->all();
		$data['menu'] = $this->Model_tipos_operaciones->get_menu();
		$this->load->view('tmp-admin', $data);
	}

	public function search(){
		if($this->session->userdata('dni_usuario') != null){
			$data['contenido'] = 'tipos_operaciones/index';
			$data['titulo'] = 'Tipos de Operaciones';
			$valor = $this->input->post('buscar');
			$data['registros'] = $this->Model_tipos_operaciones->allFilter('tipos_operaciones.nombre', $valor);
			$data['menu'] = $this->Model_tipos_operaciones->get_menu();
			$this->load->view('tmp-admin', $data);
		}else{
			//La session expiro, redireccionamos al ingreso.
			$mensaje = "La sesión terminó, ingrese nuevamente";
			$data['contenido'] = 'home/ingreso';
			$data['mostrar_mensaje'] = TRUE;
			$data['exito'] = false;//Variable para saber si el mensaje es bueno o malo.
			$data['mensaje'] = $mensaje;
			$this->load->view('tmp-index', $data);
		}
	}

	public function my_validation(){
		return $this->tiposoperacioneslib->my_validation($this->input->post());
	}

	public function edit($id){
		//$id = $this->uri->segment(3);//1->controlador, 2->accion, 3->el id
		$data['contenido'] = 'tipos_operaciones/edit';
		$data['titulo'] = 'Editar Tipos de Operaciones';
		$data['registro'] = $this->Model_tipos_operaciones->find($id);
		$this->load->view('tmp-admin',$data);
	}

	public function update(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('nombre', 'Nombre', 'required|callback_my_validation');
		$this->form_validation->set_rules('orden', 'Orden', 'numeric|is_natural');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->edit($registro['id']);
		}else{
			$registro['updated'] = date('Y/m/d H:i');
			$this->Model_tipos_operaciones->update($registro);
			redirect('tipos_operaciones/index');
		}
	}

	public function create(){
		$data['contenido'] = 'tipos_operaciones/create';
		$data['titulo'] = 'Crear Operación';
		$data['menu'] = $this->Model_tipos_operaciones->get_menu();
		$this->load->view('tmp-admin',$data);
	}

	public function insert(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('nombre', 'Nombre', 'required|callback_my_validation');
		$this->form_validation->set_rules('orden', 'Orden', 'numeric|is_natural');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->create();
		}else{
			if (isset($registro['estado'])){
				$registro['estado'] = 1;
			}
			$registro['created'] = date('Y/m/d H:i');
			$registro['updated'] = date('Y/m/d H:i');
 			$this->Model_tipos_operaciones->insert($registro);
			redirect('tipos_operaciones/index');
		}
	}

	public function delete($id){
		$this->Model_tipos_operaciones->delete($id);
		redirect('tipos_operaciones/index');
	}

}
