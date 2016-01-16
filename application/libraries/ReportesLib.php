<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Validar login de usuario, cambio de clave y CRUD en la tabla de usuarios
class ReportesLib {

	function __construct(){
		$this->CI = & get_instance();//Obtener la instancia del objeto por referencia.
		$this->CI->load->model('Model_tickets');//Cargamos el modelo.
	}

	public function getListaDias(){
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		return $dias;
	}

	public function getListaMeses(){
		$meses = array(
			0  => "Enero",
			1  => "Febrero",
			2  => "Marzo",
			3  => "Abril",
			4  => "Mayo",
			5  => "Junio",
			6  => "Julio",
			7  => "Agosto",
			8  => "Septiembre",
			9  => "Octubre",
			10 => "Noviembre",
			11 => "Diciembre",
			12 => 'Todos'
		);
		return $meses;
	}

}