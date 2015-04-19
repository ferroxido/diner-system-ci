<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Validar login de usuario, cambio de clave y CRUD en la tabla de usuarios
class ValidacionesLib {

	function __construct(){
		$this->CI = & get_instance();//Obtener la instancia del objeto por referencia.
	}

}