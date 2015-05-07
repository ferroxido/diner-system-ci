<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Dias extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function all(){
    	$query = $this->db->get('dias');
    	return $query->result();
    }

    function find($fecha){
    	$this->db->where('fecha',$fecha);
    	return $this->db->get('dias');
    }

    function insert($registro){
    	$this->db->set($registro);
    	$this->db->insert('dias');
    }

    function update($registro){
    	$this->db->set($registro);
    	$this->db->where('fecha',$registro['fecha']);
    	$this->db->update('dias');
    }

    function delete($fecha){
    	$this->db->where('fecha',$fecha);
    	$this->db->delete('dias');
    }

    function consultar_dia_con_ticket($fecha, $dni){
        $this->db->select('tickets.id, dias.fecha, log_usuarios.dni');
        $this->db->from('tickets');
        $this->db->join('dias', 'tickets.id_dia = dias.id', 'left');
        $this->db->join('log_usuarios', 'tickets.id_log_usuario = log_usuarios.id', 'left');
        $this->db->where('dias.fecha', $fecha);
        $this->db->where('dni',$dni);
        return $this->db->get();
    }

    //Devuelve todos los días con la fecha like $fecha.
    function get_dias($fecha){
        $query = $this->db->query("SELECT *, extract(day FROM fecha) AS dia FROM dias WHERE fecha::text LIKE '$fecha' ORDER BY fecha");
        return $query->result();
    }
    
    function get_data_from_month($year, $month){
        $fecha = $year.'-'.$month;
        $query = $this->db->query("SELECT extract(day FROM fecha) AS dia, (tickets_totales - tickets_vendidos) AS tickets_disponibles FROM dias WHERE fecha::text LIKE '{$fecha}%'");
        $registros = $query->result();
        $data = array();
        $hoy = date('d');
        foreach ($registros as $registro) {
            if($registro->tickets_disponibles >= 0){
                if ($registro->dia < $hoy){
                    //El día ya paso
                    $data[$registro->dia] = 'td: 0';
                }else{
                    $data[$registro->dia] = 'td: '.$registro->tickets_disponibles;
                }
            }else{
                $data[$registro->dia] = 'td: 0';
            }
        }
        return $data;
    }   
}