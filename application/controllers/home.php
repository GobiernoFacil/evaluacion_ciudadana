<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$this->load->view('/home/home_view');
	}
	
	public function about()
	{
		$this->load->view('/home/about_view');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */