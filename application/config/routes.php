<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']  = "home";
$route['404_override']        = '';
$route['cuestionario/(:any)'] = 'form_application/index/$1';

$route['municipios/(:num)']  = 'question_options/get_cities/$1';
$route['localidades/(:num)/(:num)'] = 'question_options/get_localities/$1/$2';
							  	
$route['resultados']           = 'open_data';
$route['resultados/(:any)']    = 'open_data/index/$1/$2';
$route['respuestas']           = 'survey_handler/index';
$route['que-es']               = 'home/about';
$route['preguntas-frecuentes'] = 'home/preguntas';
$route['terminos']             = 'home/terms';
$route['privacidad']           = 'home/privacy';
$route['contacto']             = 'home/contact';

// ADMIN AMIGO
$route['bienvenido']             = 'wackyland/login';
$route['bienvenido/tuevaluas']   = 'wackyland/tuevaluas';

// SURVEY APP
$route['surveys/title/update']    = 'wackyland/surveys/update_title';
$route['surveys/question/(:num)'] = 'wackyland/surveys/update_question/$1';
$route['surveys/question']        = 'wackyland/surveys/add_question';

/* End of file routes.php */
/* Location: ./application/config/routes.php */