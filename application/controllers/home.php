<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$data['title'] 			= 'Política de Privacidad de Tú Evalúas';
		$data['description'] 	= 'Política de Privacidad de la plataforma Tú Evalúas';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/privacy_view');
		$this->load->view('/templates/footer_view');
	}
	
	//contact
	public function contact()
	{
		$data['title'] 			= 'Contacto en Tú Evalúas';
		$data['description'] 	= 'Envía un mensaje a la plataforma Tú Evalúas';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/contact_view');
		$this->load->view('/templates/footer_view');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */