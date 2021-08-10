<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function updatePassword_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[10]');
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			}
            else{
                $passwordData = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                );
                $this->db->where('user_id', $this->input->post('user_id'));
                $this->db->update('users', $passwordData);
				$response = ['status' => 200, 'message' => 'success', 'description' => 'Password updated successfully.'];
                $this->response($response, REST_Controller::HTTP_OK);
                exit();
            }
        }
    }
	

	






	//CLASS ENDS
}
