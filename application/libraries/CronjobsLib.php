<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CronjobsLib {

    function __construct(){
        $this->CI = & get_instance();
        $this->CI->load->model('Model_usuarios');
    }

    public function vencer_tickets($tickets){
        // Idealmente esta fecha deberia ser el dia actual a las 16 hrs
        // para asegurarse de que la hora del almuerzo termino.
        // Por ahora venceremos los tickets con un dia de retraso.
        $fecha = date('Y-m-d', strtotime("-1 days"));
        $dni = xxxxxxxxx;// usuario cron
        $status_record = array();
        $status_record['process_start_time'] = date('Y/m/d H:i:s');

        $cont = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->fecha <= $fecha) {
                $status = $this->CI->Model_usuarios->transactionVencer($ticket->id, $dni);

                $status_record['tickets_status'][$ticket->id] = $status;
                if ($status) {
                    $cont++;
                }
            }
        }

        $status_record['process_finish_time'] = date('Y/m/d H:i:s');
        $status_record['num_records_updated'] = $cont;
        return $status_record;
    }

}
