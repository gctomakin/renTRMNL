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
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
*/
$route['default_controller']   = 'main';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

/* Main Routes */
$route['signup'] = 'main/signUp';
$route['logout'] = 'main/logout';

/* Admin Pages */
$route['admin/dashboard']         = 'admins';
$route['admin/signin-page']       = 'admins/signinPage';
$route['admin/signin']            = 'admins/signin';
$route['admin/accounts/add']      = 'admins/accountsAddPage';
$route['admin/accounts']          = 'admins/accountsViewPage';
$route['admin/subscriptions/add'] = 'admins/subscription_plansAddPage';
$route['admin/subscriptions/pending'] = 'admins/subscription_pendingsPage';
$route['admin/subscriptions']     = 'admins/subscription_plansViewPage';
$route['admin/rentalshops/pending']   = 'admins/rental_shopsPendingPage';
$route['admin/rentalshops']       = 'admins/rental_shopsViewPage';
$route['admin/categories/add']    = 'admins/categoriesAddPage';
$route['admin/categories/edit/(:num)']    = 'admins/categoriesEditPage/$1';
$route['admin/categories']        = 'admins/categoriesViewPage';
$route['admin/reports/subscriptions'] = 'admins/reportsSubscriptions';
$route['admin/reports/rentals'] = 'admins/reportsRentals';
$route['admin/reports/users'] = 'admins/reportsUsers';


/** Admin Actions*/
$route['admin/account/add']       = 'admins/addAccount';
$route['admin/subscription/add']  = 'admins/addSubscriptionPlan';
$route['admin/rentalshop/add']    = 'admins/addRentalShop';
$route['admin/category/add']      = 'admins/addCategory';
$route['admin/category/edit']      = 'admins/editCategory';

/** Subscriber/Lessors */
$route['lessor/signin-page']       = 'lessors/signinPage';
$route['lessor/items/create']      = 'lessors/itemCreate';
$route['lessor/items/edit/(:num)'] = 'lessors/itemEdit/$1';
$route['lessor/items/list']        = 'lessors/itemList';
$route['lessor/items/list/(:num)'] = 'lessors/itemList/$1';
$route['lessor/shops/create']      = 'lessors/shopCreate';
$route['lessor/shops/edit/(:num)'] = "lessors/shopEdit/$1";
$route['lessor/shops/list']        = 'lessors/shopList';
$route['lessor/subscriptions']     = 'lessors/subscriptions';
$route['lessor']                   = 'lessors';
$route['lessor/dashboard']         = 'lessors/dashboard';
$route['lessor/reservations/pending'] = 'lessors/pendingReserves';
$route['lessor/reservations/pending/payments'] = 'lessors/pendingPayments';
$route['lessor/reservations/approve'] = 'lessors/approveReserves';
$route['lessor/account'] 						= 'lessors/account';

/** Lessees Pages*/
$route['lessee/dashboard']             = 'lessees';
$route['lessees/signin-page']          = 'lessees/signinPage';
$route['lessee/profile']               = 'lessees/profilePage';
$route['lessee/myshops']               = 'lessees/myShopsPage';
$route['lessee/myshops/(:num)']        = 'lessees/myShopsPage/$1';
$route['lessee/myinterests']           = 'lessees/myInterestsPage';
$route['lessee/myinterests/(:num)']    = 'lessees/myInterestsPage/$1';
$route['lessee/items']                 = 'lessees/itemsPage';
$route['lessee/items/(:num)']          = 'lessees/itemsPage/$1';
$route['lessee/items/category']        = 'lessees/itemsCategoryPage';
$route['lessee/items/category/(:num)'] = 'lessees/itemsCategoryPage/$1';
$route['lessee/items/category/(:num)/(:num)'] = 'lessees/itemsCategoryPage/$1/$2';
$route['lessee/reserved']        			 = 'lessees/reservedPage';
/** Lessees Actions*/
$route['lessee/inbox']                 = 'lessees/inboxPage';
$route['lessee/shops']                 = 'lessees/shopsPage';
$route['lessee/shops/(:num)']          = 'lessees/shopsPage/$1';
$route['lessee/send']                  = 'lessees/sendMessage';
$route['lessee/update-info']           = 'lessees/updateInfo';
$route['lessee/update-account']        = 'lessees/updateAccount';
$route['lessee/add-myshop']            = 'lessees/addMyShop';
$route['lessee/myshops/delete/(:num)'] = 'lessees/removeMyShop/$1';
$route['lessee/add-myinterest']        = 'lessees/addMyInterest';
$route['lessee/myinterests/delete/(:num)'] = 'lessees/removeMyInterest/$1';
$route['lessee/getshops']              = 'lessees/shopsJson';