<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends CI_Controller {

	protected $filasPorPagina = 200;
	protected $primeraPagina = 1;	

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_Tickets');
	}

	public function index(){
		$data['contenido'] = 'tickets/index';
		$nombre = '';
		$dni = '';
		$id = '';
		$fecha = '';
		$estado = 10;//todos los estados.
		$totalRows = $this->Model_Tickets->get_total_rows($nombre, $dni, $id, $fecha, $estado);
		$data['numeroPaginas'] = ceil($totalRows / $this->filasPorPagina);
		$data['registros'] = $this->Model_Tickets->all($this->filasPorPagina, $this->primeraPagina);
		$data['estados'] = $this->Model_Tickets->get_estados();
		$this->load->view('tmp-admin', $data);
	}

	public function get_total_pages(){
		if ($this->input->is_ajax_request()){
			$nombre = $this->input->post('nombre');
			$dni = $this->input->post('dni');
			$id = $this->input->post('id');
			$fecha = $this->input->post('fecha');
			$estado = $this->input->post('estado');
			$totalRows = $this->Model_Tickets->get_total_rows($nombre, $dni, $id, $fecha, $estado);
			echo ceil($totalRows / $this->filasPorPagina);
		}
	}

	/*
	 * Filtra los tickets de acuerdo a los parametros enviados por ajax
	 */
	public function filtrar($page_num){
		if($this->input->is_ajax_request()){
			$nombre = $this->input->post('nombre');
			$dni = $this->input->post('dni');
			$id = $this->input->post('id');
			$fecha = $this->input->post('fecha');
			$estado = $this->input->post('estado');
			$posicion = (($page_num - 1) * $this->filasPorPagina);
			$query = $this->Model_Tickets->all_filter($nombre, $dni, $id, $fecha, $estado, $this->filasPorPagina, $posicion);
			echo json_encode($query);
		}else{
			show_404();
		}
	}

	/*
	 * Muestra en detalle los estados por los que paso un ticket
	 */
	public function detalles($id_ticket){
		$data['contenido'] = 'tickets/detalles';
		$data['registros'] = $this->Model_Tickets->get_ticket_detalle($id_ticket);
		$this->load->view('tmp-admin', $data);		
	}

	public function vencimiento_cron(){
		if ($this->input->is_cli_request()) {
			//Vencer los tickets de hoy
			echo 'wget';
		}else {
			//navegador
			echo 'navegador';
		}
	}

}