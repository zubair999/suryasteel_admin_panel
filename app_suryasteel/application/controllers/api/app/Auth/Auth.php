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
                        $response = ['status' => 200, 'message' => 'error', 'description' => 'You are not allowed to login. Contact Administrator.'];
                    }
                    else{
                        $user = $this->auth_m->getUserByEmail($this->input->post('username'));
                        if(password_verify($this->input->post('password'), $user->password)){
                            $userPermission = $this->roles_m->getUserPermission($user->role_id);
                            $userPermission = unserialize($userPermission);
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
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();

        }
    }
	

	






	//CLASS ENDS
}
