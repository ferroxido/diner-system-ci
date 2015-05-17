<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendario extends CI_Controller{	

	protected $defaultTipo = 0;//Default tipo feriado.

	//Constructor
	function __construct(){
		parent::__construct();

      	$this->load->model('Model_Calendario');
      	$this->load->model('Model_Feriados');
      	$this->load->library('calendarioLib');
      	$this->load->library('usuarioLib');
      	$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
      	$this->form_validation->set_message('desde_menor_hasta', 'La fecha %s es mayor que %s, eso no es posible');
      	$this->form_validation->set_message('validar_calendario_unico', 'Existe otro calendario que se solapa con este');
      	$this->form_validation->set_message('validar_feriado', 'El feriado debe estar entre las fechas indicadas');
      	$this->form_validation->set_message('validar_anulacion', 'Clave incorrecta!');
	}

	public function index(){
		$data['contenido'] = 'calendario/index';
		$data['registros'] = $this->Model_Calendario->all();
		$this->load->view('tmp-admin', $data);
	}

	public function detalle($id = null){
		if(isset($id) && is_numeric($id)){
			$data['contenido'] = 'calendario/detalle';
			$data['titulo'] = 'Calendario';
			$resultado = $this->calendariolib->calcular_meses($id);
			if($resultado){
				$data['calendario'] = $this->calendariolib->generar_calendario($resultado['year'],$resultado['month'],$resultado['total']);
				$this->load->view('tmp-admin', $data);
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
		$this->load->view('tmp-admin', $data);
	}

	public function feriados(){
		//Guardamos los valores de las variables
		$descripcion = $this->input->post('descripcion');
		$desde = $this->input->post('desde');
		$hasta = $this->input->post('hasta');

		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('desde', 'Desde', 'required|callback_desde_menor_hasta[hasta]');
		$this->form_validation->set_rules('hasta', 'Hasta', 'required|callback_validar_calendario_unico');
		
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->create();
		}else{
			$data['contenido'] = 'calendario/feriados';
			$data['descripcion'] = $descripcion;
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
			$data['feriados'] = $this->Model_Feriados->all_between($desde, $hasta);
			$this->load->view('tmp-admin', $data);
		}
	}

	public function agregar_feriados(){
		if($this->session->userdata('dni_usuario') != null){
			$data['descripcion'] = $this->input->post('descripcion');
			$data['desde'] = $this->input->post('desde');
			$data['hasta'] = $this->input->post('hasta');
			$data['contenido'] = 'calendario/agregar_feriados';
			$this->load->view('tmp-admin',$data);
		}
	}

	public function validar_feriado(){
		$desde = $this->input->post('desde');
		$hasta = $this->input->post('hasta');
		$fecha = $this->input->post('fecha');
		return $this->calendariolib->validar_feriado($desde, $hasta, $fecha);
	}

	public function insert_feriado(){
		$feriado['descripcion'] = $this->input->post('descripcion');
		$feriado['fecha'] = $this->input->post('fecha');

		$this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required|callback_validar_feriado');
		if($this->form_validation->run() == FALSE){
			//Si no cumplio alguna de las reglas
			$this->agregar_feriados();
		}else{
			$feriado['tipo'] = $this->defaultTipo;
			$feriado['created'] = date('Y/m/d H:i');
			$feriado['updated'] = date('Y/m/d H:i');
 			$this->Model_Feriados->insert($feriado);
			
			$data['contenido'] = 'calendario/feriados';
			$data['descripcion'] = $this->input->post('descripcion_calendario');
			$desde = $this->input->post('desde');
			$hasta = $this->input->post('hasta');
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
			$data['feriados'] = $this->Model_Feriados->all_between($desde, $hasta);
			$this->load->view('tmp-admin', $data);
		}
	}

	public function insert(){
		if($this->session->userdata('dni_usuario') != null){
			$desde = $this->input->post('desde');
			$hasta = $this->input->post('hasta');
			$registro['descripcion'] = $this->input->post('descripcion');
			$registro['desde'] = $desde;
			$registro['hasta'] = $hasta;
	 		$this->Model_Calendario->insert($registro);
			$this->calendariolib->generar_dias($desde, $hasta);
			redirect('calendario/index');
		}
	}

	public function desde_menor_hasta(){
		$desde = $this->input->post('desde');
		$hasta = $this->input->post('hasta');
		return $this->calendariolib->desde_menor_hasta($desde, $hasta);
	}

	public function validar_calendario_unico(){
		$desde = $this->input->post('desde');
		$hasta = $this->input->post('hasta');
		return $this->calendariolib->validar_calendario_unico($desde, $hasta);
	}

	public function get_dias_feriados(){
		if($this->input->is_ajax_request()){
			$idCalendario = $this->input->post('idCalendario');
			$registros = $this->Model_Calendario->get_dias_feriados($idCalendario);
			echo json_encode($registros);
		}
	}

	public function actualizar(){
		if($this->input->is_ajax_request()){

			$estadoDia = $this->input->post('estado');
			$ticketsVendidos = $this->input->post('tickets_vendidos');
			$ticketsTotales = $this->input->post('tickets_totales');
			$registro = $this->input->post();
			if ($estadoDia == '0' && $ticketsVendidos == '0'){
				$registro['tickets_totales'] = 0;
				$this->Model_Calendario->update($registro);
				echo 'Se actualizaron los datos ...';
			}else if ($estadoDia == '1' && is_numeric($ticketsTotales)){
				if((int)$ticketsTotales > -1){
					$registro['tickets_totales'] = $ticketsTotales;
					$this->Model_Calendario->update($registro);
					echo 'Se actualizaron los datos ...';
				}else{
					echo 'No se pudo actualizar los datos.';
				}
			}else{
				echo 'No se pudo actualizar los datos.';
			}
		}else{
			show_404();
		}
	}

	/*
	 * Anula todos los tickets del día seleccionado, Mucho cuidado con esta función!!
	 */
	public function anular($fecha){
		if($this->session->userdata('dni_usuario') != null){
			$data['contenido'] = 'calendario/anular';
			$data['fecha'] = $fecha;
			$this->load->view('tmp-admin',$data);
		}		
	}

	public function validar_anulacion(){
		$clave = $this->input->post('clave');
		$fecha = $this->input->post('fecha');
		return $this->calendariolib->validar_anulacion($clave, $fecha);
	}

	public function anulando(){
		if($this->input->post()){
			$clave = $this->input->post('clave');
			$fecha = $this->input->post('fecha');
			$dni = $this->session->userdata('dni_usuario');

			$this->form_validation->set_rules('clave', 'Clave', 'required|callback_validar_anulacion');

			if($this->form_validation->run() == FALSE){

				$this->anular($fecha);

			}else{
				$fecha = $this->calendariolib->transformar_fecha($fecha, '-');

				//Anulo los tickets
				$this->calendariolib->anular($fecha, $dni);
				
				redirect('calendario/index');
			}
		}else{
			show_404();
		}		
	}

}