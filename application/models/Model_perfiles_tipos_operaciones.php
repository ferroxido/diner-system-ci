<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_perfiles_tipos_operaciones extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function all(){
        $query = $this->db->get('perfiles_tipos_operaciones');
        return $query->result();
    }

    function find($id){
        $this->db->where('id', $id);
        return $this->db->get('perfiles_tipos_operaciones')->row();
    }

    function insert($registro){
        $this->db->set($registro);
       	$this->db->insert('perfiles_tipos_operaciones');
    }

    function update($registro){
        $this->db->set($registro);
        $this->db->where('id', $registro['id']);
        $this->db->update('perfiles_tipos_operaciones');
    }

    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete('perfiles_tipos_operaciones');
    }

    function get_operaciones(){
        $lista = array();
        $this->load->model('Model_tipos_operaciones');
        $registros = $this->Model_Menu->all();
        foreach ($registros as $registro) {
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_perfiles() {
        $lista = array();
        $this->load->model('Model_perfiles');
        $registros = $this->Model_Perfil->all();
        foreach ($registros as $registro) {
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

}
