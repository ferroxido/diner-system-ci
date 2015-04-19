<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Usuarios extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function all($filasPorPagina, $posicion){
        $this->db->select('usuarios.nombre, usuarios.dni, usuarios.lu, usuarios.saldo, usuarios.estado,facultades.nombre as facultad, categorias.nombre as categoria');
        $this->db->from('usuarios');
        $this->db->join('perfiles', 'usuarios.id_perfil = perfiles.id', 'left');
        $this->db->join('provincias', 'usuarios.id_provincia = provincias.id', 'left');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id', 'left');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id', 'left');
        $this->db->order_by('usuarios.nombre', 'asc');
        $this->db->limit($filasPorPagina, $posicion);
        $query = $this->db->get();
        return $query->result();
    }

    function all_filter($buscar_nombre, $buscar_dni, $buscar_lu, $filasPorPagina, $posicion){
        $buscar_nombre = strtolower($buscar_nombre);
        $this->db->select('usuarios.nombre, usuarios.dni, usuarios.lu, usuarios.saldo, usuarios.estado,facultades.nombre as facultad, categorias.nombre as categoria');
        $this->db->from('usuarios');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id');
        $this->db->where("LOWER(usuarios.nombre) LIKE '%{$buscar_nombre}%'");
        $this->db->like('dni', $buscar_dni, 'after');
        $this->db->like('lu', $buscar_lu, 'after');
        $this->db->order_by('usuarios.nombre', 'asc');
        $this->db->limit($filasPorPagina, $posicion);
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
        $this->db->select('usuarios.nombre, usuarios.dni, usuarios.saldo, usuarios.lu, usuarios.ruta_foto, usuarios.email, usuarios.id_provincia, usuarios.id_facultad, usuarios.id_perfil, usuarios.id_categoria, perfiles.nombre as perfil_nombre, usuarios.estado, provincias.nombre as provincia_nombre, facultades.nombre as facultad_nombre, categorias.nombre as categoria_nombre, categorias.importe as importe');
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

    function add_tickets_log($registro){
        $this->db->set($registro);
        $this->db->insert('tickets_log_usuarios');
    }

    function get_total_rows($nombre, $dni, $lu){
        $nombre = strtolower($nombre);
        $this->db->select('COUNT(nombre) as total_rows');
        $this->db->from('usuarios');
        $this->db->where("LOWER(nombre) LIKE '%{$nombre}%'");
        $this->db->like('dni', $dni, 'after');
        $this->db->like('lu', $lu, 'after');
        $query = $this->db->get();
        return $query->row('total_rows');
    }

}   