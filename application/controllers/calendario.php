<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendario extends CI_Controller{	

	protected $descripcion;
	protected $desde;
	protected $hasta;
	protected $feriados = array();//feriados del calendario
	protected $defaultTipo = 0;//Default tipo feriado.

	//Constructor
	function __construct(){
		parent::__construct();

      	$this->load->model('Model_Calendario');
      	$this->load->model('Model_Feriados');
      	$this->load->library('calendarioLib');
      	$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
      	$this->form_validation->set_message('my_validation', 'La fecha %s es mayor que %s, eso no es posible');
	}

	public function index(){
		$data['contenido'] = 'calendario/index';
		$data['registros'] = $this->Model_Calendario->all();
		$this->load->view('template-admin', $data);
	}

	public function detalle($id = null){
		if(isset($id) && is_numeric($id)){
			$data['contenido'] = 'calendario/detalle';
			$data['titulo'] = 'Calendario';
			$resultado = $this->calendariolib->calcular_meses($id);
			if($resultado){
				$data['calendario'] = $this->calendariolib->generar_calendario($resultado['year'],$resultado['month'],$resultado['total']);
				$this->load->view('template-admin', $data);
			}else{
				show_404();
			}
		}else{
			show_404();
		}
	}

	/*
	 * Muestra la informacion respecto a un dia determinado, cuya 
	 * fecha se envia por ajax.
	 */
	public function mostrar_info(){
		if($this->input->is_ajax_request()){
			//Variables enviadas por ajax
			$day = (int) $this->input->post('dia');
			$month = $this->calendariolib->fromNameToNumber($this->input->post('month'));
			$year =  $this->input->post('year');
			$idCalendario = $this->input->post('idCalendario');
			
			if($day != null){
				$fecha = date('Y-m-d', strtotime($year.'-'.$month.'-'.$day));
				$query = $this->Model_Calendario->get_calendar_data($fecha, $idCalendario);

				echo json_encode($query);
			}
		}else{
			show_404();
		}
		
	}

	public function create(){
		$data['contenido'] = 'calendario/create';
		$this->load->view('template-admin', $data);
	}

	public function feriados(){
		//Guardamos los valores de las variables
		$this->descripcion = $this->input->post('descripcion');
		$this->desde = $this->input->post('desde');
		$this->hasta = $this->input->post('hasta');

		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('desde', 'Desde', 'required|callback_my_validation[hasta]');
		$this->form_validation->set_rules('hasta', 'Hasta', 'required');
		
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->create();
		}else{
			$data['contenido'] = 'calendario/feriados';
			$data['desde'] = $this->desde;
			$data['hasta'] = $this->hasta;
			$data['feriados'] = $this->Model_Feriados->all_between($this->desde, $this->hasta);
			$this->load->view('template-admin', $data);
		}
	}

	public function agregar_feriados(){
		$data['contenido'] = 'calendario/agregar_feriados';
		$this->load->view('template-admin',$data);
	}

	public function validar_fecha(){
		return true;
	}

	public function insert_feriado(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required|callback_validar_fecha');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->agregar_feriados();
		}else{
			$registro['tipo'] = $this->defaultTipo;
			$registro['created'] = date('Y/m/d H:i');
			$registro['updated'] = date('Y/m/d H:i');
 			$this->Model_Feriados->insert($registro);
			
			$data['contenido'] = 'calendario/feriados';
			$data['feriados'] = $this->Model_Feriados->all_between($this->desde, $this->hasta);
			$this->load->view('template-admin', $data);
		}
	}

	public function insert(){
		$registro = $this->input->post();
		
		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('desde', 'Desde', 'required|callback_my_validation[hasta]');
		$this->form_validation->set_rules('hasta', 'Hasta', 'required');

		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->create();
		}else{

 			$this->Model_Calendario->insert($registro);

 			$this->calendariolib->generar_dias($registro['desde'], $registro['hasta']);
			redirect('calendario/index');
			//echo date('Y', strtotime($registro['desde']));
		}
	}

	public function my_validation(){
		$desde = $this->input->post('desde');
		$hasta = $this->input->post('hasta');
		return $this->calendariolib->my_validation($desde, $hasta);
	}

	public function actualizar(){
		if($this->input->is_ajax_request()){
			$registro = $this->input->post();
			$this->Model_Calendario->update($registro);
			echo 'Se actualizaron los datos ...';
		}else{
			show_404();
		}
	}

}