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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* Main Routes */
$route['signup'] = 'main/signUp';
$route['logout'] = 'main/logout';

/* Admin Routes */
$route['admin/dashboard'] = 'admins';
$route['admin/signin-page'] = 'admins/signinPage';
$route['admin/signin'] = 'admins/signin';

/** Subscriber/Lessors */
$route['lessor/signin-page'] = 'lessors/signinPage';
$route['lessor/items/create'] = 'lessors/itemCreate';
$route['lessor/items'] = 'lessors/itemList';
$route['lessor/shops/create'] = 'lessors/shopCreate';
$route['lessor/shops/edit/(:num)'] = "lessors/shopEdit/$1";
$route['lessor/shops/list'] = 'lessors/shopList';
$route['lessor/shops/list/(:num)'] = 'lessors/shopList/$1';
$route['lessor'] = 'lessors';
$route['lessor/dashboard'] = 'lessors/dashboard';

/** Lessees */
$route['lessees/signin-page'] = 'lessees/signinPage';
$route['lessee/dashboard'] = 'lessees';
$route['lessee/profile'] = 'lessees/profilePage';
$route['lessee/myshops'] = 'lessees/myShopsPage';
$route['lessee/myinterests'] = 'lessees/myInterestsPage';
$route['lessee/inbox'] = 'lessees/inboxPage';
$route['lessee/shops'] = 'lessees/shopsPage';
$route['lessee/gowns'] = 'lessees/gownsPage';
$route['lessee/send'] = 'lessees/sendMessage';
$route['lessee/update-info'] = 'lessees/updateInfo';
$route['lessee/update-account'] = 'lessees/updateAccount';