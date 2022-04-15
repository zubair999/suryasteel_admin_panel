<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Stockmanufactured extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function addManufacturedStockToProductStockHistory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->stockmanufactured_m->productStockHistory);
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => 'Invalid data.'];
            }
            else{
                $data = $this->input->post();
                $isUpdated =  $this->stockmanufactured_m->updateStockAddedStatus();
                if($isUpdated){
                    $stock_manufactured = $this->stockmanufactured_m->getStockManufacturedByStockManufacturedId($data['stockManufacturedId']);
                    
                    $this->stockhistory_m->stock_manufactured_id = $data['stockManufacturedId'];
                    $this->stockhistory_m->product_id = $stock_manufactured->product_id;
                    $this->stockhistory_m->stock_type = 'Manufactured';
                    $this->stockhistory_m->stock_in_kg = $stock_manufactured->stock_in_kg;
                    $this->stockhistory_m->stock_in_pcs = $stock_manufactured->stock_in_pcs;
                    $this->stockhistory_m->added_by = $data['addedBy'];

                    $isProductHistoryAdded = $this->stockhistory_m->addProductStockHistory();
                    if($isProductHistoryAdded){
                        $this->product_m->product_id = $stock_manufactured->product_id;
                        $this->product_m->stock_manufactured = $stock_manufactured->stock_in_kg;

                        $isProductStockUpdated = $this->product_m->increase_stock();
                        if($isProductStockUpdated){
                            $response = ['status' => 200, 'message' => 'success', 'description' => 'Product Stock updated.'];
                        }
                        else{
                            $response = ['status' => 200, 'message' => 'error', 'description' => 'Stock Added to history.'];
                        }
                    }
                    else{
                        $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong'];
                    }
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function getUserPermission_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->form_validation->set_rules('user_id','User Id', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $res = ['status'=>200,'message'=>'error','description'=>'Invalid details'];
            }
            else{
                $user = $this->auth_m->getUserById($this->input->post('user_id'));
                $userPermission = $this->roles_m->getUserPermission($user->role_id);
                $userPermission = unserialize($userPermission);
                foreach($userPermission as $key => $p){
                    $userPermission[$p] = $p;
                    unset($userPermission[$key]);
                };

                $res = ['status'=>200,'message'=>'success','description'=>'User permission fetched successfully.', 'data'=>$userPermission];
            }
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }

    public function addStock_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->form_validation->set_rules($this->stockmanufactured_m->addStockRules);
            if ($this->form_validation->run() == FALSE) {
                $res = ['status'=>200, 'message'=>'error', 'description'=>'Invalid details'];
            }
            else{

                $product = $this->product_m->getProductByCategorySizelength($this->input->post('category'), $this->input->post('size'), $this->input->post('length'));
                $data = $this->input->post();

                $this->stockhistory_m->product_id = $product->product_id;
                $this->stockhistory_m->stock_type = 'Direct Entry';
                $this->stockhistory_m->stock_in_kg = $data['stock_in_kg'];
                $this->stockhistory_m->stock_in_pcs = $data['stock_in_pcs'];
                $this->stockhistory_m->added_by = $data['addedBy'];
                $isProductHistoryAdded = $this->stockhistory_m->addProductStockHistory();

                if($isProductHistoryAdded){
                    $this->product_m->product_id = $product->product_id;
                    $this->product_m->stock_manufactured = $data['stock_in_kg'];

                    $isProductStockUpdated = $this->product_m->increase_stock();
                        if($isProductStockUpdated){
                            $product_new_stock = $this->product_m->get_product_by_id($product->product_id)->stock;;
                            $stockIncreated = ['product_new_stock' => $product_new_stock];
                            $res = ['status' => 200, 'message' => 'success', 'description' => 'Product Stock updated.', 'data' => $stockIncreated];
                        }
                        else{
                            $res = ['status' => 200, 'message' => 'error', 'description' => 'Stock history added but product stock is not increased.'];
                        }
                }
                else{
                    $res = ['status'=>200,'message'=>'success','description'=>'Something went wrong.'];
                }
            }
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
