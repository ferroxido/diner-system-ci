<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Tickets extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function all($filasPorPagina, $posicion){
        $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha AS fecha, tickets.importe AS importe_ticket, estados_tickets.nombre AS estado_ticket, usuarios.dni AS dni, usuarios.nombre AS nombre_usuario
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                inner join 
                    (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios 
                ON tickets_log_usuarios.id_ticket = tickets.id
                INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                ORDER BY tickets.id DESC LIMIT '$filasPorPagina' OFFSET '$posicion'");
        return $query->result();
    }

    function all_filter($nombre, $dni, $id, $fecha, $estado, $filasPorPagina, $posicion){
        $nombre = strtolower($nombre);
        $estado = ($estado == '5')? '':$estado;
        if($fecha === ''){
            $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha AS fecha, tickets.importe AS importe_ticket, estados_tickets.nombre AS estado_ticket, usuarios.dni AS dni, usuarios.nombre AS nombre_usuario
                    FROM tickets
                    INNER JOIN dias ON tickets.id_dia = dias.id
                    INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                    INNER JOIN
                        (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios 
                    ON tickets_log_usuarios.id_ticket = tickets.id
                    INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                    INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                    WHERE LOWER(usuarios.nombre) LIKE '%{$nombre}%' AND
                    usuarios.dni LIKE '{$dni}%' AND
                    tickets.id::text LIKE '{$id}%'
                    AND tickets.estado::text LIKE '{$estado}%'
                    ORDER BY tickets.id DESC
                    LIMIT '$filasPorPagina' OFFSET '$posicion'");
        }else{
            $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha AS fecha, tickets.importe AS importe_ticket, estados_tickets.nombre AS estado_ticket, usuarios.dni AS dni, usuarios.nombre AS nombre_usuario
                    FROM tickets
                    INNER JOIN dias ON tickets.id_dia = dias.id
                    INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                    inner join 
                        (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios 
                    ON tickets_log_usuarios.id_ticket = tickets.id
                    INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                    INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                    WHERE LOWER(usuarios.nombre) LIKE '%{$nombre}%' AND
                    usuarios.dni LIKE '{$dni}%' AND
                    tickets.id::text LIKE '{$id}%'
                    AND tickets.estado::text LIKE '{$estado}%'
                    AND dias.fecha = '$fecha'
                    ORDER BY tickets.id DESC
                    LIMIT '$filasPorPagina' OFFSET '$posicion'");

        }
        return $query->result();
    }

    function find($id){
        $this->db->where('id',$id);
        return $this->db->get('tickets')->row();
    }

    function find_ticket($dni, $id_ticket){
        $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                inner join 
                    (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios
                ON tickets_log_usuarios.id_ticket = tickets.id
                INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                WHERE tickets.estado = 2 AND dni = '$dni' AND tickets.id = '$id_ticket'");
        return $query;
    }

    function get_ticket_detalle($id_ticket){
        $this->db->select('id_ticket, log_usuarios.id as id_log,log_usuarios.fecha as fecha, acciones.nombre as accion, log_usuarios.lugar as lugar');
        $this->db->from('tickets_log_usuarios');
        $this->db->join('log_usuarios', 'log_usuarios.id = tickets_log_usuarios.id_log_usuario');
        $this->db->join('acciones', 'acciones.id = log_usuarios.id_accion');
        $this->db->where('id_ticket', $id_ticket);
        $this->db->order_by('id_log_usuario', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function add_ticket($registro){
        $this->db->trans_start();
        $this->db->set($registro);
        $this->db->insert('tickets');
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function update($registro){
        $this->db->trans_start();
        $this->db->set($registro);
        $this->db->where('id',$registro['id']);
        $this->db->update('tickets');
        $this->db->trans_complete();
    }

    function delete($id){
        $this->db->where('id',$id);
        $this->db->delete('tickets');
    }

    //Devuelve todos los días para los que el usuario tiene ticket.
    function get_dias_tickets($fecha, $dni){
        $query = $this->db->query("SELECT dias.fecha,extract(day FROM dias.fecha) AS dia, tickets.id AS id_ticket,log_usuarios.dni FROM dias LEFT JOIN tickets ON dias.id = tickets.id_dia LEFT JOIN tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket LEFT JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario WHERE dni = '$dni' AND dias.fecha::text LIKE '$fecha' AND estado = 1");
        return $query->result();
    }

    //Obtener los 5 tickets próximos al día de hoy si los tiene.
    function get_tickets_anulables($dni){
        $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha AS fecha, tickets.importe AS importe, estados_tickets.nombre AS estado
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                inner join 
                    (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios
                ON tickets_log_usuarios.id_ticket = tickets.id
                INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                WHERE tickets.estado = 2 AND dni = '$dni'
                ORDER BY tickets.id ASC");
        return $query->result();
    }

    function servicios_tickets($desde, $hasta){
        $query = $this->db->query("SELECT facultades.nombre AS facultad,
            COUNT(tabla.id_tickets) AS total_tickets,
            COUNT(CASE WHEN id_categoria = 1 THEN 1 END) AS becados,
            COUNT(CASE WHEN id_categoria = 2 THEN 1 END) AS regulares,
            COUNT(CASE WHEN id_categoria = 3 THEN 1 END) AS gratuitos,
            COALESCE(SUM(tabla.importe),0) AS total_pesos FROM facultades
            LEFT JOIN (SELECT tickets.id AS id_tickets,id_facultad, id_categoria,tickets.importe AS importe
                FROM tickets INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN  (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
                INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
                INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                WHERE tickets.estado <> 0 AND dias.fecha BETWEEN '$desde' AND '$hasta') AS tabla ON tabla.id_facultad = facultades.id GROUP BY facultades.nombre");

        return $query->result();
    }

    function get_total_servicios($desde, $hasta){
        $query = $this->db->query("SELECT COUNT(tickets.id) AS total_tickets,
            COUNT(CASE WHEN id_categoria = 1 THEN 1 END) AS becados,
            COUNT(CASE WHEN id_categoria = 2 THEN 1 END) AS regulares,
            COUNT(CASE WHEN id_categoria = 3 THEN 1 END) AS gratuitos,
            COALESCE(SUM(tickets.importe),0) AS total_importe FROM tickets
            INNER JOIN dias ON tickets.id_dia = dias.id
            INNER JOIN (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
            INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
            INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
            WHERE tickets.estado <> 0 AND dias.fecha BETWEEN '$desde' AND '$hasta'");
        return $query;
    }

    function clasificacion_tickets($desde, $hasta){
        $query = $this->db->query("SELECT facultades.nombre AS facultad, 
            COUNT(tabla.id_tickets) as total_tickets,
            COUNT(CASE WHEN estado = 0 THEN 1 END) as anulados,
            COUNT(CASE WHEN estado = 1 THEN 1 END) as activos,
            COUNT(CASE WHEN estado = 2 THEN 1 END) as impresos,
            COUNT(CASE WHEN estado = 3 THEN 1 END) as consumidos 
            FROM facultades LEFT JOIN
                (SELECT tickets.id AS id_tickets,id_facultad, id_categoria,tickets.estado AS estado 
                    FROM tickets INNER JOIN dias ON tickets.id_dia = dias.id 
                    INNER JOIN (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
                    INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
                    INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                    WHERE dias.fecha BETWEEN '$desde' AND '$hasta') AS tabla 
                    ON tabla.id_facultad = facultades.id 
                    GROUP BY facultades.nombre");
        return $query->result();
    }

    function get_total_tickets($desde, $hasta){
        $query = $this->db->query("SELECT COUNT(tickets.id) AS total_tickets,
                COUNT(CASE WHEN tickets.estado = 0 THEN 1 END) AS anulados,
                COUNT(CASE WHEN tickets.estado = 1 THEN 1 END) AS activos,
                COUNT(CASE WHEN tickets.estado = 2 THEN 1 END) AS impresos,
                COUNT(CASE WHEN tickets.estado = 3 THEN 1 END) AS consumidos
                FROM tickets INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
                INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
                INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                WHERE dias.fecha BETWEEN '$desde' AND '$hasta'");
        return $query;
    }

    function get_tickets_por_dias($month, $year){
        $query = $this->db->query("SELECT dias.fecha AS dias,
            COUNT(tabla.id_tickets) AS cantidad_tickets,
            COUNT(CASE WHEN id_categoria = 1 THEN 1 END) AS cantidad_becados,
            COUNT(CASE WHEN id_categoria = 2 THEN 1 END) AS cantidad_regulares,
            COUNT(CASE WHEN id_categoria = 3 THEN 1 END) AS cantidad_gratuitos,
            COALESCE(SUM(CASE WHEN id_categoria = 1 THEN tabla.importe END),0) AS subtotal_becados,
            COALESCE(SUM(CASE WHEN id_categoria = 2 THEN tabla.importe END),0) AS subtotal_regulares,
            COALESCE(SUM(CASE WHEN id_categoria = 3 THEN tabla.importe END),0) AS subtotal_gratuitos,
            COALESCE(SUM(tabla.importe),0) AS total_tickets FROM dias 
            LEFT JOIN (SELECT tickets.id_dia AS id_dia,tickets.id AS id_tickets,id_facultad, id_categoria,tickets.importe AS importe 
            FROM tickets INNER JOIN (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
            INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
            INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni 
            WHERE tickets.estado <> 0) AS tabla 
            ON tabla.id_dia = dias.id 
            WHERE DATE_PART('month', dias.fecha) = '$month' AND DATE_PART('year', dias.fecha) = '$year'
            GROUP BY dias.fecha
            ORDER BY dias.fecha");
        return $query->result();
    }

    function get_total_consumidos_hoy($hoy){
        $estadoConsumido = 3;
        $query = $this->db->query("SELECT COUNT(tickets.id) AS total_consumidos_hoy FROM tickets 
            INNER JOIN dias ON tickets.id_dia = dias.id WHERE fecha = '$hoy' and tickets.estado = '$estadoConsumido'");
        return $query->row('total_consumidos_hoy');
    }

    function get_estados(){
        $lista = array();
        $registros = $this->db->get('estados_tickets')->result();
        $lista[5] = 'Todos';
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_total_rows($nombre, $dni, $id, $fecha, $estado){
        $nombre = strtolower($nombre);
        $estado = ($estado == '5')? '':$estado;
        if($fecha == ''){
            $query = $this->db->query("SELECT COUNT(tickets.id) AS total_tickets 
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
                INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
                INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                WHERE usuarios.nombre LIKE '%{$nombre}%' AND usuarios.dni LIKE '{$dni}%' AND tickets.id::text LIKE '{$id}%' AND tickets.estado::text LIKE '{$estado}%'");
        }else{
            $query = $this->db->query("SELECT COUNT(tickets.id) AS total_tickets 
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN (SELECT distinct on(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios ON tickets.id = tickets_log_usuarios.id_ticket
                INNER JOIN log_usuarios ON log_usuarios.id = tickets_log_usuarios.id_log_usuario
                INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
                WHERE usuarios.nombre LIKE '%{$nombre}%' AND usuarios.dni LIKE '{$dni}%' AND tickets.id::text LIKE '{$id}%' AND tickets.estado::text LIKE '{$estado}%' AND dias.fecha = '$fecha'");
        }
        return $query->row('total_tickets');
    }

}