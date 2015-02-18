<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CalendarioLib {

	function __construct(){
		$this->CI = & get_instance();//Obtener la instancia del objeto por referencia.
		$this->CI->load->model('Model_Calendario');//Cargamos el modelo.
	}

	public function my_validation($desde, $hasta){
		$unix_desde = strtotime($desde);
		$unix_hasta = strtotime($hasta);

		if($unix_desde < $unix_hasta){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	//Generar calendario y mostrar todos los meses que necesitamos
	public function generar_calendario($year, $month, $totalMonth){

		if ($totalMonth > 0 && $totalMonth < 13){
			$calendario = array();

			for ($i = 0; $i < $totalMonth; $i++){
				$calendario[$month] = $this->CI->Model_Calendario->generate($year, $month);

				//Si el mes es diciembre, incrementamos $year
				if ($month == 12){
					$year++;
					$month = 1;//ponemos el mes en enero
				}else{
					$month++;
				}
			}

			return $calendario;
		}else{
			return false;
		}
		
	}

	public function calcular_meses($id_calendario){
		$query = $this->CI->Model_Calendario->buscar($id_calendario);
		$resultado = array();

		if($query->num_rows == 1){
			$ajuste = 1;
			$total = 0;
			$totalMeses = 12;

			$fechaDesde = date_parse($query->row('desde'));
			$fechaHasta = date_parse($query->row('hasta'));
			$monthDesde = (int) $fechaDesde['month'];
			$monthHasta = (int) $fechaHasta['month'];

			if($monthDesde <= $monthHasta){
				$total = $monthHasta - $monthDesde + $ajuste;
			}else{
				$total = $totalMeses - $monthDesde + $monthHasta + $ajuste;
			}

			$resultado['year'] = $fechaDesde['year'];
			$resultado['month'] = $monthDesde;
			$resultado['total'] = $total;

			return $resultado;
		}else{
			return false;
		}
	}

	public function fromNameToNumber($monthName){
		$monthNumber = date('m', strtotime($monthName));
		return $monthNumber;
	}

	//Generar los dias entre las fechas pasadas como parámetros y los insertará en la BD
	public function generar_dias($desde, $hasta){

		$this->CI->db->where('desde', $desde);
		$this->CI->db->where('hasta', $hasta);
		$query = $this->CI->db->get('calendario');

		//Si existe un único calendario con esas fechas de inicio y fin.
		if($query->num_rows() == 1){
			//generamos los dias
			$fechaInicio = strtotime($desde);
			$fechaFin = strtotime($hasta);

			//Obtenemos el id del calendario
			$id = $query->row('id');

			$data = array();

			//Incrementamos de 86400 segundos que representa un día
			for($i = $fechaInicio; $i <= $fechaFin; $i += 86400){

				//Nos saltamos los días domingo y sabado.
				if(date('l', $i) != 'Sunday' && date('l', $i) != 'Saturday'){
					$data['fecha'] = date('Y-m-d',$i);
					$data['tickets_totales'] = 700;
					$data['tickets_vendidos'] = 0;
					$data['id_calendario'] = $id;
					$data['evento'] = 'Día Hábil';
					$this->CI->db->set($data);
	    			$this->CI->db->insert('dias');					
	    			//echo date('d-m-Y',$i).'</br>';
				}
			}
		}
	}

}