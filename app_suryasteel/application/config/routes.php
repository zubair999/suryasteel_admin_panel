<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// WEBSITE
$route['default_controller'] = 'backend/Auth/login';


/************************************************************/

//admin api
$route['v1/createbrand'] = 'api/backend/brand/createbrand';
$route['v1/updatebrand/(:any)'] = 'api/backend/brand/updatebrand/$1';
$route['v1/delete-image'] = 'backend/media/deleteimage';

// LOGIN
$route['v1/userlogin'] = 'api/app/Auth/auth/userLogin';
$route['v1/stafflogin'] = 'api/app/Auth/auth/staffLogin';
$route['v1/customerlogin'] = 'api/app/Auth/auth/customerLogin';
$route['v1/customerregistration'] = 'api/app/Auth/auth/customerRegistration';

// ROLE & PERMISSION
$route['v1/getrole'] = 'api/app/Admin/role/getRole';
$route['v1/getuserpermission'] = 'api/app/Admin/role/getUserPermission';

// PAYMENT MODE
$route['v1/getpaymentmode'] = 'api/app/Admin/paymentmode/getPaymentMode';

// DELIVERY MODE
$route['v1/getdeliverymode'] = 'api/app/Admin/deliverymode/getDeliveryMode';

// MEDIA
$route['v1/getmedia'] = 'api/app/Admin/media/getAllImage';

// STATE
$route['v1/getstate'] = 'api/app/Admin/state/getState';

// GST REG. TYPE
$route['v1/getgstregtype'] = 'api/app/Admin/gstregtype/getGstRegType';

// UNIT
$route['v1/getunit'] = 'api/app/Admin/unit/getUnit';

// CATEGORY
$route['v1/addcategory'] = 'api/app/Admin/category/addCategory';
$route['v1/editcategory'] = 'api/app/Admin/category/editCategory';
$route['v1/getcategory'] = 'api/app/Admin/category/getcategory';
$route['v1/deletecategory'] = 'api/app/Admin/category/deleteCategory';

// PRODUCT
$route['v1/addproduct'] = 'api/app/Admin/product/addProduct';
$route['v1/editproduct'] = 'api/app/Admin/product/editProduct';
$route['v1/getproduct'] = 'api/app/Admin/product/getProduct';
$route['v1/deleteproduct'] = 'api/app/Admin/product/deleteProduct';

// ORDER
$route['v1/addorder'] = 'api/app/Admin/order/addOrder';
$route['v1/deleteorder'] = 'api/app/Admin/order/deleteOrder';
$route['v1/editorder'] = 'api/app/Admin/order/editOrder';
$route['v1/editsingleorderitem'] = 'api/app/Admin/order/editSingleOrderItem';
$route['v1/getorder'] = 'api/app/Admin/order/getOrder';
$route['v1/getorderbyproduct'] = 'api/app/Admin/order/getOrdersByProduct';
$route['v1/getorderstatuscataglog'] = 'api/app/Admin/order/getOrderStatusCatalog';

// ORDER ITEM
$route['v1/deleteorderitem'] = 'api/app/Admin/order/deleteOrderItem';

// DISPATCH
$route['v1/dispatchitem'] = 'api/app/Admin/order/dispatchItem';
$route['v1/deletedispatchitem'] = 'api/app/Admin/order/deleteDispatchItem';
$route['v1/dispatchitemdelivery'] = 'api/app/Admin/dispatch/dispatchItemDelivery';

// DISPATCH ITEM DELIVERY
$route['v1/adddeliveryfordispatchitem'] = 'api/app/Admin/order/addDeliveryForDispatchedItem';

// DECREAES STOCK
$route['v1/decreasestock'] = 'api/app/Admin/product/decreaseStock';


// STAFF
$route['v1/addstaff'] = 'api/app/Admin/staff/addStaff';
$route['v1/editstaff'] = 'api/app/Admin/staff/editStaff';
$route['v1/getstaff'] = 'api/app/Admin/staff/getStaff';
$route['v1/deleteuser'] = 'api/app/Auth/auth/deleteUser';
$route['v1/updatepassword'] = 'api/app/Auth/auth/update_password';
$route['v1/changestatus'] = 'api/app/Auth/auth/change_status';

