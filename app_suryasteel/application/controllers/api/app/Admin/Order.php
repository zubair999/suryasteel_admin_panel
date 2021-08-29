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
                    $response = ['status' => 200, 'message' =>'ok', 'description' =>"Product item added successfully."];
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
            $this->data['user'] = $this->auth_m->getUserById($this->input->post('user_id'));
            if($this->input->post('mobileno') != $this->data['user']->mobile_no) {
                $is_unique =  '|is_unique[users.mobile_no]';
            } else {
                $is_unique =  '';
            }
            $this->form_validation->set_rules('role', 'Role', 'trim|required');
            $this->form_validation->set_rules('mobileno', 'Mobile no', 'trim|required|exact_length[10]|is_natural'.$is_unique);
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' =>'ok', 'description' =>validation_errors()];
            }
            else{
                $userCount = $this->auth_m->userCountByEmail($this->input->post('username'));
				if($userCount > 0){
					$response = ['status' => 200, 'message' => 'error', 'description' => 'User already exits.'];
                }
                else{
                    $this->staff_m->editStaff($this->input->post('user_id'));
                    $this->staff_m->editLog($this->input->post('edited_by'));
                    $response = ['status' => 200, 'message' =>'ok', 'description' =>'Staff updated successfully.'];
                }   
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function getOrder_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
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
	






	//CLASS ENDS
}
