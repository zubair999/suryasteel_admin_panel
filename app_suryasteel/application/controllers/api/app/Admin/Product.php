<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function addProduct_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->product_m->productAddRulesApp);            
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $isAdded = $this->product_m->addProduct($this->input->post('createdBy'));
				$this->log_m->Log($this->input->post('createdBy'), 'Product','A product is added successfully.');
				if($isAdded){
                    $out_of_stock_threshold_limit = get_settings("out_of_stock_threshold_limit");
                    $short_stock_threshold_limit = get_settings("short_stock_threshold_limit");

                    $products = $this->product_m->productSectionList($out_of_stock_threshold_limit, $short_stock_threshold_limit);
					$response = ['status' => 200, 'message' => 'success', 'description' => 'New product added successfully.', 'data'=>$products];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function editProduct_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else {
            $this->form_validation->set_rules($this->product_m->productAddRulesApp);
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' =>'error', 'description' =>validation_errors()];
            }
            else{
                $isUpdated = $this->product_m->editProduct($this->input->post('productId'));
                $this->log_m->Log($this->input->post('updatedBy'), 'Product','A product is updated successfully.');
                if($isUpdated){
                    $response = ['status' => 200, 'message' =>'success', 'description' =>'Product updated successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function getProduct_post() {
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $out_of_stock_threshold_limit = get_settings("out_of_stock_threshold_limit");
            $short_stock_threshold_limit = get_settings("short_stock_threshold_limit");

            $products = $this->product_m->productSectionList($out_of_stock_threshold_limit, $short_stock_threshold_limit);
            $res = ['status'=>200,'message'=>'success','description'=>'Products fetched successfully.', 'data'=>$products];
            $this->response($res, REST_Controller::HTTP_OK);
            exit();
        }
    }

    

    public function deleteProduct_post(){
		$method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("productId", 'product ', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$this->db->where('product_id', $this->input->post('productId'));
				$isDeleted = $this->db->delete('products');
				$this->log_m->Log($this->input->post('deletedBy'), 'Product','A product is deleted successfully.');
				if($isDeleted){
					$out_of_stock_threshold_limit = get_settings("out_of_stock_threshold_limit");
                    $short_stock_threshold_limit = get_settings("short_stock_threshold_limit");

                    $products = $this->product_m->productSectionList($out_of_stock_threshold_limit, $short_stock_threshold_limit);
					$response = ['status' => 200, 'message' => 'success', 'description' => 'Product deleted successfully.', 'data'=>$products];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function decreaseStock_post(){
        $this->product_m->decreaseStock();
        // $response = ['status' => 200, 'message' =>'success', 'description' =>'Stock decrease successfully.'];
        // $this->response($response, REST_Controller::HTTP_OK);
        // exit();
    }




//CLASS ENDS
}