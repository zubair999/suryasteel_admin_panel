<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// WEBSITE
$route['default_controller'] = 'backend/Auth/login';


/************************************************************/
//admin api
$route['v1/createbrand'] = 'api/backend/brand/createbrand';
$route['v1/updatebrand/(:any)'] = 'api/backend/brand/updatebrand/$1';
$route['v1/delete-image'] = 'backend/media/deleteimage';

// ROLE
$route['v1/getrole'] = 'api/app/Admin/role/getRole';

// STAFF
$route['v1/addstaff'] = 'api/app/Admin/staff/addStaff';
$route['v1/getstaff'] = 'api/app/Admin/staff/getStaff';


// APP CONFIGURATIONS
$route['v1/getconfiguration'] = 'api/app/Admin/setting/getSetting';



// CUSTOMER
$route['v1/getcustomer'] = 'api/app/Admin/customer/getCustomer';
$route['v1/getallproduct'] = 'api/app/Admin/product/getAllProducts';
$route['v1/getcategory'] = 'api/app/Admin/category/getcategory';













/************************************************************/
//admin panel
$route['dashboard'] = 'backend/dashboard/index';

$route['media'] = 'backend/media/index';
$route['get-media'] = 'backend/media/getMedia';
$route['media-upload'] = 'backend/media/fileUpload';

$route['category'] = 'backend/category/index';
$route['add-category'] = 'backend/category/add';
$route['edit-category-(:any)'] = 'backend/category/edit/$1';
$route['delete-category-(:any)'] = 'backend/category/delete/$1';


$route['orders'] = 'backend/order/index';
$route['get-order'] = 'backend/order/getOrder';

$route['type'] = 'backend/type/index';
$route['gst'] = 'backend/gst/index';

$route['v1/getallimages'] = 'backend/media/getAllImage';

$route['products'] = 'backend/product/index';
$route['get-product'] = 'backend/product/getProduct';
$route['add-product'] = 'backend/product/add';
$route['edit-product-(:any)'] = 'backend/product/edit/$1';

$route['add-staff'] = 'backend/staff/add';
$route['edit-staff-(:any)'] = 'backend/staff/edit/$1';
$route['delete-staff-(:any)'] = 'backend/staff/delete/$1';
$route['update-password-(:any)'] = 'backend/auth/update_password/$1';
$route['view-staff'] = 'backend/staff/index';
$route['get-staff'] = 'backend/staff/getStaff';

$route['add-roles'] = 'backend/roles/add';
$route['edit-role-(:any)'] = 'backend/roles/edit/$1';
$route['view-roles'] = 'backend/roles/index';
$route['get-roles'] = 'backend/roles/getRoles';

$route['add-customer'] = 'backend/customer/add';
$route['edit-customer-(:any)'] = 'backend/customer/edit/$1';
$route['view-customer'] = 'backend/customer/index';
$route['get-customer'] = 'backend/customer/getCustomer';

$route['login'] = 'backend/auth/login';
$route['logout'] = 'backend/auth/userLogout';

// SETTINGS
$route['system-setting'] = 'backend/setting/edit';
$route['manage-profile'] = 'backend/auth/update_profile';

// PURCHASE
$route['add-purchase'] = 'backend/purchase/add';
$route['edit-purchase-(:any)'] = 'backend/purchase/edit/$1';
$route['delete-purchase-(:any)'] = 'backend/purchase/delete/$1';
$route['view-purchase'] = 'backend/purchase/index';
$route['get-purchase'] = 'backend/purchase/getPurchase';

// RAW MATERIAL
$route['view-raw-material'] = 'backend/purchase/index';
$route['get-raw-material'] = 'backend/purchase/getPurchase';


// LOGS
$route['view-logs'] = 'backend/log/index';
$route['get-logs'] = 'backend/log/getLog';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
