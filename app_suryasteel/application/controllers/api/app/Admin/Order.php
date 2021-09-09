<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Order extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function addOrder_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            // $this->form_validation->set_rules($this->staff_m->staffAddRulesApp);
            // if ($this->form_validation->run() == FALSE) {
			// 	$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			// } else {
				$data = $this->input->post();


                $isOrderAdded = $this->order_m->addOrder($this->input->post('createdBy'));
                if($isOrderAdded['response']){
                    $this->order_m->addOrderItem($isOrderAdded['last_order_id']);
                    $response = ['status' => 200, 'message' =>'success', 'description' =>"Order created successfully."];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>"Something went wrong."];
                }

			// }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function editOrder_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            // $this->form_validation->set_rules($this->staff_m->staffAddRulesApp);
            // if ($this->form_validation->run() == FALSE) {
			// 	$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			// } else {
				$data = $this->input->post();


                $isUpdatedAdded = $this->order_m->editOrder($this->input->post('orderId'));
                if($isUpdatedAdded){
                    $this->order_m->editOrderItem();
                    $response = ['status' => 200, 'message' =>'success', 'description' =>"Order updated successfully."];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>"Something went wrong."];
                }

			// }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function getOrder_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->order_m->get_order();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Order fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}
	
    public function getOrdersByProduct_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $response = ['status' => 200, 'message' =>'ok', 'description' =>'ok'];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }
	
    public function dispatchItem_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $response = $this->order_m->dispatchOrderItem();
            if($response){
                $isQtyUpdated = $this->order_m->addDispatchQtyInOrderItem();
                $this->order_m->checkIfOrderIsFullyDispatched();
                // $this->product_m->decreaseStock();
                if($isQtyUpdated){
                    $order_item = $this->order_m->get_order_item_by_order_id($this->input->post('orderId'));
                    $response = ['status' => 200, 'message' =>'success', 'description' =>'Item is dispatch successfully.', 'data'=>$order_item];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
                }
            }
            else{
                $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

    public function deleteDispatchItem_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $dispatchedItem = $this->order_m->getDispatchedItem($this->input->post('dispatchId'));
            $this->order_m->decreaseDispatchedQtyInOrderItem($dispatchedItem->order_item_id, $dispatchedItem->dispatch_quantity, $this->input->post('dispatchId'));
            // $this->order_m->deleteDispatchedItem($this->input->post('dispatchId'));
            $order_item = $this->order_m->get_order_item_by_order_id($this->input->post('orderId'));
            $response = ['status' => 200, 'message' => 'success', 'description' =>'ok', 'data'=>$order_item];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

    public function deleteOrderItem_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $isItemDispatched = $this->order_m->isOrderItemDispatched($this->input->post('order_item_id'));
            if($isItemDispatched){
                $response = ['status' => 200, 'message' =>'error', 'description' =>'Order item already dispatch, it cannot be deleted.'];
            }
            else{
                $isItemDeleted = $this->order_m->deleteOrderItem($this->input->post('order_item_id'));
                if($isItemDeleted){
                    $response = ['status' => 200, 'message' =>'success', 'description' =>'Order item deleted successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }



	//CLASS ENDS
}
