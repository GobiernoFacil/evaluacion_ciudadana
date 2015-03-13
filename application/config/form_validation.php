<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * form_validation - config 
 * -----------------------------------------------------------
 * Aquí se definen las reglas para el formulario de contacto
 *
 * @author	 Gobierno Fácil <howdy@gobiernofacil.com>
 * @version 1.0
 * @see http://codeigniter.com/user_guide/libraries/form_validation.html#savingtoconfig
 */
 
 
$config = array(
	
	/**
		*	C O N T A C T -  F O R M 
	*/
	'contact-form' => array(
		array(
			'field' => 'name',
			'label' => 'nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|required|valid_email'
		),
		array(
			'field' => 'message',
			'label' => 'mensaje',
			'rules' => 'trim|required|min_length[6]|xss_clean'
		)
	), // - termina admin-login-authorize	

);



/* fin del archivo form_validation.php
que se encuentra en config/form_validation.php*/