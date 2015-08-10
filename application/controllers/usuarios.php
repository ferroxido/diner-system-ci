<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	protected $filasPorPagina = 200;
	protected $primeraPagina = 1;
	protected $mensajeAnular = '';

	//Constructor
	function __construct(){
		parent::__construct();

		//Eliminar cache
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');

		$this->load->model('Model_Usuarios');
		$this->load->model('Model_Tickets');
		$this->load->model('Model_Facultades');
		$this->load->library('usuarioLib');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('valid_email', '%s no es un email válido');
		$this->form_validation->set_message('no_repetir_usuario_insertar', 'Existe otro registro con el mismo nombre');
		$this->form_validation->set_message('max_length', '%s debe ser de a lo sumo %s números');
		$this->form_validation->set_message('numeric', '%s debe ser un valor numérico');
		$this->form_validation->set_message('is_natural', '%s debe ser un valor numérico natural');
		$this->form_validation->set_message('caracteres_permitidos', 'El %s sólo debe contener letras.');
		$this->form_validation->set_message('parametros_permitidos_editar_perfil', 'Usted esta mandando parámetros extras.');
		$this->form_validation->set_message('parametros_permitidos_ingresar_usuario', 'Usted esta mandando parámetros extras.');
	}

	//Para el usuario administrador
	public function index(){
		$data['contenido'] = 'usuarios/index';
		$nombre = '';
		$dni = '';
		$lu = '';
		$id_facultad = 20;//Todas las facultades
		$totalRows = $this->Model_Usuarios->get_total_rows($nombre, $dni, $lu, $id_facultad);
		$data['numeroPaginas'] = ceil($totalRows / $this->filasPorPagina);
		$data['registros'] = $this->Model_Usuarios->all($this->filasPorPagina, $this->primeraPagina);
		$data['facultades'] = $this->Model_Facultades->get_facultades();//Obtener lista de facultades
		$this->load->view('tmp-admin', $data);
	}

	public function get_total_pages(){
		if ($this->input->is_ajax_request()){
			$nombre = $this->input->post('nombre');
			$dni = $this->input->post('dni');
			$lu = $this->input->post('lu');
			$id_facultad = $this->input->post('id_facultad');
			$totalRows = $this->Model_Usuarios->get_total_rows($nombre, $dni, $lu, $id_facultad);
			echo ceil($totalRows / $this->filasPorPagina);
		}
	}

	public function search($page_num){
		if($this->input->is_ajax_request()){
			$nombre = $this->input->post('nombre');
			$dni = $this->input->post('dni');
			$lu = $this->input->post('lu');
			$id_facultad = $this->input->post('id_facultad');
			$posicion = (($page_num - 1) * $this->filasPorPagina);
			$query = $this->Model_Usuarios->all_filter($nombre, $dni, $lu, $id_facultad, $this->filasPorPagina, $posicion);
			echo json_encode($query);
		}else{
			show_404();
		}

	}

	public function no_repetir_usuario_insertar(){
		return $this->usuariolib->no_repetir_usuario($this->input->post(), 'insertar');
	}

	public function no_repetir_usuario_update(){
		return $this->usuariolib->no_repetir_usuario($this->input->post(), 'update');
	}	

	//Para el usuario administrador
	public function edit($dni = null){
		if($this->usuariolib->validar_dni($dni) && $dni != null){
			$data['contenido'] = 'usuarios/edit';
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$data['provincias'] = $this->Model_Usuarios->get_provincias();//Obtener lista de provincias
			$data['facultades'] = $this->Model_Usuarios->get_facultades();//Obtener lista de facultades
			$data['perfiles'] = $this->Model_Usuarios->get_perfiles();//Obtener lista de perfiles
			$data['categorias'] = $this->Model_Usuarios->get_categorias();//Obtener lista de categorías
			$this->load->view('tmp-admin',$data);
		}else{
			show_404();
		}
	}

	//Para el usuario administrador
	public function update(){
		if($this->input->post('dni')){
			$registro = $this->input->post();

			$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[45]|callback_caracteres_permitidos');
			$this->form_validation->set_rules('email', 'Email', 'required|max_length[64]|valid_email');
			$this->form_validation->set_rules('dni', 'Usuario', 'required|is_natural|callback_no_repetir_usuario_update');
			$this->form_validation->set_rules('lu', 'Libreta Universitaria', 'required|max_length[8]|is_natural');
			if($this->form_validation->run() == FALSE){
				//Si no cumplio alguna de las reglas
				$this->edit($registro['dni']);
			}else{
				//Registro el log de usuario para registro
				$dni = $this->input->post('dni');
				$fecha_log = date('Y/m/d H:i:s');
				$this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'perfil');
				//El registro está ok, entonces lo actualizamos en la tabla usuarios
				$this->Model_Usuarios->update($registro);
				redirect('usuarios/index');
			}
		}else{
			show_404();
		}
	}

	//Para el usuario administrador
	public function create(){
		$data['contenido'] = 'usuarios/create';
		$data['provincias'] = $this->Model_Usuarios->get_provincias();//Obtener lista de provincias
		$data['facultades'] = $this->Model_Usuarios->get_facultades();//Obtener lista de facultades
		$data['perfiles'] = $this->Model_Usuarios->get_perfiles();//Obtener lista de perfiles
		$data['categorias'] = $this->Model_Usuarios->get_categorias();//Obtener lista de categorías
		$this->load->view('tmp-admin',$data);
	}

	public function parametros_permitidos_insertar_usuario(){
		$registro = $this->input->post();
		return $this->usuariolib->parametros_permitidos($registro, 8);
	}

	//Para el usuario administrador
	public function insert(){
		if($this->input->post()){
			$registro = $this->input->post();
			$this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|max_length[45]|callback_caracteres_permitidos');
			$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|valid_email|max_length[64]|callback_parametros_permitidos_ingresar_usuario');
			$this->form_validation->set_rules('dni', 'Usuario', 'required|xss_clean|max_length[10]|is_natural|callback_no_repetir_usuario_insertar');
			$this->form_validation->set_rules('lu', 'Libreta', 'required|xss_clean|max_length[8]|is_natural');
			$this->form_validation->set_rules('id_provincia', 'Provincia', 'required|is_natural');
			$this->form_validation->set_rules('id_facultad', 'Facultad', 'required|is_natural');
			$this->form_validation->set_rules('id_perfil', 'Perfil', 'required|is_natural');
			$this->form_validation->set_rules('id_categoria', 'categoria', 'required|is_natural');

			if($this->form_validation->run() == FALSE){
				//Si no cumplio alguna de las reglas
				$this->create();
			}else{
				//Si no esta en la tabla alumnos lo agregamos
				if(!$this->usuariolib->validar_tabla($registro)){
					$this->Model_Usuarios->insert_alumnos($registro);
				}
				//Intentamos enviar el mail y registramos al usuario
				$nombre = $this->input->post('nombre');
				$email = $this->input->post('email');
				$password_generada = $this->usuariolib->generarPassword(10);//Generamos un password aleatorio de 10 caracteres
				$this->usuariolib->enviar_email($nombre, $email, $password_generada);//Intentamos enviar el mail. Si falla, lo registramos de todas formas
				//El registro está ok, entonces lo agregamos a la tabla usuarios
				$registro['password'] = $this->usuariolib->encriptar($password_generada);
				$estadoRegistrado = 1;
				$registro['estado'] = $estadoRegistrado;
				$this->Model_Usuarios->insert($registro);
				//Registro el log de usuario para registro
				$dni = $this->input->post('dni');
				$fecha_log = date('Y/m/d H:i:s');
				$this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'registrar');

				redirect('usuarios/index');
			}
		}else{
			show_404();
		}
	}

	//Recupero los días para poder pintar los días NO hábiles.
	public function get_dias(){
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		//$dia = $this->input->post('dia');
		$this->load->model('Model_Dias');
		$query = $this->Model_Dias->get_dias($year.'-'.$month.'%');//Optimizar trayendo en la consulta solo los días where tickets_totales - tickets_vendidos > 0
		echo json_encode($query);
	}

	//Recupero los días que el usuario logueado tiene tickets
	public function get_dias_tickets(){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$year = $this->input->post('year');
			$month = $this->input->post('month');

			$this->load->model('Model_Tickets');
			$registros = $this->Model_Tickets->get_dias_tickets($year.'-'.$month.'%', $dni);
			echo json_encode($registros);
		}
	}

	//Para el usuario alumnos
	public function alumno(){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$data['contenido'] = 'usuarios/alumno';
			$estado = 10;//Traer todos los tickets
			$data['tickets'] = $this->Model_Tickets->mis_tickets($dni, $estado);
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$data['estados'] = $this->Model_Tickets->get_estados_alumnos();
			$this->load->view('tmp-alumnos', $data);
		}
	}

	/*
	 * Filtra los tickets de acuerdo a los parametros enviados por ajax
	 */
	public function filtrar_tickets_alumno(){
		if($this->input->is_ajax_request()){
			if($this->session->userdata('dni_usuario') != null){
				$dni = $this->session->userdata('dni_usuario');
				$estado = $this->input->post('estado');
				$query = $this->Model_Tickets->mis_tickets($dni, $estado);
				echo json_encode($query);
			}
		}else{
			show_404();
		}
	}

	public function admin(){
		$data['contenido'] = 'usuarios/admin';
		$data['maquinas'] = $this->Model_Usuarios->get_info_maquinas(6);
		$this->load->view('tmp-admin', $data);
	}

	//Para el usuario alumnos
	public function editar_perfil($mostrar = false, $error = ""){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$data['contenido'] = 'usuarios/editar_perfil';
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$data['provincias'] = $this->Model_Usuarios->get_provincias();//Obtener lista de provincias
			$data['facultades'] = $this->Model_Usuarios->get_facultades();//Obtener lista de facultades
			$data['mostrar'] = $mostrar;//Variable booleana para mostrar mensaje de error
			$data['error'] = $error;
			$this->load->view('tmp-alumnos2', $data);
		}
	}

	public function parametros_permitidos_editar_perfil(){
		$registro = $this->input->post();
		return $this->usuariolib->parametros_permitidos($registro, 4);
	}

	//Para el usuario admin y alumno
	public function editando_perfil(){
		if($this->input->post('dni')){
			$registro = $this->input->post();

			$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[45]|callback_caracteres_permitidos|callback_parametros_permitidos_editar_perfil');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[64]');
			$this->form_validation->set_rules('dni', 'DNI', 'required|is_natural|callback_no_repetir_usuario_update');
			$this->form_validation->set_rules('id_provincia', 'Provincia', 'required|is_natural');

			if($this->form_validation->run() == FALSE){
				//Si no cumplio alguna de las reglas
				$this->editar_perfil();
			}else{
				$this->Model_Usuarios->update($registro);//Actualizo el registro
				//Cargo el log de usuarios para editar perfil
				$dni = $this->input->post('dni');
				$fecha_log = date('Y/m/d H:i:s');
				$this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'perfil');//Registrar el log

				$perfil = $this->session->userdata('perfil_nombre');
				if($perfil == 'Alumno'){
					redirect('usuarios/alumno');
				}elseif($perfil == 'Administrador' || $perfil == 'Super Administrador'){
					redirect('usuarios/admin');
				}else{
					redirect('home/index');
				}
			}
		}else{
			show_404();
		}
	}

	public function caracteres_permitidos($campo){
		$expreg = '/^[a-zA-Z áéíóúAÉÍÓÚÑñ]+$/';
		return $this->usuariolib->caracteres_permitidos($campo, $expreg);
	}

	public function subir_foto(){
		if($this->session->userdata('dni_usuario') != null){
			//Tratamos de subir la imagen al servidor.
			$dni = $this->session->userdata('dni_usuario');

			$config['upload_path'] = './img/fotos-usuarios/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '2048';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$config['overwrite'] = true;
			$config['file_name'] = $dni;

			$this->load->library('upload', $config);

			if (! $this->upload->do_upload()){
				//Controlar error al subir archivo.
				$mostrar = true;
				$error = "No se pudo subir la imagen ".$this->upload->display_errors();

				$this->editar_perfil($mostrar, $error);
			}else{
				//El registro está ok, entonces lo actualizamos en la tabla usuarios
				$info = $this->upload->data();//Información de la imagen subida
				//Redimensionamos la imagen
				$config['image_library'] = 'gd2';
				$config['source_image'] = $info['full_path'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 640;
				$config['height']       = 400;

				$this->load->library('image_lib', $config);
				
				if(!$this->image_lib->resize()){
					$mostrar = true;
					$error = "No se pudo subir la imagen ".$this->image_lib->display_errors();
					$this->editar_perfil($mostrar, $error);
				}else{
					//Guardamos la imagen en la DB				
					$registro['dni'] = $dni;
					$registro['ruta_foto'] = base_url('img/fotos-usuarios/'.$info['file_name']);
					$this->Model_Usuarios->update($registro);
					redirect('usuarios/editar_perfil');
				}

			}
		}
	}

	public function anular(){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$data['contenido'] = 'usuarios/anular';
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$data['tickets'] = $this->Model_Tickets->get_tickets_anulables($dni);
			$data['mensaje'] = $this->mensajeAnular;
			$this->load->view('tmp-alumnos2', $data);
		}
	}
	
	public function anulando($id_ticket){
		if($this->session->userdata('dni_usuario') != null){
			//Realizar anulación
			$data = array();
			$dni = $this->session->userdata('dni_usuario');
			if(is_numeric($id_ticket) && $id_ticket != null){
				$respuesta = $this->usuariolib->anularConTransaccion($dni, $id_ticket);
				$this->mensajeAnular = my_mensaje_confirmacion($respuesta['mensaje'], true, $respuesta['exito']);
			}
			$this->anular();
		}
	}

	public function imprimir(){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$data['contenido'] = 'usuarios/imprimir';
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$this->load->model('Model_Tickets');
			$data['tickets_proximos'] = $this->Model_Tickets->get_tickets_proximos($dni);
			$this->load->view('template_usuario', $data);
		}	
	}

	public function descargar(){
		$this->load->helper('download');
		$data = 'Here is some text!';
		$name = 'mytext.txt';
		force_download($name, $data);
		$data['contenido'] = 'usuarios/descargar';
		$this->load->view('template_usuario', $data);
	}

	/*
	 * Acción para mostrar la interfaz del usuario de control.
	 * El usuario de control es el que se encarga de pasar los tickets por el lecto de código de barra.
	 */
	public function control(){
		$data['contenido'] = 'usuarios/control';
		$hoy = date('Y-m-d 00:00:00');
		$data['totalConsumidosHoy'] = $this->Model_Tickets->get_total_consumidos_hoy($hoy);
		$this->load->view('template_control', $data);
	}

	/*
	 * Transacciones por día de los usuarios
	 */
	public function movimientos($dni = null){
		if($this->usuariolib->validar_dni($dni) && $dni != null){
			$data['contenido'] = 'usuarios/movimientos';
			$data['categoria_importe'] = $this->Model_Usuarios->get_categoria_importe($dni);
			$fecha = '';
			$data['registros'] = $this->Model_Usuarios->get_movimientos($dni, $fecha);
			$this->load->view('tmp-admin',$data);
		}else{
			show_404();
		}
	}

	public function filtrar_movimientos(){
		if($this->input->is_ajax_request()){
			$dni = $this->input->post('dni');
			$fecha = $this->input->post('fecha');
			$data['registros'] = $this->Model_Usuarios->get_movimientos($dni, $fecha);
			$data['categoria_importe'] = $this->Model_Usuarios->get_categoria_importe($dni);
			echo json_encode($data);
		}else{
			show_404();
		}
	}	

	/*
	 * Procesa la petición producida al leer el código de barra de los tickets.
	 * Devuelve los datos del ticket y del usuario que compró el ticket.
	 */
	// public function procesar_barcode(){
	// 	if($this->input->is_ajax_request()){
	// 		$barcode = $this->input->post('barcode');

	// 		$is_grupal = false;
	// 		if (strtoupper($barcode[0]) === 'G') {
	// 			$barcode = ltrim ($barcode, 'G');
	// 			$is_grupal = true;
	// 		}

	// 		if(strlen($barcode) <= 10 && is_numeric($barcode)){
	// 			$id_ticket = (int) $barcode;
	// 		}else{
	// 			$id_ticket = $this->usuariolib->obtener_id_ticket($barcode);	
	// 		}
	// 		//Obtener información del ticket en cuestión.
	// 		$query = $this->Model_Tickets->get_tickets_control($id_ticket, $is_grupal);

	// 		$estadoImpreso = 2;
	// 		$estadoConsumido = 3;
	// 		$estadoVencido = 4;
	// 		$hoy = date('Y-m-d 00:00:00');

	// 		if($query->row('estado') == $estadoImpreso && $query->row('fecha') == $hoy){
	// 			$data['id'] = $id_ticket;
	// 			$data['estado'] = $estadoConsumido;
	// 			$this->Model_Tickets->update($data);
	// 			//Cargamos el log de usuario para consumir
	// 			$dni = $query->row('dni');
	// 			$fecha_log = date('Y/m/d H:i:s');
	// 			$id_log = $this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'consumir');//Registrar el log

	// 			//Cargo la tabla tickets_log_usuarios
	// 			$registro = array();
	// 			$registro['id_ticket'] = $id_ticket;
	// 			$registro['id_log_usuario'] = $id_log;
	// 			$this->Model_Usuarios->add_tickets_log($registro);
	// 		}elseif($query->row('estado') == $estadoImpreso && $query->row('fecha') < $hoy){
	// 			$data['id'] = $id_ticket;
	// 			$data['estado'] = $estadoVencido;
	// 			$this->Model_Tickets->update($data);
	// 			//Cargamos el log de usuario para ticket vencido
	// 			$dni = $query->row('dni');
	// 			$fecha_log = date('Y/m/d H:i:s');
	// 			$id_log = $this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'vencer');//Registrar el log

	// 			//Cargo la tabla tickets_log_usuarios
	// 			$registro = array();
	// 			$registro['id_ticket'] = $id_ticket;
	// 			$registro['id_log_usuario'] = $id_log;
	// 			$this->Model_Usuarios->add_tickets_log($registro);

	// 			//Cambiamos el valor del objeto query la propiedad estado
	// 			$query->row()->estado = $estadoVencido;
	// 		}
	// 		echo json_encode($query->result());
	// 	}else{
	// 		show_404();
	// 	}
	// }

	public function procesar_barcode(){
		if($this->input->is_ajax_request()){
			$barcode = $this->input->post('barcode');

			$is_grupal = false;
			if (strtoupper($barcode[0]) === 'G') {
				$barcode = ltrim ($barcode, 'G');
				$is_grupal = true;
			}

			if(strlen($barcode) <= 10 && is_numeric($barcode)){
				$id_ticket = (int) $barcode;
			}else{
				$id_ticket = $this->usuariolib->obtener_id_ticket($barcode);
			}

			//Obtener información del ticket en cuestión.
			$query = $this->Model_Tickets->get_tickets_control($id_ticket, $is_grupal);
			$dni = $query->row('dni');
			$estadoImpreso = 2;
			$estadoConsumido = 3;
			$estadoVencido = 4;
			$hoy = date('Y-m-d 00:00:00');

			if($query->row('estado') == $estadoImpreso && $query->row('fecha') == $hoy){

				if ($is_grupal) {
					$this->Model_Tickets->actualizar_estado_ticket_grupal($id_ticket, $dni, 'consumir', $estadoConsumido);
				}else {
					$this->Model_Tickets->actualizar_estado_ticket($id_ticket, $dni, 'consumir', $estadoConsumido);
				}

				$this->usuariolib->actualizar_estado_ticket($id_ticket, $dni, $is_grupal, 'consumir');
			}elseif($query->row('estado') == $estadoImpreso && $query->row('fecha') < $hoy){

				if ($is_grupal) {
					$this->Model_Tickets->actualizar_estado_ticket_grupal($id_ticket, $dni, 'vencer', $estadoVencido);
				}else {
					$this->Model_Tickets->actualizar_estado_ticket($id_ticket, $dni, 'vencer', $estadoVencido);
				}

				//Cambiamos el valor del objeto query la propiedad estado
				$query->row()->estado = $estadoVencido;	
			}
			echo json_encode($query->result());
		}else{
			show_404();
		}		
	}

	//Compra de tickets del alumno
	public function comprar_tickets(){

	}

	public function realizar_compra(){

	}
}