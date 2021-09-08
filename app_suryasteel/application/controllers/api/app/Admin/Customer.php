<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Customer extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function addCustomer_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->customer_m->customerAddRulesApp);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$data = $this->input->post();
				$userCount = $this->auth_m->userCountByEmail($data['username']);
				if($userCount > 0){
					$response = ['status' => 200, 'message' => 'error', 'description' => 'User already exits.'];
                }
				else{
                    $userCompanyMailCount = $this->auth_m->userCountByCompanyEmail($data['companyMail']);
                    if($userCompanyMailCount > 0){
                        $response = ['status' => 200, 'message' => 'error', 'description' => 'Company email already exists.'];
                    }
                    else{
                        $mobileNoCount = $this->auth_m->getUserCountByMobile($data['mobileno']);
                        if($mobileNoCount > 0) {
                            $response = ['status' => 200, 'message' => 'error', 'description' => 'Duplicate mobile no.'];
                        }
                        else{
                            $isAdded = $this->customer_m->addCustomer($data['uid']);
                            $this->customer_m->addLog($data['uid']);
                            if($isAdded){
                                $customer = $this->customer_m->get_customer_list();
                                $response = ['status' => 200, 'message' => 'success', 'description' => 'New customer added successfully.', 'data'=>$customer];
                            }
                            else{
                                $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
                            }
                        }
                    }
				}   
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function editCustomer_post(){
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
            $this->form_validation->set_rules('mobileno', 'Mobile no', 'trim|required|exact_length[10]|is_natural'.$is_unique);
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' =>'error', 'description' =>validation_errors()];
            }
            else{
                $isUpdated = $this->customer_m->editCustomer($this->input->post('user_id'));
                $this->log_m->Log($this->input->post('user_id'),'Customer','A customer edited successfully.');
                if($isUpdated){
                    $response = ['status' => 200, 'message' =>'ok', 'description' =>'Staff updated successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong.'];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function getCustomer_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $customerCount = $this->customer_m->customer_count();
            if($customerCount < 1){
                $res = ['status'=> 200, 'message'=> 'error', 'description'=>'No customer available.'];
                $this->response($res, REST_Controller::HTTP_OK);
                exit();
            }
            else{
                $customer = $this->customer_m->get_customer_list();
                $res = ['status'=> 200, 'message'=> 'success', 'description'=>'Customer fetched successfully.', 'data'=>$customer];
                $this->response($res, REST_Controller::HTTP_OK);
                exit();
            }
        }
	}

    public function deleteStaff_post(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->auth_m->deleteUser($this->input->post('user_id'));
            $staff = $this->staff_m->get_staff_list();
            $res = ['status'=> 200, 'message'=> 'success', 'description'=>'User delete successfully.', 'data'=>$staff];
            $this->response($res, REST_Controller::HTTP_OK);
            exit();
        }
    }
	
    public function searchCustomer_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->customer_m->searchCustomer();
            $res = ['status'=> 200, 'message'=> 'success', 'description'=>'Customer fetched successfully.', 'data'=>$data];
            $this->response($res, REST_Controller::HTTP_OK);
            exit();
        }
    }
	






	//CLASS ENDS
}
