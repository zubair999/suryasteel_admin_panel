<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Customer extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

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
                            $last_image_id = $this->fileUpload();
                            $isAdded = $this->customer_m->addCustomer($data['uid'], $last_image_id);
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
                $last_image_id = $this->fileUpload();
                $isUpdated = $this->customer_m->editCustomer($this->input->post('user_id'), $last_image_id);
                $this->log_m->Log($this->input->post('user_id'),'Customer','A customer edited successfully.');
                if($isUpdated){
                    $customer = $this->customer_m->get_customer_list();
                    $response = ['status' => 200, 'message' =>'success', 'description' =>'Staff updated successfully.', 'data'=>$customer];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>'Something went wrong....'];
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
