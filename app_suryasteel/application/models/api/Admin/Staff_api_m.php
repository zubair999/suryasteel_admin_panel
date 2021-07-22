<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Staff_api_m extends MY_Model {

	protected $tbl_name = 'users';
    protected $primary_col = 'user_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function add_staff(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('roleid', 'Role', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => 'Invalid details'];
			} else {
				$data = $this->input->post();
				$userCount = $this->auth_m->getUserCount($data['email']);
				if($userCount > 0){
					$response = ['status' => 200, 'message' => 'error', 'description' => 'User already exits.'];
				}
				else{
					$mobileNoCount = $this->auth_m->getUserCountByMobile($data['mobile_no']);
					if($mobileNoCount > 0) {
						$response = ['status' => 200, 'message' => 'error', 'description' => 'Duplicate mobile no.'];
					}
					else{
						$hashedPwd = password_hash($data['password'], PASSWORD_DEFAULT);
						$staffData = array(
							'role_id'=> $data['roleid'],
							'is_active' => 'active',
							'firstname'  => $data['first_name'],
							'lastname'  => $data['last_name'],
							'email'  => $data['email'],
							'password'  => $hashedPwd,
							'mobile_no' => $data['mobile_no'],
							'created_on' => $this->today
						);
						$isAdded =  $this->db->insert('users', $staffData);
						if($isAdded){
							$response = ['status' => 200, 'message' => 'success', 'description' => 'Staff added successfully.'];
						}
						else{
							$response = ['status' => 200, 'message' => 'success', 'description' => 'Something went wrong.'];
						}
					}
				}
			}
		} else {
			$response = ['status' => 200, 'message' => 'error', 'description' => 'Bad request'];
		}
		echo json_encode($response);
		exit();
	}
  


//end class

}
