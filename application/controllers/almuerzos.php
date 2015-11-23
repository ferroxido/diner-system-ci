<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Goutte\Client;

class Almuerzos extends CI_Controller {

    //Constructor
    function __construct(){
    	parent::__construct();
    	$this->load->model('Model_Dias');
    }

    public function index(){
        $data['days_of_week'] = array();
        $offset = 86400;
        $nextMonday = strtotime( "next monday" );
        for ($i=0; $i < 5; $i++) { 
            $data['days_of_week'][] = $this->Model_Dias->find_almuerzo(date('Y-m-d', $nextMonday));
            $nextMonday += $offset;
        }
        $data['contenido'] = 'almuerzos/index';
        $this->load->view('tmp-admin', $data);
    }

    public function update(){

    }

    public function create(){

    }

    public function insert(){

    }

    public function delete($id){

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
