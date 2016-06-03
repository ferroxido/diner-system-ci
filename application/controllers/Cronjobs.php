<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjobs extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Model_tickets');
        $this->load->library('cronjobsLib');
    }

    /**
     * Funcion para vencer los  tickets que no se consumieron.
     */
    public function procesar($key=""){
        if ($key == "c0m3d0r") {
            $tickets = $this->Model_tickets->get_all_tickets_older_yesterday();
            $status_record = $this->cronjobslib->vencer_tickets($tickets);

            $fecha = date('Y-m-d-H:i:s');
            $filename = $_SERVER['DOCUMENT_ROOT'].'/cronlogs/' . $fecha . '.txt';
            file_put_contents($filename, print_r($status_record, true));
            echo $filename;
        }

    }

}