// CUSTOMER
$route['v1/addcustomer'] = 'api/app/Admin/customer/addCustomer';
$route['v1/editcustomer'] = 'api/app/Admin/customer/editCustomer';
$route['v1/getcustomer'] = 'api/app/Admin/customer/getCustomer';

// PROFILE
$route['v1/updateprofile'] = 'api/app/Auth/auth/update_profile';

// APP CONFIGURATIONS
$route['v1/getconfiguration'] = 'api/app/Admin/setting/getSetting';

// CUSTOMER
// $route['v1/getcustomer'] = 'api/app/Admin/customer/getCustomer';
$route['v1/getallproduct'] = 'api/app/Admin/product/getAllProducts';

// PURCHASE
$route['v1/addpurchase'] = 'api/app/Admin/purchase/addPurchase';
$route['v1/editpurchase'] = 'api/app/Admin/purchase/editPurchase';
$route['v1/getpurchase'] = 'api/app/Admin/purchase/getPurchase';
$route['v1/deletepurchase'] = 'api/app/Admin/purchase/deletePurchase';
$route['v1/deletepurchaseitem'] = 'api/app/Admin/purchase/deletePurchaseItem';


// SINK
$route['v1/getsink'] = 'api/app/Admin/sink/getSink';

// SIZE
$route['v1/getsize'] = 'api/app/Admin/size/getSize';

// LENGTH
$route['v1/addlength'] = 'api/app/Admin/length/addLength';
$route['v1/editlength'] = 'api/app/Admin/length/editLength';
$route['v1/deletelength'] = 'api/app/Admin/length/deleteLength';
$route['v1/getlength'] = 'api/app/Admin/length/getLength';

// MACHINE
$route['v1/getmachine'] = 'api/app/Admin/machine/getMachine';

// RAW MATERIAL OR PURCHASE ITEM
$route['v1/getrawmaterial'] = 'api/app/Admin/purchase/getPurchaseItem';

// ACID TREATMENT ITEM
$route['v1/getacidtreatmentitem'] = 'api/app/Admin/acidtreatment/getAcidTreatmentItem';
$route['v1/addacidtreatment'] = 'api/app/Admin/acidtreatment/addAcidTreatment';
$route['v1/editacidtreatment'] = 'api/app/Admin/acidtreatment/editAcidTreatment';
$route['v1/addacidtreatmenthistory'] = 'api/app/Admin/acidtreatment/addAcidTreatmentHistory';

// DRAW PROCESS
$route['v1/adddrawhistory'] = 'api/app/Admin/draw/addDrawHistory';
$route['v1/getdrawbatch'] = 'api/app/Admin/draw/getDrawBatch';

// CUTTING PROCESS
$route['v1/getcuttingbatch'] = 'api/app/Admin/cutting/getCuttingBatch';
$route['v1/addcuttinghistory'] = 'api/app/Admin/cutting/addCuttingHistory';

// GRINDING PROCESS
$route['v1/getgrindingbatch'] = 'api/app/Admin/grinding/getGrindingBatch';
$route['v1/addgrindinghistory'] = 'api/app/Admin/grinding/addGrindingHistory';

// FORGING PROCESS
$route['v1/getforgingbatch'] = 'api/app/Admin/forging/getForgingBatch';
$route['v1/addforginghistory'] = 'api/app/Admin/forging/addForgingHistory';

// HEAD PROCESS
$route['v1/getheadbatch'] = 'api/app/Admin/head/getHeadBatch';
$route['v1/addheadhistory'] = 'api/app/Admin/head/addHeadHistory';

// DRILL PROCESS
$route['v1/getdrillbatch'] = 'api/app/Admin/drill/getDrillBatch';
$route['v1/adddrillhistory'] = 'api/app/Admin/drill/addDrillHistory';

// WELDING PROCESS
$route['v1/getweldingbatch'] = 'api/app/Admin/welding/getWeldingBatch';
$route['v1/addweldingistory'] = 'api/app/Admin/welding/addWeldingHistory';

// GALVANISATION PROCESS
$route['v1/getgalvanisationbatch'] = 'api/app/Admin/galvanisation/getGalvanisationBatch';
$route['v1/addgalvanisedhistory'] = 'api/app/Admin/galvanisation/addGalvanisationHistory';

// REPORT PROCESS
$route['v1/getreport'] = 'api/app/Admin/report/getReport';







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
$route['delete-role-(:any)'] = 'backend/roles/delete/$1';
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
