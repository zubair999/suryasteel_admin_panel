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
            $data = $this->input->post();
            $isOrderAdded = $this->order_m->addOrder($this->input->post('createdBy'));
            if($isOrderAdded['response']){
                $this->order_m->addOrderItem($isOrderAdded['last_order_id']);
                $user = $this->auth_m->getUserById($data['userId']);
                $this->onesignal_m->send_push_notification($user->customer_company);
                $response = ['status' => 200, 'message' =>'success', 'description' =>"Order created successfully."];
            }
            else{
                $response = ['status' => 200, 'message' =>'error', 'description' =>"Something went wrong."];
            }
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
            $isOrderItemDispatched = $this->order_m->getDispatchedItemByOrderId($this->input->post('orderId'));
            if($isOrderItemDispatched > 0){
                $response = ['status' => 200, 'message' =>'success', 'description' =>"Order item dispatch. You can not edit this order."];
            }
            else{
                $data = $this->input->post();


                $isUpdatedAdded = $this->order_m->editOrder($this->input->post('orderId'));
                if($isUpdatedAdded){
                    // $this->order_m->editOrderItem();
                    $response = ['status' => 200, 'message' =>'success', 'description' =>"Order detail updated successfully."];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>"Something went wrong."];
                }
            }
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
            $checkStock = $this->product_m->checkStock();
            if($checkStock){
                $response = $this->order_m->dispatchOrderItem();
                if($response){
                    $isQtyUpdated = $this->order_m->addDispatchQtyInOrderItem();
                    $this->order_m->checkIfOrderIsFullyDispatched();
                    if($isQtyUpdated){
                        $this->product_m->decreaseStock();
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
            }
            else{
                $orderItem = $this->order_m->getOrderItemByOrderItemId($this->input->post('orderItemId'));
                $productId = $orderItem->product_id;
                $product = $this->product_m->get_product($productId);
                $response = ['status' => 200, 'message' =>'error', 'description' =>'Product stock is '.$product->stock.' Kg. You can not dispatched more than that.'];
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
            $dispatchedItem = $this->order_m->getDispatchedItemByDispatchedId($this->input->post('dispatchId'));
            if($dispatchedItem->delivery_status == 'Delivered'){
                $response = ['status' => 200, 'message' => 'error', 'description' =>'Dispatched item is already delivered. You can not delete this item.'];
            }
            else{
                $dispatchedItem = $this->order_m->getDispatchedItem($this->input->post('dispatchId'));
                $this->order_m->decreaseDispatchedQtyInOrderItem($dispatchedItem->order_item_id, $dispatchedItem->dispatch_quantity, $this->input->post('dispatchId'));
                // $this->order_m->deleteDispatchedItem($this->input->post('dispatchId'));
                $this->order_m->checkIfOrderIsFullyDispatched($this->input->post('orderId'));
                $order_item = $this->order_m->get_order_item_by_order_id($this->input->post('orderId'));
                $response = ['status' => 200, 'message' => 'success', 'description' =>'Dispatch item deleted successfully.', 'data'=>$order_item];
            }
        }
        $this->response($response, REST_Controller::HTTP_OK);
        exit();
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
                $response = ['status' => 200, 'message' =>'error', 'description' =>'Order item already dispatch, it cannot be deleted. First delete dispatch item.'];
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

    public function getOrderStatusCatalog_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $orderStatusCatalog = $this->order_m->getOrderStatusCatalog();
		    $response = ['status'=>200,'message'=>'success','description'=>'Order Status catalog successfully.', 'data'=>$orderStatusCatalog];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function addDeliveryForDispatchedItem_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->order_m->addDelivery();
            $order_item = $this->order_m->get_order_item_by_order_id($this->input->post('orderId'));
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Delivery added for the dispatched item.', 'data'=>$order_item];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

    public function deleteOrder_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $dispatchedRowCount = $this->order_m->getDispatchedItemByOrderId($this->input->post('orderId'));
            if($dispatchedRowCount){
                $response = ['status' => 200, 'message' =>'error', 'description' =>'Some item of this order is already dispatched, it cannot be deleted. First delete dispatched item.'];
            }
            else{
                $isItemDeleted = $this->order_m->deleteOrderItemByOrderId($this->input->post('orderId'));
                if($isItemDeleted){
                    $isOrderDeleted = $this->order_m->deleteOrderByOrderId($this->input->post('orderId'));
                    if($isOrderDeleted){
                        $response = ['status' => 200, 'message' =>'success', 'description' =>'Order is deleted successfully.'];
                    }
                    else{
                        $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong..'];
                    }
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

    public function editSingleOrderItem_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules('orderItemId', 'Order item id', 'trim|required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');
            $this->form_validation->set_rules('rate', 'Rate', 'trim|required');
            $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' =>'error', 'description' =>'Invalid details.'];
            }
            else{
                $this->order_m->updateOrderItem();
                $response = ['status' => 200, 'message' =>'success', 'description' =>'Order item updated successfully.'];
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

    public function getOrderCountByOrderStatus_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->order_m->getOrderCountByOrderStatus();
            $response = ['status' => 200, 'message' =>'success', 'description' =>'Order count fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }


    

	//CLASS ENDS
}
