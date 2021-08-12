<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Staff extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function addStaff_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->staff_m->staffAddRulesApp);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$data = $this->input->post();
				$userCount = $this->auth_m->userCountByEmail($data['username']);
				if($userCount > 0){
					$response = ['status' => 200, 'message' => 'error', 'description' => 'User already exits.'];
                }
				else{
					$mobileNoCount = $this->auth_m->getUserCountByMobile($data['mobileno']);
					if($mobileNoCount > 0) {
						$response = ['status' => 200, 'message' => 'error', 'description' => 'Duplicate mobile no.'];
					}
					else{
                        $isAdded = $this->staff_m->addStaff($data['uid']);
                        $this->staff_m->addLog($data['uid']);
						if($isAdded){
                            $staff = $this->staff_m->get_staff_list();
							$response = ['status' => 200, 'message' => 'success', 'description' => 'New Staff added successfully.', 'data'=>$staff];
						}
						else{
							$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
						}
					}
				}   
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function editStaff_post(){
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

	public function getStaff_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $staffCount = $this->staff_m->staff_count();
            if($staffCount < 1){
                $res = ['status'=> 200, 'message'=> 'error', 'description'=>'No staff available.'];
                $this->response($res, REST_Controller::HTTP_OK);
                exit();
            }
            else{
                $staff = $this->staff_m->get_staff_list();
                $res = ['status'=> 200, 'message'=> 'success', 'description'=>'Staff fetched successfully.', 'data'=>$staff];
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
	

	






	//CLASS ENDS
}
