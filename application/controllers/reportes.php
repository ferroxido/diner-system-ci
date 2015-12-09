<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes extends CI_Controller {

	protected $filasPorPagina = 200;
	protected $primeraPagina = 1;

	function __construct(){
		parent::__construct();
        ini_set('memory_limit', '512M');
		//Cargo la librería html2pdf
		$this->load->library('ConvertToPDF');
		$this->load->library('reportesLib');
		$this->load->library('calendarioLib');
		//Cargo el modelo
		$this->load->model('Model_Facultades');
		$this->load->model('Model_Usuarios');
		$this->load->model('Model_Tickets');
	}

	public function index(){
		$data['contenido'] = 'reportes/index';
		//Datos para el informe de usuarios por facultad
		$data['registros'] = $this->Model_Facultades->clasificacion_usuarios();
		$data['totales'] = $this->Model_Usuarios->total_usuarios();
		//Datos para el informe de servicios por facultad
		$hoy = date('Y-m-d');
		$data['desde'] = $hoy;
		$data['hasta'] = $hoy;
		$data['registros2'] = $this->Model_Tickets->servicios_tickets($hoy, $hoy);//por defecto, el día actual.
		$data['totales2'] = $this->Model_Tickets->get_total_servicios($hoy, $hoy)->row();
		//Datos para la clasificación de tickets
		$data['registros3'] = $this->Model_Tickets->clasificacion_tickets($hoy, $hoy);//por defecto, el día actual.
		$data['totales3'] = $this->Model_Tickets->get_total_tickets($hoy, $hoy)->row();
		//Datos para el informe 4
		$mes = 13;
		$fecha = "";
		$data['registros4'] = $this->Model_Tickets->consumo_tickets_por_dia($mes, $fecha);
		$data['dias'] = $this->reporteslib->getListaDias();
		$data['meses'] = $this->reporteslib->getListaMeses();
 		//Datos para el ranking de ausentismos
		$data['registros5'] = $this->Model_Tickets->get_ranking_ausentismos();

        //Datos para el reporte 6
        $totalRows = $this->Model_Usuarios->get_total_usuarios_con_saldo();
        $data['numeroPaginas'] = ceil($totalRows / $this->filasPorPagina);
        $data['registros6'] = $this->Model_Usuarios->get_usuarios_saldos($this->filasPorPagina, $this->primeraPagina);

		$this->load->view('tmp-admin', $data);
	}

	public function detalle_ranking($cantidadAusentismo){
		if($cantidadAusentismo != null && is_numeric($cantidadAusentismo)){
			$data['contenido'] = 'reportes/detalle_ranking';
			$data['cantidadAusentismo'] = $cantidadAusentismo;
			$data['registros'] = $this->Model_Tickets->get_detalle_ranking($cantidadAusentismo);
			$this->load->view('tmp-admin', $data);

		}else{
			show_404();
		}
	}

	/*
	 * Filtra los tickets de acuerdo a los parametros enviados por ajax
	 */
	public function filtrar_control_consumo(){
		if($this->input->is_ajax_request()){
			$mes = $this->input->post('mes') + 1;
			$fecha = $this->input->post('fecha');
			$query = $this->Model_Tickets->consumo_tickets_por_dia($mes, $fecha);
			echo json_encode($query);
		}else{
			show_404();
		}
	}

	public function ausentismos($fecha = null){
		if($fecha != null){
			if($this->calendariolib->validar_fecha($fecha, '-')){
				$fechaConsulta = $this->calendariolib->transformar_fecha($fecha, '-');
				$totalRows = $this->Model_Tickets->get_total_ausentismos($fechaConsulta);
				$data['fecha'] = $fecha;
				$data['contenido'] = 'reportes/ausentismos';
				$data['numeroPaginas'] = ceil($totalRows / $this->filasPorPagina);
				$data['registros'] = $this->Model_Tickets->get_ausentismos($fechaConsulta, $this->filasPorPagina, $this->primeraPagina);
				$this->load->view('tmp-admin', $data);
			}else{
				show_404();
			}
		}else{
			show_404();
		}
	}

	public function filtrar_ausentismos(){
		if($this->input->is_ajax_request()){
			$fecha = $this->input->post('fecha');
			$fechaConsulta = $this->calendariolib->transformar_fecha($fecha, '-');
			$posicion = (($this->input->post('page_num') - 1) * $this->filasPorPagina);
			$query = $this->Model_Tickets->get_ausentismos($fechaConsulta, $this->filasPorPagina, $posicion);
			echo json_encode($query);
		}else{
			show_404();
		}
	}

    public function obtener_registros_tickets(){
        $desde = $this->input->post('desde');
        $hasta = $this->input->post('hasta');

        $data['tickets'] = $this->Model_Tickets->servicios_tickets($desde, $hasta);
        $data['totales'] = $this->Model_Tickets->get_total_servicios($desde, $hasta)->result();
        echo json_encode($data);
    }

    public function obtener_clasificacion_tickets(){
        $desde = $this->input->post('desde2');
        $hasta = $this->input->post('hasta2');

        $data['tickets2'] = $this->Model_Tickets->clasificacion_tickets($desde, $hasta);
        $data['totales2'] = $this->Model_Tickets->get_total_tickets($desde, $hasta)->result();
        echo json_encode($data);
    }

	public function generar_pdf(){
		if($this->session->userdata('dni_usuario') != null){
			if($this->input->post('PDF1')){
				$data['registros'] = $this->Model_Facultades->clasificacion_usuarios();
				$data['totales'] = $this->Model_Usuarios->total_usuarios();
				$html = $this->load->view('reportes/reporte_pdf1', $data, true);
				$this->converttopdf->doPDF('informe_usuarios',$html,false,'');
			}else if($this->input->post('PDF2')){
				if($this->input->post('filtro_radio') == 'filtrointervalo' && $this->input->post('desde') != '' && $this->input->post('hasta') != ''){
					//Imprimo pdf según el intervalo.
					$desde = $this->input->post('desde');
					$hasta = $this->input->post('hasta');
				}else if($this->input->post('filtro_radio') == 'filtrodia' && $this->input->post('dia') != ''){
					//Imprimo según el día actual. Por defecto el día actual
					$desde = $this->input->post('dia');
					$hasta = $this->input->post('dia');
				}else{
					$hoy = date('Y-m-d');
					$desde = $hoy;
					$hasta = $hoy;
				}
				$data['registros2'] = $this->Model_Tickets->servicios_tickets($desde, $hasta);//por defecto, el día actual.
				$data['totales2'] = $this->Model_Tickets->get_total_servicios($desde, $hasta)->row();
				$data['desde'] = $desde;
				$data['hasta'] = $hasta;
				$html = $this->load->view('reportes/reporte_pdf2', $data, true);
				$this->converttopdf->doPDF('informe_servicios',$html,false,'');
			}else if($this->input->post('PDF3')){
				if($this->input->post('filtro_radio2') == 'filtrointervalo2' && $this->input->post('desde2') != '' && $this->input->post('hasta2') != ''){
					//Imprimo pdf según el intervalo.
					$desde = $this->input->post('desde2');
					$hasta = $this->input->post('hasta2');
				}else if($this->input->post('filtro_radio2') == 'filtrodia2' && $this->input->post('dia2') != ''){
					//Imprimo según el día actual. Por defecto el día actual
					$desde = $this->input->post('dia2');
					$hasta = $this->input->post('dia2');
				}else{
					$hoy = date('Y-m-d');
					$desde = $hoy;
					$hasta = $hoy;
				}
				$data['registros3'] = $this->Model_Tickets->clasificacion_tickets($desde, $hasta);//por defecto, el día actual.
				$data['totales3'] = $this->Model_Tickets->get_total_tickets($desde, $hasta)->row();
				$data['desde'] = $desde;
				$data['hasta'] = $hasta;
				$html = $this->load->view('reportes/reporte_pdf3', $data, true);
				$this->converttopdf->doPDF('informe_tickets',$html,false,'');
			}else if($this->input->post('PDF4')){
				$data['mes'] = $this->input->post('mes') + 1;
				$data['fecha'] = "";
				$data['registros4'] = $this->Model_Tickets->consumo_tickets_por_dia($data['mes'], $data['fecha']);
				$data['dias'] = $this->reporteslib->getListaDias();
				$data['meses'] = $this->reporteslib->getListaMeses();
				$html = $this->load->view('reportes/reporte_pdf4', $data, true);
				$this->converttopdf->doPDF('informe_consumo',$html,false,'');
			}else if($this->input->post('PDF5')){
				$fecha = $this->input->post('fecha');
				$fechaConsulta = $this->calendariolib->transformar_fecha($fecha, '-');
				$totalRows = $this->Model_Tickets->get_total_ausentismos($fechaConsulta);
				$data['fecha'] = $fecha;
				$data['registros'] = $this->Model_Tickets->get_ausentismos($fechaConsulta, $this->filasPorPagina, $this->primeraPagina);
				$html = $this->load->view('reportes/reporte_pdf5', $data, true);
				$this->converttopdf->doPDF('ausentismos'.$fecha,$html,false,'');
			}else if($this->input->post('PDF6')){
				$data['registros'] = $this->Model_Tickets->get_ranking_ausentismos();
				$html = $this->load->view('reportes/reporte_pdf6', $data, true);
				$this->converttopdf->doPDF('Cantidad_ausentes',$html,false,'');
			}else if($this->input->post('PDF7')){
				$cantidadAusentismo = $this->input->post('cantidad_ausentismo');
				$data['cantidadAusentismo'] = $cantidadAusentismo;
				$data['registros'] = $this->Model_Tickets->get_detalle_ranking($cantidadAusentismo);
				$html = $this->load->view('reportes/reporte_pdf7', $data, true);
				$this->converttopdf->doPDF('Ausentes',$html,false,'');
			}else if($this->input->post('PDF8')) {
                $data['registros'] = $this->Model_Usuarios->get_usuarios_saldos();
                $html = $this->load->view('reportes/reporte_pdf8', $data, true);
                $this->converttopdf->doPDF('Saldos',$html,false,'');
            }
		}else{
			redirect('home/index');
		}
	}

    public function reporte1(){
        $data['registros'] = $this->Model_Facultades->clasificacion_usuarios();
        $data['totales'] = $this->Model_Usuarios->total_usuarios();
        $this->load->view('reportes/reporte_pdf1', $data);
    }

}
