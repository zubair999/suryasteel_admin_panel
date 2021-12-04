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
                            if($up == 'addRoles'){
                                if(password_verify($this->input->post('password'), $user->password)){
                                    foreach($userPermission as $key => $p){
                                        $userPermission[$p] = $p;
                                        unset($userPermission[$key]);
                                    };

                                    $user_image = $this->get_user_avatar($user->image_id);

                                    $userData = array(
                                        'uid' => $user->user_id,
                                        'role_id' => $user->role_id,
                                        'role_name' => $user->roles_name,
                                        'mobile_no' => $user->mobile_no,
                                        'firstname' => $user->firstname,
                                        'lastname' => $user->lastname,
                                        'username' => $user->email,
                                        'is_logged_in' => true,
                                        'user_avatar' => base_url('upload/'.$user_image->thumbnail),
                                        'permission' => $userPermission
                                    );
                                    $response = ['status' => 200, 'message' => 'success', 'description' => 'You are successfully login.', 'data'=>$userData];
                                }
                                else{
                                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Incorrect password.'];
                                }
                                $this->response($response, REST_Controller::HTTP_OK);
                                exit();
                            }
                            else{
                                $response = ['status' => 200, 'message' => 'error', 'description' => 'Not allowed to login here. Contact Administrator.'];
                                $this->response($response, REST_Controller::HTTP_OK);
                                exit();
                            }
                        };
                    }
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }


    public function staffLogin_post(){
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
                            if($up == 'addRoles'){
                                $response = ['status' => 200, 'message' => 'error', 'description' => 'Admin not allowed to login here. Contact Administrator.'];
                                $this->response($response, REST_Controller::HTTP_OK);
                                exit();
                            }
                            else{
                                if(password_verify($this->input->post('password'), $user->password)){
                                    foreach($userPermission as $key => $p){
                                        $userPermission[$p] = $p;
                                        unset($userPermission[$key]);
                                    };

                                    $user_image = $this->get_user_avatar($user->image_id);

                                    $userData = array(
                                        'uid' => $user->user_id,
                                        'role_id' => $user->role_id,
                                        'role_name' => $user->roles_name,
                                        'mobile_no' => $user->mobile_no,
                                        'firstname' => $user->firstname,
                                        'lastname' => $user->lastname,
                                        'username' => $user->email,
                                        'is_logged_in' => true,
                                        'user_avatar' => base_url('upload/'.$user_image->thumbnail),
                                        'permission' => $userPermission
                                    );
                                    $response = ['status' => 200, 'message' => 'success', 'description' => 'You are successfully login.', 'data'=>$userData];
                                
                                }
                                else{
                                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Incorrect password.'];
                                }
                                $this->response($response, REST_Controller::HTTP_OK);
                                exit();
                            }
                        };
                    }
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }


    public function customerLogin_post(){
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

                        if(in_array("isCustomer", $userPermission)){
                            if(password_verify($this->input->post('password'), $user->password)){
                                foreach($userPermission as $key => $p){
                                    $userPermission[$p] = $p;
                                    unset($userPermission[$key]);
                                };

                                $user_image = $this->get_user_avatar($user->image_id);

                                $userData = array(
                                    'uid' => $user->user_id,
                                    'role_id' => $user->role_id,
                                    'role_name' => $user->roles_name,
                                    'mobile_no' => $user->mobile_no,
                                    'firstname' => $user->firstname,
                                    'lastname' => $user->lastname,
                                    'username' => $user->email,
                                    'is_logged_in' => true,
                                    'is_allowed_to_view_product'=> $user->is_allowed_to_view_product,
                                    'user_avatar' => base_url('upload/'.$user_image->thumbnail),
                                    'permission' => $userPermission
                                );
                                $response = ['status' => 200, 'message' => 'success', 'description' => 'You are successfully login.', 'data'=>$userData];
                            
                            }
                            else{
                                $response = ['status' => 200, 'message' => 'error', 'description' => 'Incorrect password.'];
                            }
                            $this->response($response, REST_Controller::HTTP_OK);
                            exit();
                        }
                        else{
                            $response = ['status' => 200, 'message' => 'error', 'description' => 'Only customer can login here. Contact Administrator.'];
                                $this->response($response, REST_Controller::HTTP_OK);
                                exit();
                        }
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
	
    public function update_profile_post(){
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
            $this->form_validation->set_rules('firstname', 'firstname', 'trim|required');
            $this->form_validation->set_rules('lastname', 'lastname', 'trim|required');
            $this->form_validation->set_rules('mobileno', 'mobile', 'trim|required|exact_length[10]|is_natural'.$is_unique);
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
            }
            else{
                $isUpdated = $this->auth_m->update_profile($this->input->post('user_id'));
                if($isUpdated){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Profile update successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
                }
            }
        }
        $this->response($response, REST_Controller::HTTP_OK);
        exit();
    }

    public function deleteUser_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->auth_m->deleteUser($this->input->post('user_id'));
            $res = ['status'=> 200, 'message'=> 'success', 'description'=>'User delete successfully.'];
            $this->response($res, REST_Controller::HTTP_OK);
            exit();
        }
    }

    public function get_user_avatar($image_id){
        return $this->db->get_where('images', array('image_id'=> $image_id))->row();
    }

    // public function get_user_avatar($image_id){
    //     $this->db->get_where('images', array('image_id', $image_id))->row();
    // }

    // File upload
	public function fileUpload(){
		if(!empty($_FILES['file']['name'])){
			$renmae = md5(date('Y-m-d H:i:s:u')) . time();
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$_FILES['file']['name'] = md5(date('Y-m-d H:i:s:u')) . time() . '.' . $ext;

			// Set preference
			$config['upload_path'] = 'upload/';	
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']    = '10024'; // max_size in kb
			$config['file_name'] = $_FILES['file']['name'];
					
			//Load upload library
			$this->load->library('upload');			
			$this->upload->initialize($config);
			// File upload
			if($this->upload->do_upload('file')){
				// Get data about the file
				$uploadData = $this->upload->data();
				$config2['image_library']    = 'gd2'; 
				$config2['source_image']     = 'upload/' . $uploadData['file_name']; 
				$config2['new_image']         = 'upload/' . 'thumb_' . $uploadData['file_name']; 
				$config2['maintain_ratio']     = false; 
				$config2['width']            = 150; 
				$config2['height']           = 150;
				$this->image_lib->initialize($config2);
				$this->image_lib->resize();
				$arr_data = [];
				$arr_data['actual'] = $uploadData['file_name'];
				$arr_data['thumbnail'] = 'thumb_' . $uploadData['file_name'];
				$this->image_lib->clear();
                $this->db->insert('images', $arr_data);
                return $this->db->insert_id();
			}
		}
        else{
            return get_settings('default_user_image');
        }
	}

    public function customerRegistration_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->customer_m->customerRegistrationRulesApp);
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
                            $last_image_id = $this->fileUpload();
                            $isAdded = $this->customer_m->customerRegistration($data['uid'], $last_image_id);
                            $this->customer_m->addLog($data['uid']);
                            if($isAdded){
                                $customer = $this->customer_m->get_customer_list();
                                $response = ['status' => 200, 'message' => 'success', 'description' => 'Your registration is successfull. Your account will be active after approval.', 'data'=>$customer];
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


	//CLASS ENDS
}
