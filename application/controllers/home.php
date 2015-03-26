<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	protected $estadoBloqueado = 0;
	protected $estadoRegistrado = 1;
	protected $estadoActivo = 2;
	protected $estadoSuspendido = 3;

	//Constructor
	function __construct(){
		parent::__construct();
		$this->load->library('usuarioLib');
		$this->load->model('Model_Usuarios');
		$this->form_validation->set_message('validar_tabla', 'Usted no es alumno regular de la Universidad, diríjase a la Administración del Comedor');
		$this->form_validation->set_message('required', 'Debe ingresar un valor para %s');
		$this->form_validation->set_message('loginok', 'Usuario o password incorrecto');
		$this->form_validation->set_message('valid_email', 'El email %s no es válido');
		$this->form_validation->set_message('cambiook', 'No se pudo realizar el cambio de clave');
		$this->form_validation->set_message('max_length', '%s debe tener como máximo %s números');
		$this->form_validation->set_message('numeric', '%s debe ser un valor numérico');
		$this->form_validation->set_message('no_repetir_usuario', 'Existe otro registro con el mismo nombre');
		$this->form_validation->set_message('existe_usuario', 'Usted no esta registrado, dirijase a la Administración del Comedor.');
		$this->form_validation->set_message('matches', '%s no coincide con %s');
		$this->form_validation->set_message('is_bloqueado', 'Su cuenta esta bloqueada, dirijase a la Administración del Comedor.');
		$this->form_validation->set_message('validar_caracteres', 'La contraseña que ingreso contiene caracteres no permitidos');
	}

	public function index(){
		$this->session->sess_destroy();//Destruimos cualquier session que haya quedado guardada.
		$data['contenido'] = 'home/index';
		$this->load->view('template-index', $data);
	}

	public function acerca_de(){
		$data['contenido'] = 'home/acerca_de';
		$this->load->view('template-index', $data);//Cargamos la vista y el template
	}

	public function acceso_denegado(){
		$data['contenido'] = 'home/acceso_denegado';
		$this->load->view('template-index', $data);
	}

	public function ingreso(){
		$this->session->sess_destroy();//Destruimos cualquier session que haya quedado guardada.
		$data['contenido'] = 'home/ingreso';
		$data['mostrar_mensaje'] = false;
		$data['exito'] = true;
		$data['mensaje'] = "";
		$this->load->view('template-index', $data);//Cargamos la vista y el template
	}

	public function ingresar(){
		$this->form_validation->set_rules('dni', 'Usuario', 'required|callback_loginok');
		$this->form_validation->set_rules('password', 'Password', 'required|callback_is_bloqueado');
		
		if($this->form_validation->run() == FALSE){
			$this->ingreso();//No uso redirect para no perder el valor de los campos ingresados
		}else{
			if($this->session->userdata('estado_usuario') == $this->estadoRegistrado){
				//El usuario aún no esta activo. Lo mando a cambiar su clave.
				redirect('home/cambiar_clave');
			}else if($this->session->userdata('estado_usuario') == $this->estadoActivo){
				//Al ingresar lo mando a la página de inicio correspondiente a su perfil
				if($this->session->userdata('perfil_nombre') === 'Alumno'){
					redirect('usuarios/alumno');
				}else if($this->session->userdata('perfil_nombre') === 'Administrador' || $this->session->userdata('perfil_nombre') === 'Super Administrador' || $this->session->userdata('perfil_nombre') === 'Consulta'){
					redirect('usuarios/admin');
				}else{
					redirect('usuarios/control');
				}
			}else{
				redirect('usuarios/bloqueado');
			}
		}
	}

	public function is_bloqueado(){
		$dni = $this->input->post('dni');
		return $this->usuariolib->is_bloqueado($dni);
	}

	public function loginok(){
		$dni = $this->input->post('dni');
		$password = $this->input->post('password');
		return $this->usuariolib->loginok($dni,$password);
	}

	public function salir(){
		$this->session->sess_destroy();
		redirect('home/index');
	}

	public function cambiar_clave(){		
		$data['contenido'] = 'home/cambiar_clave';
		$this->load->view('template-index', $data);
	}

	public function validar_caracteres(){
		$clave_nueva = $this->input->post('clave_nueva');
		$clave_repetida = $this->input->post('clave_repetida');
		return $this->usuariolib->validar_caracteres_password($clave_nueva, $clave_repetida);
	}

	public function cambiando_clave(){
		$this->form_validation->set_rules('clave_nueva', 'Clave Nueva', 'required|matches[clave_repetida]');
		$this->form_validation->set_rules('clave_actual', 'Clave Actual', 'required|callback_cambiook');
		$this->form_validation->set_rules('clave_repetida', 'Repita Clave', 'required|callback_validar_caracteres');
		
		if($this->form_validation->run() == FALSE){
			//No se pudo realizar el cambio
			$this->cambiar_clave();//No uso redirect para no perder el valor de los campos ingresados
		}else{
			//Cambio con exito
			//Hacemos el cambio de clave, y seteamos el estado en 2 de activo.
			$nueva = $this->usuariolib->encriptar($this->input->post('clave_nueva'));
			$dni = $this->session->userdata('dni_usuario');
			$data = array('dni' => $dni, 'password' => $nueva, 'estado' => 2);//Mandamos el dni porque la consulta necesita ubicar el registro que se va a modificar.
			$this->Model_Usuarios->update($data);
			
			if($this->session->userdata('perfil_nombre') === 'Alumno'){
				redirect('usuarios/alumno');
			}else if($this->session->userdata('perfil_nombre') === 'Control'){
				redirect('usuarios/control');
			}else{
				redirect('usuarios/admin');
			}
		}
	}

	public function recordar_clave(){
		$data['contenido'] = 'home/recordar_clave';
		$data['mostrar_mensaje'] = FALSE;
		$this->load->view('template-index', $data);//Cargamos la vista y el template
	}

	public function existe_usuario(){
		$registro = $this->input->post();
		return $this->usuariolib->existe_usuario($registro);
	}

	public function recordando_clave(){
		$dni = $this->input->post('dni');
		$email = $this->input->post('email');
		$lu = $this->input->post('lu');

		$this->form_validation->set_rules('dni', 'Usuario', 'required|callback_existe_usuario');
		$this->form_validation->set_rules('lu', 'Libreta', 'required|max_length[6]|numeric');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if($this->form_validation->run() == FALSE){
			$this->recordar_clave();//No uso redirect para no perder el valor de los campos ingresados
		}else{
			//Intentamos mandar el mail con la nueva contraseña
			$password_generada = $this->usuariolib->generarPassword(10);//Generamos un password aleatorio de 10 caracteres.	
			$usuario = $this->Model_Usuarios->find_simple($dni, $lu, $email);//Recupero los datos
			$nombre = $usuario->row('nombre');
			$email = $usuario->row('email');
			if($this->usuariolib->enviar_email($nombre, $email, $password_generada)){
				//Actualizo password, estado y email en el usuario.
				$registro['dni'] = $dni;
				$registro['email'] = $email;
				$registro['password'] = $this->usuariolib->encriptar($password_generada);
				$this->Model_Usuarios->update($registro);

				$mensaje = "Una nueva contraseña se ha enviado a su dirección de correo";
				$exito = true;
			}else{
				$mensaje = "Lo sentimos, no se pudo generar una nueva contraseña, intentelo de nuevo más tarde";
				$exito = false;
			}
			
			//Redireccionamos al ingreso.
			$data['contenido'] = 'home/ingreso';
			$data['mostrar_mensaje'] = TRUE;
			$data['exito'] = $exito;
			$data['mensaje'] = $mensaje;
			$this->load->view('template-index', $data);//Cargamos la vista y el template
		}
	}

	public function cambiook(){
		$actual = $this->input->post('clave_actual');
		$nueva = $this->input->post('clave_nueva');
		return $this->usuariolib->cambiarPWD($actual,$nueva);
	}

	public function registro(){
		$this->load->model('Model_Perfiles');
		$this->load->model('Model_Categorias');
		$data['contenido'] = 'home/registro';
		$data['titulo'] = 'Registro de Usuario';
		$data['provincias'] = $this->Model_Usuarios->get_provincias();
		$data['facultades'] = $this->Model_Usuarios->get_facultades();
		$data['perfil'] = $this->Model_Perfiles->findNombre('Alumno');
		$data['categoria'] = $this->Model_Categorias->findNombre('Regular');

		$this->load->view('template-index', $data);
	}

	public function no_repetir_usuario(){
		return $this->usuariolib->no_repetir_usuario($this->input->post(), 'insertar');
	}

	public function validar_tabla(){
		$registro = $this->input->post();
		return $this->usuariolib->validar_tabla($registro);
	}

	public function registrarse(){
		$registro = $this->input->post();

		$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[45]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[64]');
		$this->form_validation->set_rules('dni', 'Usuario', 'required|max_length[10]|numeric|callback_no_repetir_usuario');
		$this->form_validation->set_rules('lu', 'Libreta', 'required|max_length[8]|numeric|callback_validar_tabla');

		if($this->form_validation->run() == FALSE){
			//Fallo alguna validación
			$this->registro();
		}else{
			//Los datos son correctos.
			$nombre = $this->input->post('nombre');
			$email = $this->input->post('email');
			$password_generada = $this->usuariolib->generarPassword(10);//Generamos un password aleatorio de 10 caracteres.

			$data = array();//limpiamos el array.

			//Intentamos enviar en mail.
			if ($this->usuariolib->enviar_email($nombre, $email, $password_generada)){
				//Registramos el usuario.
				$registro['password'] = $this->usuariolib->encriptar($password_generada);
				
				$lu = $registro['lu'];
				$registro['estado'] = $this->usuariolib->definir_estado($lu);//Define el estado según las materias aprobadas.
				
				$this->Model_Usuarios->insert($registro);
				$dni = $this->input->post('dni');
				//Registro el log de usuario para registro
				$fecha_log = date('Y/m/d H:i:s');
				$this->usuariolib->cargar_log_usuario($dni, $fecha_log, 'registrar');

				//Mensaje de confirmación.
				$mensaje = "Registración exitosa, revise su correo para obtener la contraseña";
				$exito = true;
			}else{
				$mensaje = "Lo sentimos, no se pudo registrar, intente de nuevo más tarde.";	
				$exito = false;
			}
			$data['contenido'] = 'home/ingreso';
			$data['mostrar_mensaje'] = TRUE;
			$data['exito'] = $exito;
			$data['mensaje'] = $mensaje;
			$this->load->view('template-index', $data);//Cargamos la vista y el template
		}
	}

}
