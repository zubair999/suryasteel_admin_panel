<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function userLogin_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules('username', 'username', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'password', 'trim|required');
			if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
            }
            else{
                $userCount = $this->auth_m->userCountByEmail($this->input->post('username'));
                if($userCount == 0){
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'You are not registered.'];
                }
                else{
                    $activeUserCount = $this->auth_m->getActiveUserByEmail($this->input->post('username'));
                    if($activeUserCount == 0){
                        $response = ['status' => 200, 'message' => 'error', 'description' => 'You account is disabled. Contact Administrator.'];
                    }
                    else{
                        $user = $this->auth_m->getUserByEmail($this->input->post('username'));
                        $userPermission = $this->roles_m->getUserPermission($user->role_id);
                        $userPermission = unserialize($userPermission);

                        foreach($userPermission as $up){
                            if($up != 'addRoles'){
                                $response = ['status' => 200, 'message' => 'error', 'description' => 'Not allowed to login here. Contact Administrator.'];
                                $this->response($response, REST_Controller::HTTP_OK);
                                exit();
                            }
                            else{
                                if(password_verify($this->input->post('password'), $user->password)){
                                    foreach($userPermission as $key => $p){
                                        $userPermission[$p] = $p;
                                        unset($userPermission[$key]);
                                    };
                                    $userData = array(
                                        'uid' => $user->user_id,
                                        'role_id' => $user->role_id,
                                        'role_name' => $user->roles_name,
                                        'mobile_no' => $user->mobile_no,
                                        'firstname' => $user->firstname,
                                        'lastname' => $user->lastname,
                                        'username ' => $user->email,
                                        'is_logged_in' => true,
                                        'permission' => $userPermission
                                    );
                                    $response = ['status' => 200, 'message' => 'success', 'description' => 'You are successfully login.', 'data'=>$userData];
                                }
                                else{
                                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Incorrect password.'];
                                }
                            }
                        };
                    }
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }
	
    public function update_password_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules('newpassword', 'newpassword', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
            }
            else{
                $userData = array(
                    'password' => password_hash($this->input->post('newpassword'), PASSWORD_DEFAULT)
                );
                $this->db->where('user_id', $this->input->post('user_id'));
                $isUpdated = $this->db->update('users', $userData);
                if($isUpdated){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Password updated successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong. Try again.'];
                }
            }
        }
        $this->response($response, REST_Controller::HTTP_OK);
        exit();
    }

    public function change_status_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules('user_id', 'User', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
            }
            else{
                $is_user_active = $this->auth_m->is_user_active();
                if(!$is_user_active){
                    $userData = array(
                        'is_active' => 'active'
                    );
                    $description = 'Status is active now.';
                }
                else{
                    $userData = array(
                        'is_active' => 'inactive'
                    );
                    $description = 'Status is inactive now.';
                }
                $this->db->where('user_id', $this->input->post('user_id'));
                $isUpdated = $this->db->update('users', $userData);
                if($isUpdated){
                    $response = ['status' => 200, 'message' => 'success', 'description' => $description];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong. Try again.'];
                }
            }
        }
        $this->response($response, REST_Controller::HTTP_OK);
        exit();
    }
	






	//CLASS ENDS
}
