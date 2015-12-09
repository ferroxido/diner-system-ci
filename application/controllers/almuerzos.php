<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Goutte\Client;

class Almuerzos extends CI_Controller {

    //Constructor
    function __construct(){
    	parent::__construct();
    	$this->load->model('Model_Dias');
    }

    public function index($go = 0){
        $data['days_of_week'] = array();
        $data['go'] = $go;
        $offset = 86400;
        $nextMonday = strtotime( 'monday this week' ) + $go * 7 * $offset;
        for ($i=0; $i < 5; $i++) {
            $query = $this->Model_Dias->find_almuerzo(date('Y-m-d', $nextMonday));
            if ($query->num_rows() > 0) {
                $data['days_of_week'][] = $query->row();
            }else {
                $generic_obj =  new stdClass();
                $generic_obj->fecha = date('Y-m-d', $nextMonday);
                $generic_obj->noexiste = TRUE;
                $data['days_of_week'][] = $generic_obj;
            }
            $nextMonday += $offset;
        }

        $data['entradas'] = $this->Model_Dias->get_food('entradas');
        $data['principales'] = $this->Model_Dias->get_food('platos_principales');
        $data['postres'] = $this->Model_Dias->get_food('postres');
        $data['contenido'] = 'almuerzos/index';
        $data['entradasdrop'] = $this->Model_Dias->get_food_dropdown('entradas');
        $data['platosdrop'] = $this->Model_Dias->get_food_dropdown('platos_principales');
        $data['postresdrop'] = $this->Model_Dias->get_food_dropdown('postres');
        $this->load->view('tmp-admin', $data);
    }

    public function insert_food($type_food){
        if (isset($type_food)) {
            $registro = $this->input->post();

            $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
            if($this->form_validation->run() == FALSE) {
                //Si no cumplio alguna de las reglas
                $this->index();
            }else {
                $this->Model_Dias->insert_food($registro, $type_food);
                redirect('almuerzos/index');
            }
        }
    }

    public function update_food($type_food) {
        if (isset($type_food)) {
            $registro = $this->input->post();

            $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
            if($this->form_validation->run() == FALSE) {
                //Si no cumplio alguna de las reglas
                $this->index();
            }else {
                $this->Model_Dias->update_food($registro, $type_food);
                redirect('almuerzos/index');
            }
        }
    }

    public function insert($go){
        $registro = $this->input->post();

        if($this->form_validation->run() == FALSE){
            //Si no cumplio alguna de las reglas
            $this->index($go);
        }else{
            $this->Model_Dias->update($registro);
            redirect('almuerzos/index/'.$go);
        }
    }

    public function scrapy() {
        /*
        $url_to_traverse = 'http://www.infobae.com/';
        $client = new Client();
        $crawler = $client->request('GET', $url_to_traverse);
        $status_code = $client->getResponse()->getStatus();
        if($status_code==200){
            $data['title'] = $crawler->filter('#229 h1 a')->text();
        }
        $data['contenido'] = 'almuerzos/index';
        $this->load->view('tmp-admin', $data);
        */
    }

}
