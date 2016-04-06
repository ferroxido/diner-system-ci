<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImportLib {

    function __construct(){
        $this->CI = & get_instance();//Obtener la instancia del objeto por referencia.
        $this->CI->load->model('Model_alumnos');//Cargamos el modelo.
    }

    public function procesar_datos($datos){
        $affected_rows = 0;
        $inserted = array();
        foreach ($datos as $dato) {
            if ($this->validar_dato($dato)) {
                $registro = array(
                    'facultad' => $dato[0],
                    'lu' => $dato[1],
                    'nombre' => $dato[2],
                    'dni' => $dato[3],
                    'materias' => $dato[4],
                );
                $query = $this->CI->Model_alumnos->find($registro['lu'], $registro['dni']);
                if ($query->num_rows() == 0) {
                    $this->CI->Model_alumnos->insert($registro);
                    if ($this->CI->db->affected_rows() == 1) {
                        $affected_rows++;
                        $inserted[] = $registro;
                    }
                }
            }
        }

        return $inserted;
    }

    public function validar_dato($dato){
        if (strlen($dato[0]) !== 3) {
            return FALSE;
        }

        if (!is_numeric($dato[1])){
            return FALSE;
        }

        if (!is_numeric($dato[3])){
            return FALSE;
        }

        if (!is_numeric($dato[4])){
            return FALSE;
        }

        return TRUE;
    }

}
