<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facultades extends CI_Controller {

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_Facultades');
		$this->load->library('facultadesLib');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('norepeat', 'Ya existe un registro con el mismo nombre');
	}

	public function index(){
		$data['contenido'] = 'facultades/index';
		$data['registros'] = $this->Model_Facultades->all();
		$this->load->view('tmp-admin', $data);
	}

	public function search(){
		$data['contenido'] = 'facultades/index';
		$valor = $this->input->post('buscar');
		$data['registros'] = $this->Model_Facultades->allFilter('nombre', $valor);
		$this->load->view('tmp-admin', $data);
	}

	public function norepeat(){
		return $this->facultadeslib->norepetir($this->input->post());
	}

	public function update(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('nombre', 'Nombre', 'required|callback_norepeat');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->index();
		}else{
			$registro['updated'] = date('Y/m/d H:i');
			$this->Model_Facultades->update($registro);
			redirect('facultades/index');
		}
	}

	public function create(){
		$data['contenido'] = 'facultades/create';
		$this->load->view('tmp-admin',$data);
	}

	public function insert(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('nombre', 'Nombre', 'required|callback_norepeat');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->create();
		}else{
			$registro['created'] = date('Y/m/d H:i');
			$registro['updated'] = date('Y/m/d H:i');
 			$this->Model_Facultades->insert($registro);
			redirect('facultades/index');
		}
	}

}
