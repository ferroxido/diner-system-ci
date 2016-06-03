<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_usuarios extends CI_Model {

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

    function all_filter($buscar_nombre, $buscar_dni, $buscar_lu, $id_facultad, $filasPorPagina, $posicion){
        $buscar_nombre = strtolower($buscar_nombre);
        $this->db->select('usuarios.nombre, usuarios.dni, usuarios.lu, usuarios.saldo, usuarios.estado,facultades.nombre as facultad, categorias.nombre as categoria');
        $this->db->from('usuarios');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id');
        $this->db->where("LOWER(usuarios.nombre) LIKE '%{$buscar_nombre}%'");
        if ($id_facultad != '20'){
            $this->db->where('id_facultad', $id_facultad);
        }        
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
        return $this->db->get()->row();
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

    function insert_alumnos($registro){
        $alumno['facultad'] = $this->db->select('nombre_canonico')->where('id', $registro['id_facultad'])->get('facultades')->row('nombre_canonico');
        $alumno['lu'] = $registro['lu'];
        $alumno['nombre'] = strtoupper($registro['nombre']);
        $alumno['dni'] = $registro['dni'];
        $this->db->set($alumno);
        $this->db->insert('alumnos');
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
        $this->db->where('dni',$dni);
        return $this->db->get('usuarios');
    }

    function get_perfiles(){
        $lista = array();
        $this->load->model('Model_perfiles');
        $registros = $this->Model_perfiles->all();

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
        $this->load->model('Model_provincias');
        $registros = $this->Model_provincias->all();
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_facultades(){
        $lista = array();
        $this->load->model('Model_facultades');
        $registros = $this->Model_facultades->all();
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

    function add_tickets_log($registro){
        $this->db->set($registro);
        $this->db->insert('tickets_log_usuarios');
    }

    function get_total_rows($nombre, $dni, $lu, $id_facultad){
        $nombre = strtolower($nombre);
        $this->db->select('COUNT(nombre) as total_rows');
        $this->db->from('usuarios');
        $this->db->where("LOWER(nombre) LIKE '%{$nombre}%'");
        if ($id_facultad != '20'){
            $this->db->where('id_facultad', $id_facultad);
        }
        $this->db->like('dni', $dni, 'after');
        $this->db->like('lu', $lu, 'after');
        $query = $this->db->get();
        return $query->row('total_rows');
    }

    function get_info_maquinas($limit){
        $this->db->select('maquinas.id, facultades.nombre_canonico AS facultad, estados_maquina.descripcion AS estado, maquinas.estado AS estado_num');
        $this->db->from('maquinas');
        $this->db->join('facultades', 'maquinas.ubicacion = facultades.id');
        $this->db->join('estados_maquina', 'estados_maquina.id = maquinas.estado');
        $this->db->order_by('maquinas.id', 'asc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    function get_categoria_importe($dni){
        $this->db->select('importe');
        $this->db->from('usuarios');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id');
        $this->db->where('dni', $dni);
        $query = $this->db->get();
        return $query->row('importe');
    }

    // @todo refactor query
    function get_movimientos($dni, $fecha){
        $idAccionCompra = 1;
        $idAccionAnular = 2;
        if ($fecha === ''){
            $query = $this->db->query("SELECT dias.fecha, COALESCE(dinero,0) AS dinero, COALESCE(comprados,0) AS comprados, COALESCE(anulados,0) AS anulados 
                FROM (SELECT fecha::date AS fecha FROM dias) AS dias
                LEFT JOIN 
                (SELECT COUNT(tabla.id) AS comprados, fecha::date AS fecha 
                    FROM (SELECT * FROM log_usuarios WHERE dni = '$dni' AND id_accion = '$idAccionCompra') AS tabla
                LEFT JOIN tickets_log_usuarios on  id_log_usuario = tabla.id
                GROUP BY fecha::date
                ORDER BY fecha) AS compras
                ON compras.fecha = dias.fecha
                LEFT JOIN
                (SELECT COUNT(tabla.id) AS anulados, fecha::date AS fecha FROM 
                    (SELECT * FROM log_usuarios WHERE dni = '$dni' AND id_accion = '$idAccionAnular') AS tabla
                LEFT JOIN tickets_log_usuarios on  id_log_usuario = tabla.id
                GROUP BY fecha::date
                ORDER BY fecha) AS anulaciones
                ON anulaciones.fecha = dias.fecha
                LEFT JOIN
                (SELECT fecha::date AS fecha, SUM(valor) AS dinero FROM billetes WHERE dni = '$dni' GROUP BY fecha::date) AS dinero
                ON dinero.fecha = dias.fecha
                ORDER BY fecha ASC");
        }else{
            $query = $this->db->query("SELECT dias.fecha, COALESCE(dinero,0) AS dinero, COALESCE(comprados,0) AS comprados, COALESCE(anulados,0) AS anulados 
                FROM (SELECT fecha::date AS fecha FROM dias) AS dias
                LEFT JOIN 
                (SELECT COUNT(tabla.id) AS comprados, fecha::date AS fecha 
                    FROM (SELECT * FROM log_usuarios WHERE dni = '$dni' AND id_accion = '$idAccionCompra') AS tabla
                LEFT JOIN tickets_log_usuarios on  id_log_usuario = tabla.id
                GROUP BY fecha::date
                ORDER BY fecha) AS compras
                ON compras.fecha = dias.fecha
                LEFT JOIN
                (SELECT COUNT(tabla.id) AS anulados, fecha::date AS fecha FROM 
                    (SELECT * FROM log_usuarios WHERE dni = '$dni' AND id_accion = '$idAccionAnular') AS tabla
                LEFT JOIN tickets_log_usuarios on  id_log_usuario = tabla.id
                GROUP BY fecha::date
                ORDER BY fecha) AS anulaciones
                ON anulaciones.fecha = dias.fecha
                LEFT JOIN
                (SELECT fecha::date AS fecha, SUM(valor) AS dinero FROM billetes WHERE dni = '$dni' GROUP BY fecha::date) AS dinero
                ON dinero.fecha = dias.fecha
                WHERE dias.fecha = '$fecha'
                ORDER BY fecha ASC");
        }
        return $query->result();
    }

    public function transactionAnular($id_ticket, $dni){
        //Variables de ayuda
        $estadoAnulado = 0;
        $lugarWeb = 0;
        $ticket = $this->db->where('id', $id_ticket)->get('tickets')->row();
        $importe = $ticket->importe;
        $idDia = $ticket->id_dia;
        $saldo = $this->db->where('dni', $dni)->get('usuarios')->row('saldo');
        $ticketsVendidos = $this->db->where('id', $idDia)->get('dias')->row('tickets_vendidos');
        $accion = $this->db->where('nombre_canonico', 'anular')->get('acciones')->row();
        $datosLog = array(
            'fecha'       => date('Y/m/d H:i:s'),
            'lugar'       => $lugarWeb,
            'id_accion'   => $accion->id,
            'dni'         => $dni,
            'descripcion' => $accion->nombre
        );

        //Comienzo transaccion
        $this->db->trans_start();
        //Cambio de estado el ticket
        $this->db->update('tickets', array('estado' => $estadoAnulado), array('id' => $id_ticket));
        //Incremento saldo
        $this->db->update('usuarios', array('saldo' => $saldo + $importe), array('dni' => $dni));
        //Decremento en uno tickets vendidos
        $this->db->update('dias', array('tickets_vendidos' => $ticketsVendidos-1), array('id' => $idDia));
        //Inserto log
        $this->db->insert('log_usuarios', $datosLog);
        $idLog = $this->db->insert_id();
        //Inserto relacion tickets_log_usuarios
        $this->db->insert('tickets_log_usuarios', array('id_log_usuario' => $idLog, 'id_ticket' => $id_ticket));
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }else{
            return true;
        }
    }

    public function transactionVencer($id_ticket, $dni){
        //Variables de ayuda
        $estadoVencido = 4;
        $lugarWeb = 0;
        $accion = $this->db->where('nombre_canonico', 'vencer')->get('acciones')->row();
        $datosLog = array(
            'fecha'       => date('Y/m/d H:i:s'),
            'lugar'       => $lugarWeb,
            'id_accion'   => $accion->id,
            'dni'         => $dni,
            'descripcion' => $accion->nombre
        );

        //Comienzo transaccion
        $this->db->trans_start();
        //Cambio de estado el ticket
        $this->db->update('tickets', array('estado' => $estadoVencido), array('id' => $id_ticket));
        //Inserto log
        $this->db->insert('log_usuarios', $datosLog);
        $idLog = $this->db->insert_id();
        //Inserto relacion tickets_log_usuarios
        $this->db->insert('tickets_log_usuarios', array('id_log_usuario' => $idLog, 'id_ticket' => $id_ticket));
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }else{
            return true;
        }
    }

    public function get_usuarios_saldos($filasPorPagina, $posicion){
        $this->db->select('dni, usuarios.nombre, lu, facultades.nombre as facultad, categorias.nombre as categoria, saldo');
        $this->db->from('usuarios');
        $this->db->join('facultades', 'usuarios.id_facultad = facultades.id');
        $this->db->join('categorias', 'usuarios.id_categoria = categorias.id');
        $this->db->where('saldo >', 0);
        $this->db->order_by('facultades.nombre');
        $this->db->limit(1000);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_total_usuarios_con_saldo(){
        $this->db->select('COUNT(dni) as total_rows');
        $this->db->from('usuarios');
        $this->db->where('saldo >', 0);
        $query = $this->db->get();
        return $query->row('total_rows');
    }
}
