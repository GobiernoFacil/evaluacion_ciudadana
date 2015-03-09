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
	
	public function about()
	{
		$data['title'] 			= 'Qué es Tú Evalúas';
		$data['description'] 	= 'Tú evalúas es una iniciativa dirigida a los beneficiarios de los programas públicos federales.';
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/home/about_view');
		$this->load->view('/templates/footer_view');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */