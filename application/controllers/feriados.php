<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feriados extends CI_Controller {

	protected $defaultTipo = 0;

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_Feriados');
		$this->load->library('feriadosLib');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('validar_fecha', 'Formato de Fecha incorrecto');
	}

	public function index(){
		$data['contenido'] = 'feriados/index';
		$data['registros'] = $this->Model_Feriados->all();
		$this->load->view('template-admin', $data);
	}

	public function search(){
		$data['contenido'] = 'feriados/index';
		$valor = $this->input->post('buscar');
		$data['registros'] = $this->Model_Feriados->all_filter('descripcion', $valor);
		$this->load->view('template-admin', $data);
	}

	public function update(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required|callback_validar_fecha');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->index();
		}else{
			$registro['updated'] = date('Y/m/d H:i');
			$this->Model_Feriados->update($registro);
			redirect('feriados/index');
		}
	}

	public function create(){
		$data['contenido'] = 'feriados/create';
		$this->load->view('template-admin',$data);
	}

	public function validar_fecha($fecha){
		return $this->feriadoslib->validar_fecha($fecha);
	}

	public function insert(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required|callback_validar_fecha');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->create();
		}else{
			$registro['tipo'] = $this->defaultTipo; 
			$registro['created'] = date('Y/m/d H:i');
			$registro['updated'] = date('Y/m/d H:i');
 			$this->Model_Feriados->insert($registro);
			redirect('feriados/index');
		}
	}

	public function delete($id){
		$this->Model_Feriados->delete($id);
		redirect('feriados/index');
	}

}