<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imports extends CI_Controller {

    //Constructor
    function __construct(){
        parent::__construct();
        $this->load->model('Model_alumnos');
    }

    public function index(){
        $data['contenido'] = 'imports/index';
        $this->load->view('tmp-admin', $data);
    }

    public function insert($go){
        $registro = $this->input->post();
        $this->Model_dias->update($registro);
        redirect('almuerzos/index/'.$go);
    }

}
