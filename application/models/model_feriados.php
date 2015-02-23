<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Feriados extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function all(){
    	$query = $this->db->get('feriados');
    	return $query->result();
    }

    function all_filter($field, $value){
        $this->db->like($field, $value);
        $query = $this->db->get('feriados');
        return $query->result();
    }

    function all_between($desde, $hasta){
        $this->db->where('fecha >=', $desde);
        $this->db->where('fecha <=', $hasta);
        $query = $this->db->get('feriados');
        return $query->result();
    }

    function find($fecha){
    	$this->db->where('fecha',$fecha);
    	return $this->db->get('feriados');
    }

    function insert($registro){
    	$this->db->set($registro);
    	$this->db->insert('feriados');
    }

    function update($registro){
    	$this->db->set($registro);
    	$this->db->where('id',$registro['id']);
    	$this->db->update('feriados');
    }

    function delete($id){
    	$this->db->where('id',$id);
    	$this->db->delete('feriados');
    }
   
}