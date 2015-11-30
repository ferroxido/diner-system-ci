<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_Usuarios extends CI_Controller {

	protected $filasPorPagina = 200;
	protected $primeraPagina = 1;

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_Log_Usuarios');
	}

	public function index(){
		$data['contenido'] = 'log_usuarios/index';
		$accion = 0; //todos los registros.
		$dni = '';
		$totalRows = $this->Model_Log_Usuarios->get_total_rows($accion, $dni);
		$data['numeroPaginas'] = ceil($totalRows / $this->filasPorPagina);
		$data['registros'] = $this->Model_Log_Usuarios->all($this->filasPorPagina, $this->primeraPagina);
		$data['acciones'] = $this->Model_Log_Usuarios->get_acciones();
		$this->load->view('tmp-admin', $data);
	}

	public function get_total_pages(){
		if ($this->input->is_ajax_request()){
			$accion = $this->input->post('accion');
			$dni = $this->input->post('buscar_dni');
			$totalRows = $this->Model_Log_Usuarios->get_total_rows($accion, $dni);
			echo ceil($totalRows / $this->filasPorPagina);
		}
	}

	public function filtrar(){
		if($this->input->is_ajax_request()){
			$accion = $this->input->post('accion');
			$buscar_dni = $this->input->post('buscar_dni');
			$posicion = (($this->input->post('page_num') - 1) * $this->filasPorPagina);
			$query = $this->Model_Log_Usuarios->all_filter($accion, $buscar_dni, $this->filasPorPagina, $posicion);
			echo json_encode($query);
		}else{
			show_404();
		}
	}

}
