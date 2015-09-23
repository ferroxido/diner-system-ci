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
        $estado = ($estado == '10')? '':$estado;
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

    function get_tickets_anulables($dni){
        $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha AS fecha, tickets.importe AS importe, estados_tickets.nombre AS estado
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                INNER JOIN
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
        $lista[10] = 'Todos';
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_estados_alumnos(){
        $lista = array();
        $this->db->where('id <>', 5);
        $registros = $this->db->get('estados_tickets')->result();
        $lista[10] = 'Todos';
        foreach($registros as $registro){
            $lista[$registro->id] = $registro->nombre;
        }
        return $lista;
    }

    function get_total_rows($nombre, $dni, $id, $fecha, $estado){
        $nombre = strtolower($nombre);
        $estado = ($estado == '10')? '':$estado;
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

    function mis_tickets($dni, $estado){
        $estadoCancelado = 5;
        $estado = ($estado == '10')? '':$estado;
        $query = $this->db->query("SELECT tickets.id AS id_ticket, dias.fecha AS fecha, estados_tickets.nombre AS estado
                FROM tickets
                INNER JOIN dias ON tickets.id_dia = dias.id
                INNER JOIN estados_tickets on tickets.estado = estados_tickets.id
                INNER JOIN
                    (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios
                ON tickets_log_usuarios.id_ticket = tickets.id
                INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
                WHERE dni = '$dni'
                AND tickets.estado <> $estadoCancelado
                AND tickets.estado::text LIKE '{$estado}%'
                ORDER BY tickets.id DESC");
        return $query->result();
    }

    function consumo_tickets_por_dia($mes, $fecha){
        $hoy = date('Y-m-d');
        $todosLosMeses = 13;
        $this->db->select("
            dias.fecha, 
            COUNT(CASE WHEN estados_tickets.nombre = 'Anulado' THEN 1 END) AS anulados,
            COUNT(CASE WHEN estados_tickets.nombre = 'Impreso' THEN 1 END) AS impresos,
            COUNT(CASE WHEN estados_tickets.nombre = 'Vencido' THEN 1 END) AS vencidos,
            COUNT(CASE WHEN estados_tickets.nombre = 'Consumido' THEN 1 END) AS consumidos
        ");
        $this->db->from("dias");
        $this->db->join("tickets", "dias.id = tickets.id_dia", "left");
        $this->db->join("estados_tickets", "tickets.estado = estados_tickets.id");
        $this->db->where("dias.fecha <=", $hoy);
        if ($mes != $todosLosMeses){
            $this->db->where("date_part('month',dias.fecha)", $mes);
        }
        if($fecha != ""){
            $this->db->where('dias.fecha', $fecha);   
        }
        $this->db->group_by("dias.fecha");
        $this->db->order_by("dias.fecha", 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function get_ausentismos($fecha, $filasPorPagina, $posicion){
        $query = $this->db->query("SELECT tickets.id AS id_ticket, usuarios.nombre, usuarios.dni AS dni, usuarios.lu AS lu, facultades.nombre AS facultad, categorias.nombre AS categoria, dias.fecha
            FROM tickets
            INNER JOIN dias ON tickets.id_dia = dias.id
            INNER JOIN estados_tickets ON estados_tickets.id = tickets.estado
            INNER JOIN
                (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios
            ON tickets_log_usuarios.id_ticket = tickets.id
            INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
            INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
            INNER JOIN facultades ON facultades.id = usuarios.id_facultad
            INNER JOIN categorias ON categorias.id = usuarios.id_categoria
            WHERE dias.fecha = '$fecha'
            AND (estados_tickets.nombre = 'Impreso' 
            OR estados_tickets.nombre = 'Vencido')
            ORDER BY usuarios.nombre ASC LIMIT '$filasPorPagina' OFFSET '$posicion'");
        return $query->result();
    }

    function get_total_ausentismos($fecha){
        $query = $this->db->query("SELECT COUNT(*) AS total_rows 
            FROM tickets
            INNER JOIN dias ON tickets.id_dia = dias.id
            INNER JOIN estados_tickets ON tickets.estado = estados_tickets.id
            WHERE dias.fecha = '$fecha'
            AND (estados_tickets.nombre = 'Impreso' 
            OR estados_tickets.nombre = 'Vencido')");
        return $query->row('total_rows'); 
    }

    function get_ranking_ausentismos(){
        $query = $this->db->query("SELECT aux.cantidad_vencidos AS cantidad_ausentismos, COUNT(*) AS cantidad_personas FROM
            (SELECT usuarios.nombre AS nombre, usuarios.dni AS dni, COUNT(estados_tickets.nombre) AS cantidad_vencidos
            FROM tickets
            INNER JOIN dias ON dias.id = tickets.id_dia
            INNER JOIN estados_tickets ON tickets.estado = estados_tickets.id
            INNER JOIN
            (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios 
            ON tickets_log_usuarios.id_ticket = tickets.id
            INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
            INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
            WHERE estados_tickets.nombre = 'Vencido' OR (estados_tickets.nombre = 'Impreso' AND dias.fecha < current_date)
            GROUP BY usuarios.nombre, usuarios.dni) AS aux
            GROUP BY aux.cantidad_vencidos
            ORDER BY cantidad_ausentismos DESC
        ");
        return $query->result();
    }

    function get_detalle_ranking($cantidadAusentismo){
        $query = $this->db->query("SELECT usuarios.nombre, usuarios.dni, usuarios.lu, facultades.nombre AS facultad, categorias.nombre AS categoria
            FROM tickets
            INNER JOIN dias ON dias.id = tickets.id_dia
            INNER JOIN estados_tickets ON tickets.estado = estados_tickets.id
            INNER JOIN
            (SELECT DISTINCT ON(id_ticket) id_ticket, id_log_usuario FROM tickets_log_usuarios) AS tickets_log_usuarios 
            ON tickets_log_usuarios.id_ticket = tickets.id
            INNER JOIN log_usuarios ON tickets_log_usuarios.id_log_usuario = log_usuarios.id
            INNER JOIN usuarios ON log_usuarios.dni = usuarios.dni
            INNER JOIN facultades ON facultades.id = usuarios.id_facultad
            INNER JOIN categorias ON categorias.id = usuarios.id_categoria
            WHERE estados_tickets.nombre = 'Vencido' 
            OR (estados_tickets.nombre = 'Impreso' AND dias.fecha < current_date)
            GROUP BY usuarios.nombre, usuarios.dni, usuarios.lu, facultades.nombre, categorias.nombre HAVING COUNT(estados_tickets.nombre) = '$cantidadAusentismo'
            ORDER BY usuarios.nombre
        ");
        return $query->result();
    }

    function get_tickets_control($id_ticket, $is_grupal){
        if ($is_grupal) {
            $this->db->select('tickets_grupales.id as id_ticket, dias.fecha as fecha, tickets_grupales.id_estado as estado, tickets_grupales.importe as importe, tickets_grupales.cantidad as cantidad, tickets_grupales.delegacion as delegacion, log_usuarios.fecha as fecha_log, categorias.nombre as categoria, log_usuarios.dni as dni');
            $this->db->from('tickets_grupales');
            $this->db->join('dias', 'dias.id = tickets_grupales.id_dia');
            $this->db->join('tickets_grupales_log_usuarios', 'tickets_grupales_log_usuarios.id_ticket_grupal = tickets_grupales.id');
            $this->db->join('log_usuarios', 'log_usuarios.id = tickets_grupales_log_usuarios.id_log_usuario');
            $this->db->join('categorias', 'categorias.id = tickets_grupales.id_categoria');
            $this->db->where('tickets_grupales.id', $id_ticket);
            $this->db->order_by('log_usuarios.fecha','desc');
            $this->db->limit(1);
            $query = $this->db->get();
            return $query;
        }else {
            $this->db->select('tickets.id as id_ticket, dias.fecha as fecha, tickets.estado as estado, tickets.importe as importe, usuarios.nombre as usuario_nombre, usuarios.dni as dni, usuarios.ruta_foto as ruta, facultades.nombre as facultad, categorias.nombre as categoria, usuarios.lu as usuario_lu, log_usuarios.fecha as fecha_log');
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

    }

    function actualizar_estado_ticket($id_ticket, $dni, $nombre_canonico_accion, $estado){
        //Variables de ayuda
        $lugarWeb = 0;
        $accion = $this->db->where('nombre_canonico', $nombre_canonico_accion)->get('acciones')->row();
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
        $this->db->update('tickets', array('estado' => $estado), array('id' => $id_ticket));
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

    function actualizar_estado_ticket_grupal($id_ticket, $dni, $nombre_canonico_accion, $estado){
        //Variables de ayuda
        $lugarWeb = 0;
        $accion = $this->db->where('nombre_canonico', $nombre_canonico_accion)->get('acciones')->row();
        $datosLog = array(
            'fecha'       => date('Y/m/d H:i:s'),
            'lugar'       => $lugarWeb,
            'id_accion'   => $accion->id,
            'dni'         => $dni,
            'descripcion' => $accion->nombre
        );

        //Comienzo transaccion
        $this->db->trans_start();
        //Cambio de estado el ticket_grupal
        $this->db->update('tickets_grupales', array('id_estado' => $estado), array('id' => $id_ticket));
        //Inserto log
        $this->db->insert('log_usuarios', $datosLog);
        $idLog = $this->db->insert_id();
        //Inserto relacion tickets_grupales_log_usuarios
        $this->db->insert('tickets_grupales_log_usuarios', array('id_log_usuario' => $idLog, 'id_ticket_grupal' => $id_ticket));
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }else{
            return true;
        }
    }



}

