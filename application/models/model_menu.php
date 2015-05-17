<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Menu extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function all(){
    	$query = $this->db->get('menu');
    	return $query->result();
    }

    function allFilter($campo, $valor){
        $this->db->like($campo, $valor);
        $query = $this->db->get('menu');
        return $query->result();
    }

    function allForMenu(){
        $estadoVisible = 1;
        $this->db->where('estado', $estadoVisible);
        $this->db->order_by('nombre', 'asc');//Opcionalmente usar desc
        $query = $this->db->get('menu');
        return $query->result();
    }

    function find($id){
    	$this->db->where('id',$id);
    	return $this->db->get('menu')->row();
    }

    function insert($registro){
    	$this->db->set($registro);
    	$this->db->insert('menu');
    }

    function update($registro){
    	$this->db->set($registro);
    	$this->db->where('id',$registro['id']);
    	$this->db->update('menu');
    }

    function delete($id){
    	$this->db->where('id',$id);
    	$this->db->delete('menu');
    }

}