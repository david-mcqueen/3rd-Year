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

$route['user/login'] = 'user/login'; //Login
$route['user/logout'] = 'user/logout'; //Logout
$route['VerifyLogin'] = 'verifyLogin/index'; //Logout
$route['user/calendar/(:any)'] = 'user/calendar/$1'; //Logged in main calendar view
$route['user/settings'] = 'user/settings'; //User settings
$route['user/updateSettings'] = 'user/updateSettings';

$route['shift/addShift?(:any)'] = 'shift/addShift'; //AJAX used to add a shift
$route['shift/getCalendar?(:any)'] = 'shift/ajaxCalendar'; //AJAX used to get calendar events

$route['404_override'] = 'errors/page_missing'; //Error page
$route['default_controller'] = 'user/login'; //Default if no other route matches

$route['verifyLogin'] = 'verifylogin';
/* End of file routes.php */
/* Location: ./application/config/routes.php */