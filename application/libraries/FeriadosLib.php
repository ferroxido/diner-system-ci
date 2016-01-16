<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FeriadosLib {

	function __construct(){
		$this->CI = & get_instance();//Obtener la instancia del objeto por referencia.
		$this->CI->load->model('Model_feriados');//Cargamos el modelo.
	}


	public function validar_fecha($fecha){
		$tokens = explode("/", $fecha);

		$numTokens = 3;//cade fecha tiene 3 tokens: day, month y year, separados por "/"

		//Garantizar que el array tenga dia, mes y año.
		if(sizeof($tokens) == $numTokens && is_numeric($tokens[0]) && is_numeric($tokens[1]) && is_numeric($tokens[2])){
			return true;
		}else{
			return false;
		}
	}



}