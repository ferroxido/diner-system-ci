<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	protected $filasPorPagina = 200;
	protected $primeraPagina = 1;

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->model('Model_Usuarios');
		$this->load->model('Model_Tickets');
		$this->load->library('usuarioLib');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('valid_email', '%s no es un email válido');
		$this->form_validation->set_message('no_repetir_usuario_insertar', 'Existe otro registro con el mismo nombre');
		$this->form_validation->set_message('max_length', '%s debe ser de a lo sumo %s números');
		$this->form_validation->set_message('numeric', '%s debe ser un valor numérico');
	}

	//Para el usuario administrador
	public function index(){
		$data['contenido'] = 'usuarios/index';
		$nombre = '';
		$dni = '';
		$lu = '';
		$totalRows = $this->Model_Usuarios->get_total_rows($nombre, $dni, $lu);
		$data['numeroPaginas'] = ceil($totalRows / $this->filasPorPagina);
		$data['registros'] = $this->Model_Usuarios->all($this->filasPorPagina, $this->primeraPagina);
		$this->load->view('template-admin', $data);
	}

	public function get_total_pages(){
		if ($this->input->is_ajax_request()){
			$nombre = $this->input->post('nombre');
			$dni = $this->input->post('dni');
			$lu = $this->input->post('lu');
			$totalRows = $this->Model_Usuarios->get_total_rows($nombre, $dni, $lu);
			echo ceil($totalRows / $this->filasPorPagina);
		}
	}

	public function search($page_num){
		if($this->input->is_ajax_request()){
			$nombre = $this->input->post('nombre');
			$dni = $this->input->post('dni');
			$lu = $this->input->post('lu');
			$posicion = (($page_num - 1) * $this->filasPorPagina);
			$query = $this->Model_Usuarios->all_filter($nombre, $dni, $lu, $this->filasPorPagina, $posicion);
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
	public function edit($dni){
		$data['contenido'] = 'usuarios/edit';
		$data['registro'] = $this->Model_Usuarios->find($dni);
		$data['provincias'] = $this->Model_Usuarios->get_provincias();//Obtener lista de provincias
		$data['facultades'] = $this->Model_Usuarios->get_facultades();//Obtener lista de facultades
		$data['perfiles'] = $this->Model_Usuarios->get_perfiles();//Obtener lista de perfiles
		$data['categorias'] = $this->Model_Usuarios->get_categorias();//Obtener lista de categorías
		$this->load->view('template-admin',$data);
	}

	//Para el usuario administrador
	public function update(){
		if($this->input->post('dni')){
			$registro = $this->input->post();

			$this->form_validation->set_rules('nombre', 'Nombre', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('dni', 'Usuario', 'required|callback_no_repetir_usuario_update');
			$this->form_validation->set_rules('lu', 'Libreta Universitaria', 'required|max_length[7]|numeric');
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
		$this->load->view('template-admin',$data);
	}

	//Para el usuario administrador
	public function insert(){
		if($this->input->post('nombre')){
			$registro = $this->input->post();

			$this->form_validation->set_rules('dni', 'Usuario', 'required|max_length[8]|numeric|callback_no_repetir_usuario');
			$this->form_validation->set_rules('nombre', 'Nombre', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('lu', 'Libreta Universitaria', 'required|max_length[7]|numeric');
			if($this->form_validation->run() == FALSE){
				//Si no cumplio alguna de las reglas
				$this->create();
			}else{
				//Los datos son correctos. Intentamos enviar en mail. Registramos el usuarios.
				$nombre = $this->input->post('nombre');
				$email = $this->input->post('email');
				$password_generada = $this->usuariolib->generarPassword(10);//Generamos un password aleatorio de 10 caracteres.
				$this->usuariolib->enviar_email($nombre, $email, $password_generada);//Intentamos enviar el mail. Si falla, lo registramos de todas formas.
				//El registro está ok, entonces lo agregamos a la tabla usuarios
				$registro['password'] = $this->usuariolib->encriptar($password_generada);
				$registro['estado'] = 1;//0 = bloqueado, 1 = registrado, 2 = activo, 3 = suspendido
				
				$this->Model_Usuarios->insert($registro);
				$dni = $this->input->post('dni');
				//Registro el log de usuario para registro
				$fecha_log = date('Y/m/d H:i:s');
				$this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'registrar');

				redirect('usuarios/index');
			}
		}else{
			show_404();
		}
	}

	//Para el usuario alumnos
	public function comprar_tickets($year = null, $month = null){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$data['contenido'] = 'usuarios/comprar_tickets';
			$data['calendario'] = $this->Model_Usuarios->generate($year, $month);
			$this->load->view('template_usuario', $data);
		}
	}

	public function realizar_compra(){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			//Si estamos en este punto, es porque el hay días disponibles y el usuario realizó correctamente todo.
			$dias = $this->input->post('datos');//$dias contiene todos los dias seleccionados por el usuario
			$year = $this->input->post('year');
			$month = $this->input->post('month');

			$fecha_log = date('Y/m/d H:i:s');//Necesitaré la fecha para luego recuperar el id del log
			
			//La siguiente función realiza la compra de los tickets.
			$this->usuariolib->realizar_compra($dni, $fecha_log, $dias, $year, $month);//Aquí necesito la fecha del log.

			echo "Se enviaron los datos";
		}else{
			echo "Hubo un error";
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
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$this->load->view('template_usuario', $data);
		}
	}

	public function admin(){
		$data['contenido'] = 'usuarios/admin';
		$this->load->view('template-admin', $data);
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
			$this->load->view('template_usuario', $data);
		}
	}

	//Para el usuario admin y alumno
	public function editando_perfil(){
		if($this->input->post('dni')){
			$registro = $this->input->post();

			$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[45]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[64]');
			$this->form_validation->set_rules('dni', 'DNI', 'required|callback_no_repetir_usuario_update');
			if($this->form_validation->run() == FALSE){
				//Si no cumplio alguna de las reglas
				$this->editar_perfil();
			}else{
				$this->Model_Usuarios->update($registro);//Actualizo el registro
				//Cargo el log de usuarios para editar perfil
				$dni = $this->input->post('dni');
				$fecha_log = date('Y/m/d H:i:s');
				$this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'perfil');//Registrar el log
				redirect('usuarios/alumno');
			}
		}else{
			show_404();
		}
	}

	public function subir_foto(){
		if($this->session->userdata('dni_usuario') != null){
			//Tratamos de subir la imagen al servidor.
			$dni = $this->session->userdata('dni_usuario');

			$config['upload_path'] = './img/fotos-usuarios/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '2048';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';
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
				$info = $this->upload->data();
				$registro['dni'] = $dni;
				$registro['ruta_foto'] = base_url('img/fotos-usuarios/'.$info['file_name']);
				$this->Model_Usuarios->update($registro);
				redirect('usuarios/editar_perfil');
			}
		}
	}

	public function anular(){
		if($this->session->userdata('dni_usuario') != null){
			$dni = $this->session->userdata('dni_usuario');
			$data['contenido'] = 'usuarios/anular';
			$data['registro'] = $this->Model_Usuarios->find($dni);
			$this->load->model('Model_Tickets');
			$data['tickets_proximos'] = $this->Model_Tickets->get_tickets_proximos($dni);
			$this->load->view('template_usuario', $data);
		}
	}
	
	public function anulando_ticket($id_ticket){
		if($this->session->userdata('dni_usuario') != null){
			//Realizar anulación
			$dni = $this->session->userdata('dni_usuario');
			$this->usuariolib->realizar_anulacion($id_ticket,$dni);
			redirect('usuarios/anular');
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
	 * Procesa la petición producida al leer el código de barra de los tickets.
	 * Devuelve los datos del ticket y del usuario que compró el ticket.
	 */
	public function procesar_barcode(){
		if($this->input->is_ajax_request()){
			$barcode = $this->input->post('barcode');
			if(strlen($barcode) <= 10 && is_numeric($barcode)){
				$id_ticket = (int) $barcode;
			}else{
				$id_ticket = $this->usuariolib->obtener_id_ticket($barcode);	
			}
			//Obtener información del ticket en cuestión.
			$query = $this->Model_Usuarios->get_tickets_control($id_ticket);

			$estadoImpreso = 2;
			$estadoConsumido = 3;
			$estadoVencido = 4;
			$hoy = date('Y-m-d 00:00:00');

			if($query->row('estado') == $estadoImpreso && $query->row('fecha') == $hoy){
				$data['id'] = $id_ticket;
				$data['estado'] = $estadoConsumido;
				$this->Model_Tickets->update($data);
				//Cargamos el log de usuario para consumir
				$dni = $query->row('dni');
				$fecha_log = date('Y/m/d H:i:s');
				$id_log = $this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'consumir');//Registrar el log

				//Cargo la tabla tickets_log_usuarios
				$registro = array();
				$registro['id_ticket'] = $id_ticket;
				$registro['id_log_usuario'] = $id_log;
				$this->Model_Usuarios->add_tickets_log($registro);
			}elseif($query->row('estado') == $estadoImpreso && $query->row('fecha') < $hoy){
				$data['id'] = $id_ticket;
				$data['estado'] = $estadoVencido;
				$this->Model_Tickets->update($data);
				//Cargamos el log de usuario para ticket vencido
				$dni = $query->row('dni');
				$fecha_log = date('Y/m/d H:i:s');
				$id_log = $this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'vencer');//Registrar el log

				//Cargo la tabla tickets_log_usuarios
				$registro = array();
				$registro['id_ticket'] = $id_ticket;
				$registro['id_log_usuario'] = $id_log;
				$this->Model_Usuarios->add_tickets_log($registro);

				//Cambiamos el valor del objeto query la propiedad estado
				$query->row()->estado = $estadoVencido;
			}
			echo json_encode($query->result());
		}else{
			show_404();
		}
	}

}