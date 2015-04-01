<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CalendarioLib {

	function __construct(){
		$this->CI = & get_instance();//Obtener la instancia del objeto por referencia.
		$this->CI->load->model('Model_Calendario');//Cargamos el modelo.
	}

	public function desde_menor_hasta($desde, $hasta){
		$tokensDesde = explode("/", $desde);
		$tokensHasta = explode("/", $hasta);

		$numTokens = 3;//cade fecha tiene 3 tokens: day, month y year, separados por "/"

		//Garantizar que el array tenga dia, mes y año.
		if(sizeof($tokensDesde) == $numTokens && sizeof($tokensHasta) == $numTokens){
			//Comparo los años
			$yearDesde = (int) $tokensDesde[0];
			$yearHasta = (int) $tokensHasta[0];
			if($yearDesde <= $yearHasta){
				//Por ahora son validas
				if($yearDesde < $yearHasta){
					//listo la fecha desde es menor que hasta
					return true;
				}else{
					//Coinciden en año. Debo comparar meses
					$monthDesde = $tokensDesde[1];
					$monthHasta = $tokensHasta[1];

					if($monthDesde <= $monthHasta){
						//por ahora son validas
						if($monthDesde < $monthHasta){
							//listo la fecha desde es menor que hasta
							return true;		
						}else{
							//Coinciden en mes. Debo comparar dias
							$dayDesde = $tokensDesde[2];
							$dayHasta = $tokensHasta[2];
							if($dayDesde <= $dayHasta){
								//Fecha valida
								return true;
							}else{
								//La fecha desde es menor que hasta y eso no es posible.
								return false;
							}
						}
					}else{
						//La fecha desde es menor que hasta y eso no es posible.
						return false;
					}
				}
			}else{
				//La fecha desde es menor que hasta y eso no es posible.
				return false;
			}
		}else{
			//Formato erroneo
			return false;
		}
	}

	public function validar_calendario_unico($desde, $hasta){
		$desdeUnix = strtotime($desde);
		$hastaUnix = strtotime($hasta);

		$calendarios = $this->CI->db->get('calendario')->result();
		foreach ($calendarios as $calendario) {
			$desdeCalendario = strtotime($calendario->desde);
			$hastaCalendario = strtotime($calendario->hasta);
			if($desdeCalendario <= $desdeUnix && $desdeUnix <= $hastaCalendario){
				return false;
			}
			if($desdeCalendario <= $hastaUnix && $hastaUnix <= $hastaCalendario){
				return false;
			}
		}

		return true;
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
		//$monthNumber = date('m', strtotime($monthName));
		//return $monthNumber;
		switch ($monthName) {
			case 'January':	return 01;
			case 'February': return 02;
			case 'March': return 03;
			case 'April': return 04;
			case 'May': return 05;
			case 'June': return 06;
			case 'July': return 07;
			case 'August': return 08;
			case 'September': return 09;
			case 'October': return 10;
			case 'November': return 11;
			case 'December': return 12;
		}
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
					$fecha = date('Y-m-d',$i);

					//Controlamos si es un feriado
					$this->CI->db->where('fecha', $fecha);
					$query = $this->CI->db->get('feriados');
					if($query->num_rows() == 1){
						$data['evento'] = $query->row('descripcion');
						$data['tickets_totales'] = 0;
						$estadoFeriado = 0;
						$data['estado'] = $estadoFeriado;
					}else{
						$estadoActivo = 1;
						$data['estado'] = $estadoActivo;
						$data['tickets_totales'] = 700;
						$data['evento'] = 'Día Habilitado para la compra de tickets';
					}
					$data['fecha'] = $fecha;
					$data['tickets_vendidos'] = 0;
					$data['id_calendario'] = $id;
					$this->CI->db->set($data);
	    			$this->CI->db->insert('dias');
				}
			}
		}
	}

	public function validar_feriado($desde, $hasta, $fecha){
		$desdeUnix = strtotime($desde);
		$hastaUnix = strtotime($hasta);
		$fechaUnix = strtotime($fecha);

		if ($desdeUnix <= $fechaUnix && $fechaUnix <= $hastaUnix){
			return true;
		}else{
			return false;
		}

	}

}