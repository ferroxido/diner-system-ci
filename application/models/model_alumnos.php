<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Alumnos extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function all(){
    	$query = $this->db->get('alumnos');
    	return $query->result();
    }

    function find($lu, $dni){
    	$this->db->where('lu',$lu);
    	$this->db->where('dni', $dni);
    	return $this->db->get('alumnos');
    }

    function get_materias_aprobadas($lu){
    	$query = $this->db->query("SELECT COALESCE(materias,'0') AS materias_aprobadas FROM alumnos WHERE lu = '$lu'");
    	return $query;
    }

    function insert($registro){
    	$this->db->set($registro);
    	$this->db->insert('alumnos');
    }

    function update($registro){
    	$this->db->set($registro);
    	$this->db->where('id',$registro['id']);
    	$this->db->update('alumnos');
    }

    function delete($id){
    	$this->db->where('id',$id);
    	$this->db->delete('alumnos');
    }

}