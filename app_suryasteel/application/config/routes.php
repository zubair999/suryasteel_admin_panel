<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// WEBSITE
$route['default_controller'] = 'backend/dashboard/';



/************************************************************/
//app api
$route['v1/getbrand'] = 'api/app/brand/getbrand';
$route['v1/getnewlylaunchedproduct'] = 'api/app/product/getnewlylaunchedproduct';
$route['v1/topproduct'] = 'api/app/product/topproduct';
$route['v1/topdeal'] = 'api/app/product/top_deals';
$route['v1/hotproduct'] = 'api/app/product/hotproduct';
$route['v1/getplayerendorsedproduct'] = 'api/app/product/getplayerendorsedproduct';
$route['v1/totalproductcount'] = 'api/app/product/productcount';
$route['v1/getbanner'] = 'api/app/banner/getbanner';
$route['v1/product/(:any)'] = 'api/app/product/productdetail/$1';
$route['v1/product/category/(:any)'] = 'api/app/product/productbycategory/$1';
$route['v1/product/brand/(:any)'] = 'api/app/product/productbybrand/$1';
$route['v1/product/search/(:any)'] = 'api/app/product/searchProduct/$1';
// $route['v1/getallproduct'] = 'api/app/product/getAllProducts';
$route['v1/getbrandfilter'] = 'api/app/filter/brandFilter';
$route['v1/getcategoryfilter'] = 'api/app/filter/categoryFilter';
$route['v1/getsubcategoryfilter/(:any)'] = 'api/app/filter/subCategoryFilter/$1';
$route['v1/getsubcategorytypefilter/(:any)'] = 'api/app/filter/subCategoryTypeFilter/$1';
$route['v1/gettypefilter'] = 'api/app/filter/typeFilter';
$route['v1/getsizefilter'] = 'api/app/filter/sizeFilter';
$route['v1/getqualityfilter'] = 'api/app/filter/qualityFilter';
$route['v1/getconfiguration'] = 'api/app/setting/getSetting';
$route['v1/createorder'] = 'api/app/order/create_razorpay_order';
$route['v1/authenticateorder'] = 'api/app/order/verify_razorpay_signature';
$route['v1/getmyorder'] = 'api/app/order/getMyOrder';
$route['v1/userlogin'] = 'api/app/auth/userLogin';
$route['v1/userregister'] = 'api/app/auth/userRegister';
$route['v1/updatepassword'] = 'api/app/auth/updateUserPassword';
$route['v1/adduseraddress'] = 'api/app/useraddress/add_user_address';
$route['v1/edituseraddress'] = 'api/app/useraddress/edit_user_address';
$route['v1/changedefaultaddress'] = 'api/app/useraddress/change_default_address';
$route['v1/edituserprofile'] = 'api/app/auth/update_user_profile';










/************************************************************/
//admin api
$route['v1/createbrand'] = 'api/backend/brand/createbrand';
$route['v1/updatebrand/(:any)'] = 'api/backend/brand/updatebrand/$1';
$route['v1/delete-image'] = 'backend/media/deleteimage';
$route['v1/addstaff'] = 'api/app/admin/staff/add';
$route['v1/getstaff'] = 'api/app/Admin/staff/getStaff';
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
$route['view-roles'] = 'backend/roles/index';
$route['get-roles'] = 'backend/roles/getRoles';



$route['add-customer'] = 'backend/customer/add';
$route['edit-customer-(:any)'] = 'backend/customer/edit/$1';
$route['view-customer'] = 'backend/customer/index';
$route['get-customer'] = 'backend/customer/getCustomer';



$route['login'] = 'backend/auth/index';











$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
