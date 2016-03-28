<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imports extends CI_Controller {

    protected $imports_path = './imports-csv/';

    //Constructor
    function __construct(){
        parent::__construct();
        $this->load->model('Model_alumnos');
        $this->load->helper('directory');
        $this->load->helper('file');
        $this->load->library('importLib');
    }

    public function index($mensaje=NULL){
        $data['contenido'] = 'imports/index';

        $files_name = directory_map($this->imports_path);
        $files_imported = array();
        foreach ($files_name as $file_name) {
            $path = $this->imports_path . $file_name;
            $files_imported[] = get_file_info($path);
        }

        $data['files_imported_data'] = $files_imported;
        $this->load->view('tmp-admin', $data);
    }

    public function import(){
        if($this->session->userdata('dni_usuario') != null){
            $config['upload_path'] = './imports-csv/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = '2048';
            $config['max_width']  = '0';
            $config['max_height']  = '0';
            $config['overwrite'] = true;
            //$config['file_name'] = 'test';

            $this->load->library('upload', $config);

            if (! $this->upload->do_upload()){
                //Controlar error al subir archivo.
                $mostrar = true;
                $error = "No se pudo subir la imagen ".$this->upload->display_errors();

                echo $error;
            }else{
                redirect('imports/index');
            }
        }
    }

    public function procesar($file_name){
        $file = file($this->imports_path.$file_name);
        $csv = array_map('str_getcsv', $file, array_fill(0, sizeof($file), ';'));

        $inserted = $this->importlib->procesar_datos($csv);

        echo '<pre>';
        print_r($inserted);
        echo '</pre>';
    }

}
