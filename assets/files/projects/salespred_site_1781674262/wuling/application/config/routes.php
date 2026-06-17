<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a URL
| normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
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

// Default route - redirect to login
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Authentication routes
$route['login'] = 'auth/index';
$route['logout'] = 'auth/logout';
$route['auth/login'] = 'auth/login_process';

// Dashboard routes
$route['dashboard'] = 'dashboard/index';
$route['dashboard/admin'] = 'dashboard/admin';
$route['dashboard/bpkb'] = 'dashboard/bpkb';
$route['dashboard/sales'] = 'dashboard/sales';
$route['dashboard/operations'] = 'dashboard/operations';
$route['dashboard/executive'] = 'dashboard/executive';
$route['profile'] = 'dashboard/profile';

$route['transaction/edit/(:num)'] = 'transaction/edit/$1';

// $route['kendaraan'] = 'kendaraan/kendaraan_list';                    // Daftar semua kendaraan
// $route['kendaraan/kendaraan'] = 'kendaraan/kendaraan_list';              // Sama dengan di atas
// $route['kendaraan/add'] = 'kendaraan/add';                  // Form tambah kendaraan
// $route['kendaraan/save'] = 'kendaraan/save';                // Proses simpan data baru
// $route['kendaraan/edit/(:num)'] = 'kendaraan/edit/$1';      // Form edit kendaraan berdasarkan ID
// $route['kendaraan/update/(:num)'] = 'kendaraan/update/$1';  // Proses update data
// $route['kendaraan/delete/(:num)'] = 'kendaraan/delete/$1';  // Hapus data berdasarkan ID

// Additional dashboard views for different roles
// These can be customized based on your needs
$route['dashboard/(:any)'] = 'dashboard/$1';

// Transaction Import Routes
$route['import'] = 'import';
$route['import/upload_excel'] = 'import/upload_excel';

// Forecasting / SMA Routes
$route['forecasting']         = 'forecasting/index';
$route['forecasting/refresh'] = 'forecasting/refresh';
$route['forecasting/export']  = 'forecasting/export';

// BPKB Routes
$route['bpkb/search']           = 'bpkb/search';
$route['bpkb/create']           = 'bpkb/create';
$route['bpkb/store']            = 'bpkb/store';
$route['bpkb/edit/(:num)']      = 'bpkb/edit/$1';
$route['bpkb/update/(:num)']    = 'bpkb/update/$1';
$route['bpkb/delete/(:num)']    = 'bpkb/delete/$1';
$route['bpkb/export']           = 'bpkb/export';
$route['bpkb/import']           = 'bpkb/import';