<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	// settings contact form
	protected 	$sendEmailTo 		= 	'hugo@gobiernofaciñ.com'; 

	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('email');
	}

	public function index()
	{
		$data['title'] 			= 'Tú Evalúas';
		$data['description'] 	= 'Tu opinión sobre los programas públicos federales ayuda a mejorarlos.';
		$data['body_class'] 	= 'home';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/home_view');
		$this->load->view('/templates/footer_view');
	}
	
	//about
	public function about()
	{
		$data['title'] 			= 'Qué es Tú Evalúas';
		$data['description'] 	= 'Tú evalúas es una iniciativa dirigida a los beneficiarios de los programas públicos federales.';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/about_view');
		$this->load->view('/templates/footer_view');
	}
	
	//preguntas
	public function preguntas()
	{
		$data['title'] 			= 'Preguntas Frecuentes sobre Tú Evalúas';
		$data['description'] 	= 'Algunas de las preguntas frecuentes de la plataforma Tú Evalúas';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/preguntas_view');
		$this->load->view('/templates/footer_view');
	}
	
	//terms
	public function terms()
	{
		$data['title'] 			= 'Términos y condiciones de Tú Evalúas';
		$data['description'] 	= 'Términos y condiciones de la plataforma Tú Evalúas';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/terms_view');
		$this->load->view('/templates/footer_view');
	}
	
	//privacy
	public function privacy()
	{
		$data['title'] 			= 'Aviso de Privacidad de Tú Evalúas';
		$data['description'] 	= 'Aviso de Privacidad de la plataforma Tú Evalúas';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/privacy_view');
		$this->load->view('/templates/footer_view');
	}
	
	//contact
	public function contact()
	{
		$this->subjectLine = "Mensaje enviado desde " . $_SERVER['HTTP_HOST'];
		
		if($this->form_validation->run("contact-form") == FALSE) 
		{
			$data['title'] 			= 'Contacto en Tú Evalúas';
			$data['description'] 	= 'Envía un mensaje a la plataforma Tú Evalúas';
		
			$this->load->view('/templates/header_view', $data);
			$this->load->view('/home/contact_view');
			$this->load->view('/templates/footer_view');
		} 
		else 
		{		
			if ($this->send_mail()) {
				$data['title'] 			= 'Mensaje enviado a Tú Evalúas';
				$data['description'] 	= 'Tu mensaje ha sido enviado a la plataforma Tú Evalúas';
		
				$this->load->view('/templates/header_view', $data);
				$this->load->view('/home/contact_success_view');
				$this->load->view('/templates/footer_view');
			}
			else { 
				log_message("error","Contact form - Error enviado el mensaje: " . $this->email->print_debugger() . " De: {$this->input->post('email')}. Mensaje: {$this->input->post('message')}");
				$data['title'] 			= 'Contacto en Tú Evalúas';
				$data['description'] 	= 'Envía un mensaje a la plataforma Tú Evalúas';
		
				$this->load->view('/templates/header_view', $data);
				$this->load->view('/home/contact_view');
				$this->load->view('/templates/footer_view');
			
			}
			
			
		}
	}
	
	/* HELPER FUNCTIONS */
	// send_mail
	protected function send_mail() {
			$this->email->from($this->input->post('email'), $this->input->post('name'));
			$this->email->to($this->sendEmailTo);
			$this->email->subject($this->subjectLine);
			$this->email->message($this->input->post('message'));
			return $this->email->send();
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */