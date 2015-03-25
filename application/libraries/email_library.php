<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * email_library - library
 * -----------------------------------------------------------
 * Esta librería se encarga de enviar el formulario de contacto
 * @author	 Gobierno Fácil <howdy@gobiernofacil.com>
 * @version 1.0
 *
 */
 
class Email_library {
	

	/**
	*  	Constructor
 	* 	------------------------------------------------------------------------------
 	*	
 	*/
	function __construct()
    {
		$CI	=& get_instance();
		$CI->config->load('email_credentials');
		$CI->config->load('email_view_paths');
		$CI->load->library('email');
    }
	
	/**
 	* send_contact_message
	* 	------------------------------------------------------------------------------
	* correo para contacto
 	*
 	* @access	public
 	* @param	mixed	 	 $data		    info de contacto
	* @return	bool	     TRUE en caso de enviar el correo, FALSE en caso contrario
 	*/
	
	
	public function send_contact_message($data){
		
		$CI	=& get_instance();
		// se configura el correo
		$config['mailtype'] = 'html';
		$CI->email->initialize($config);
		$from_email = $CI->config->item('credential_general_email');
		$from_name	= $CI->config->item('credential_general_name');
		$CI->email->from($from_email,$from_name);
		$CI->email->to($CI->config->item('credential_from_email'));
		$CI->email->subject('Contacto en Tú Evalúas');
		$data_to_message['data'] = $data;
		$html_email = $CI->load->view($CI->config->item('email_html_contact'),$data_to_message,true);
		$CI->email->message($html_email);
		if($CI->email->send()){
			return true;
		}else{
			return false;
		}
	}


	
}

/* End of file email_library.php */
/* Location: ./application/libraries/email_library.php */