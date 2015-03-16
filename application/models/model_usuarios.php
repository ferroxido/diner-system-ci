<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Usuarios extends CI_Model {

    var $conf;

    function __construct()
    {
        parent::__construct();
        $this->conf = array (
            'start_day'    => 'monday',
            'month_type'   => 'long',
            'day_type'     => 'abr',
            'show_next_prev' => true,
            'next_prev_url' => base_url().'usuarios/comprar_tickets'
        );

        $this->conf['template'] = '

               {table_open}<table border="0" cellpadding="0" cellspacing="0" class="calendario">{/table_open}

               {heading_row_start}<tr>{/heading_row_start}

               {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
               {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
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

    function all(){
        $this->db->select('usuarios.*, perfiles.nombre as perfil_nombre');
        $this->db->from('usuarios');
        $this->db->join('perfiles', 'usuarios.id_perfil = perfiles.id', 'left');

    	$query = $this->db->get();//Equivale a SELECT * FROM usuarios
    	return $query->result();//Devuelve un array asociativo.
    }

    function allRecargado(){
        $this->db->select('usuarios.*, perfiles.nombre as perfil_nombre, provincias.nombre as provincia_nombre, facultades.nombre as facultad_nombre, categorias.nombre as categoria_nombre');
        $this->db->from('usuarios');
        $this->db->join('perfiles', 'usuarios.id_perfil = perfiles.id', 'left');
        $this->db->join('provincias', 'usuarios.id_provincia = provincias.id', 'left');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id', 'left');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id', 'left');
        $this->db->order_by('usuarios.nombre', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function allFilter($campo, $valor){
        $this->db->select('usuarios.*, perfiles.nombre as perfil_nombre');
        $this->db->from('usuarios');
        $this->db->join('perfiles', 'usuarios.id_perfil = perfiles.id', 'left');
        $this->db->like($campo,$valor);

        $query = $this->db->get();
        return $query->result();
    }

    function find($dni){
        $this->db->select('usuarios.*, perfiles.nombre as perfil_nombre, provincias.nombre as provincia_nombre, facultades.nombre as facultad_nombre, categorias.nombre as categoria_nombre, categorias.importe as importe');
        $this->db->from('usuarios');
        $this->db->join('perfiles', 'usuarios.id_perfil = perfiles.id', 'left');
        $this->db->join('provincias', 'usuarios.id_provincia = provincias.id', 'left');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id', 'left');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id', 'left');

    	$this->db->where('usuarios.dni',$dni);
    	return $this->db->get()->row();//Equivale a SELECT * FROM usuarios WHERE id='$id'
    }

    function find_simple($dni, $lu, $email){
        $this->db->where('dni', $dni);
        $this->db->where('lu', $lu);
        $this->db->where('email', $email);
        $query = $this->db->get('usuarios');
        return $query;
    }

    function insert($registro){
    	$this->db->set($registro);
    	$this->db->insert('usuarios');
    }

    function update($registro){
    	$this->db->set($registro);
    	$this->db->where('dni',$registro['dni']);
    	$this->db->update('usuarios');
    }

    function get_id_estado($nombre){
        $this->db->where('nombre', $nombre);
        return $this->db->get('estados_usuarios')->row('id');
    }

    function delete($dni){
    	$this->db->where('dni',$dni);
    	$this->db->delete('usuarios');
    }

    function get_login($dni){
    	$this->db->where('dni',$dni);//El dni es el login de usuario
    	return $this->db->get('usuarios');//SELECT * FROM usuarios WHERE () and ()
    }

    function get_perfiles(){
        $lista = array();
        $this->load->model('Model_Perfiles');
        $registros = $this->Model_Perfiles->all();

        foreach($registros as $registro){
            if($registro->nombre == 'Super Administrador'){
                if($this->session->userdata('perfil_nombre') == 'Super Administrador'){
                    $lista[$registro->id] = $registro->nombre;    
                }
            }else{
                $lista[$registro->id] = $registro->nombre;
            }
        }
        return $lista;
    }

    function get_provincias(){
        $lista = array();
        $this->load->model('Model_Provincias');
        $registros = $this->Model_Provincias->all();
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_facultades(){
        $lista = array();
        $this->load->model('Model_Facultades');
        $registros = $this->Model_Facultades->all();
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_categorias(){
        $lista = array();
        $registros = $this->db->get('categorias')->result();
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_ultimas_operaciones($dni){
        $this->db->select('log_usuarios.*, acciones.nombre as nombre_accion');
        $this->db->from('log_usuarios');
        $this->db->join('acciones', 'log_usuarios.id_accion = acciones.id', 'left');
        $this->db->where('dni', $dni);
        $this->db->order_by('fecha','desc');
        $this->db->limit(5);
        return $query = $this->db->get()->row();
    }

    function generate($year, $month){
        $this->load->library('calendar', $this->conf);
        return $this->calendar->generate($year, $month);
    }

    function total_usuarios(){
        $query = $this->db->query("SELECT COUNT(*) AS total_usuarios, 
            COUNT(CASE WHEN id_categoria = 1 THEN 1 END) AS becados, 
            COUNT(CASE WHEN id_categoria = 2 THEN 1 END) AS regulares, 
            COUNT(CASE WHEN id_categoria = 3 THEN 1 END) AS gratuitos FROM usuarios");
        return $query->row();
    }

    function get_tickets_control($id_ticket){
        $this->db->select('tickets.id as id_ticket, dias.fecha as fecha, tickets.estado as estado, tickets.importe as importe, usuarios.nombre as usuario_nombre, usuarios.dni as dni, usuarios.ruta_foto as ruta, facultades.nombre as facultad_nombre, categorias.nombre as categoria_nombre, usuarios.lu as usuario_lu, log_usuarios.fecha as fecha_log');
        $this->db->from('tickets');
        $this->db->join('dias', 'dias.id = tickets.id_dia');
        $this->db->join('tickets_log_usuarios', 'tickets_log_usuarios.id_ticket = tickets.id');
        $this->db->join('log_usuarios', 'log_usuarios.id = tickets_log_usuarios.id_log_usuario');
        $this->db->join('usuarios', 'usuarios.dni = log_usuarios.dni');
        $this->db->join('facultades', 'facultades.id = usuarios.id_facultad');
        $this->db->join('categorias', 'categorias.id = usuarios.id_categoria');
        $this->db->where('tickets.id', $id_ticket);
        //$this->db->where('dias.fecha', date('Y-m-d'));//El ticket debe ser de la fecha de hoy
        $this->db->order_by('log_usuarios.fecha','desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query;
    }

    function get_usuarios_filtrados($buscar_nombre, $buscar_dni, $buscar_lu){
        $buscar_nombre = strtolower($buscar_nombre);
        $this->db->select('usuarios.*, facultades.nombre as facultad, categorias.nombre as categoria');
        $this->db->from('usuarios');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id');
        $this->db->where("LOWER(usuarios.nombre) LIKE '%{$buscar_nombre}%'");
        $this->db->like('dni', $buscar_dni, 'after');
        $this->db->like('lu', $buscar_lu, 'after');
        $this->db->order_by('usuarios.nombre', 'asc');
        $query = $this->db->get();
        return $query->result();
    }


    function add_tickets_log($registro){
        $this->db->set($registro);
        $this->db->insert('tickets_log_usuarios');
    }

}   