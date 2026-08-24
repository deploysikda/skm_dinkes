<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'survey';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Halaman publik survei
$route['survei']              = 'survey/index';
$route['survei/kirim']        = 'survey/submit';
$route['survei/terima-kasih'] = 'survey/thanks';

// Area admin
$route['admin']                       = 'admin/dashboard';
$route['admin/login']                 = 'admin/auth/login';
$route['admin/logout']                = 'admin/auth/logout';
$route['admin/dashboard']             = 'admin/dashboard/index';
$route['admin/dashboard/(:num)/(:num)'] = 'admin/dashboard/index/$1/$2';
$route['admin/hasil']                 = 'admin/hasil/index';
$route['admin/hasil/(:num)/(:num)']   = 'admin/hasil/index/$1/$2';
$route['admin/responden']             = 'admin/responden/index';
$route['admin/responden/detail/(:num)'] = 'admin/responden/detail/$1';
$route['admin/responden/hapus/(:num)']  = 'admin/responden/hapus/$1';
$route['admin/saran']                 = 'admin/saran/index';
$route['admin/export/hasil']          = 'admin/export/hasil';
$route['admin/export/pdf']            = 'admin/export/pdf';
