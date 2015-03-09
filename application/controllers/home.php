<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function about()
	{
		$this->load->view('welcome_message');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */