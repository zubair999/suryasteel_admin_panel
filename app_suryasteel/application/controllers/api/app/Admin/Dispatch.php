<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Dispatch extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function dispatchItemDelivery_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $response = $this->dispatch_m->updateDeliveryForDispatchItem();
            // $response = $this->order_m->dispatchOrderItem();
            // if($response){
            //     $isQtyUpdated = $this->order_m->addDispatchQtyInOrderItem();
            //     $this->order_m->checkIfOrderIsFullyDispatched();
            //     // $this->product_m->decreaseStock();
            //     if($isQtyUpdated){
            //         $order_item = $this->order_m->get_order_item_by_order_id($this->input->post('orderId'));
            //         $response = ['status' => 200, 'message' =>'success', 'description' =>'Item is dispatch successfully.', 'data'=>$order_item];
            //     }
            //     else{
            //         $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
            //     }
            // }
            // else{
            //     $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
            // }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }


	//CLASS ENDS
}
