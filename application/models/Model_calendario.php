<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_calendario extends CI_Model{

	var $conf;

	function __construct()
    {
        parent::__construct();
        $this->conf = array (
               'start_day'    => 'monday',
               'month_type'   => 'long',
               'day_type'     => 'abr',
               'show_next_prev' => false,
               'next_prev_url' => base_url().'calendario/detalle'
             );

        $this->conf['template'] = '

			   {table_open}<table border="0" cellpadding="0" cellspacing="0" class="calendario">{/table_open}

			   {heading_row_start}<tr>{/heading_row_start}

			   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			   {heading_title_cell}<th class="encabezado" colspan="{colspan}">{heading}</th>{/heading_title_cell}
			   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

			   {heading_row_end}</tr>{/heading_row_end}

			   {week_row_start}<tr>{/week_row_start}
			   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
			   {week_row_end}</tr>{/week_row_end}

			   {cal_row_start}<tr class="dias">{/cal_row_start}
			   {cal_cell_start}<td class="dia">{/cal_cell_start}

			   {cal_cell_content}
			   		<div class="dia_num">{day}</div>
			   		<div class="dia_contenido">{content}</div>
			   {/cal_cell_content}

			   {cal_cell_content_today}
			   		<div class="dia_num highlight">{day}</div>
			   		<div class="dia_contenido">{content}</div>
			   {/cal_cell_content_today}

			   {cal_cell_no_content}
			   		<div class="dia_num">{day}</div>
			   	{/cal_cell_no_content}

			   {cal_cell_no_content_today}
			   		<div class="dia_num highlight">{day}</div>
			   	{/cal_cell_no_content_today}

			   {cal_cell_blank}&nbsp;{/cal_cell_blank}

			   {cal_cell_end}</td>{/cal_cell_end}
			   {cal_row_end}</tr>{/cal_row_end}

			   {table_close}</table>{/table_close}
			';
    }

    public function generate($year, $month){
		$this->load->library('calendar', $this->conf);

		return $this->calendar->generate($year, $month);
    }

    public function generate_data($year, $month, $data){
        $this->load->library('calendar', $this->conf);

        return $this->calendar->generate($year, $month, $data);
    }

    public function insert($registro){
    	$this->db->set($registro);
    	$this->db->insert('calendario');
    }

    public function update($registro){
    	$this->db->set($registro);
    	$this->db->where('fecha',$registro['fecha']);
    	$this->db->update('dias');
    }

    public function get_calendar_data($fecha,$id){
    	$this->db->where('fecha', $fecha);
    	$this->db->where('id_calendario',$id);
    	$query = $this->db->get('dias');
    	return $query->result();
    }

    public function all(){
    	$query = $this->db->query('SELECT *, extract(year FROM desde) AS year ,extract(month FROM desde) AS month FROM calendario'); 
    	//$query = $this->db->get('calendario');
    	return $query->result();
    }

    public function find($id){
    	$query = $this->db->query("SELECT extract(year FROM desde) AS year_desde ,extract(month FROM desde) AS month_desde,extract(year FROM hasta) AS year_hasta ,extract(month FROM hasta) AS month_hasta FROM calendario WHERE id = '$id'"); 
    	return $query;
    }

    public function buscar($id){
        $this->db->where('id', $id);
        $query = $this->db->get('calendario');
        return $query;
    }

    public function get_dias_feriados($id){
        $query = $this->db->query("SELECT * ,extract(day FROM dias.fecha) AS dia FROM dias WHERE id_calendario = '$id' AND estado = 0");
        return $query->result();
    }

    public function get_tickets($fecha){
        $estadoAnulado = 0;

        $sql = "SELECT tickets.*, dias.fecha, usuarios.dni AS dni, usuarios.saldo
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN
                    (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios 
                ON tickets_log_usuarios.id_ticket = tickets.id
                INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                WHERE dias.fecha = ? AND tickets.estado <> ?";
        $query = $this->db->query($sql, array($fecha, $estadoAnulado));
        return $query;
    }
}






























































































































