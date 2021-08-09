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
            $this->response(['status' => 400, 'message' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
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
						$hashedPwd = password_hash($data['password'], PASSWORD_DEFAULT);
						$staffData = array(
							'role_id'=> $data['role'],
							'is_active' => 'active',
							'firstname'  => $data['firstname'],
							'lastname'  => $data['lastname'],
							'email'  => $data['username'],
							'password'  => $hashedPwd,
							'mobile_no' => $data['mobileno'],
							'created_on' => $this->today,
                            'created_by' => $data['uid']
						);
						$isAdded =  $this->db->insert('users', $staffData);
						if($isAdded){
                            $this->db->select(
                                'u.user_id,
                                u.role_id,
                                u.is_active,
                                u.mobile_no,
                                u.firstname,
                                u.lastname,
                                u.email,
                                r.roles_name
                                '
                            );
                
                            $this->db->from('users as u');
                            $this->db->join('roles as r', 'u.role_id=r.role_id');
                            $this->db->where('r.role_id != ', null);
                            $this->db->limit(25);
                            $this->db->order_by('u.firstname ASC');
                            $staff = $this->db->get()->result_array();


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

	public function getStaff_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'message' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $staffCount = $this->staff_api_m->staff_count();
            if($staffCount < 1){
                $res = ['status'=> 200, 'message'=> 'error', 'description'=>'No staff available.'];
                $this->response($res, REST_Controller::HTTP_OK);
                exit();
            }
            else{
                $this->db->select(
                    'u.user_id,
                    u.role_id,
                    u.is_active,
                    u.mobile_no,
                    u.firstname,
                    u.lastname,
                    u.email,
                    r.roles_name
                    '
                );
    
                $this->db->from('users as u');
                $this->db->join('roles as r', 'u.role_id=r.role_id');
                $this->db->where('r.role_id != ', null);
                $this->db->limit(25);
                $this->db->order_by('u.firstname ASC');
                $staff = $this->db->get()->result_array();
                $res = ['status'=> 200, 'message'=> 'success', 'description'=>'Staff fetched successfully.', 'data'=>$staff];
                $this->response($res, REST_Controller::HTTP_OK);
                exit();
            }
        }
	}


	

	






	//CLASS ENDS
}
