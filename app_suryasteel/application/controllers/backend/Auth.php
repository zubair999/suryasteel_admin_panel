<?php

class Auth extends Backend_Controller{

	public function __construct(){
		parent::__construct();
	}


	public function login(){
		echo "hi";
		die;
		
		// if($this->input->post()){
		// 	$this->form_validation->set_rules('brand_name', 'Brand', 'trim|required');
		// 	$this->form_validation->set_rules('status', 'Status', 'trim|required');
		// 	$this->form_validation->set_rules('thumbnail_id', 'Thumbnail', 'trim|required');
		// 	if($this->form_validation->run() == FALSE){
		// 		$this->data['page_title'] = 'add brand';
		// 		$this->admin_view('backend/auth/index', $this->data);
		// 	}
		// 	else{
		// 		$data = array(
		// 			'brand_name' => strtoupper($this->input->post('brand_name')),
		// 			'thumbnail' => $this->input->post('thumbnail_id'),
		// 			'status' => $this->input->post('status'),
		// 			'created_by' => $this->uid,
		// 			'created_on' => $this->current_time,
		// 		);			
		// 		$this->session->set_flashdata('notification', "You successfully read this important alert message.");
		// 		$isDataSave = $this->db->insert('brand', $data);
		// 		$this->data['page_title'] = 'add brand';
		// 		$this->admin_view('backend/auth/index', $this->data);
		// 	}
		// }
		// else{
		// 	$this->data['page_title'] = 'add brand';;
		// 	$this->admin_view('backend/auth/index', $this->data);
		// }
	}


	public function update_password($id){
		if($this->input->post()){
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[10]');
			if($this->form_validation->run() == FALSE){
                $this->data['user_id'] = $id;
				$this->data['page_title'] = 'update password';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/auth/change_password', $this->data);
			}
			else{
				$passwordData = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                );

                $this->db->where('user_id', $id);
                $this->db->update('users', $passwordData);

                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'Password',
                    'description' => 'Password updated succesfully',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);
				$this->session->set_flashdata('success', "Password updated successfully.");
				redirect('update-password-'.$id,'refresh');
			}
		}
		else{
            $this->data['user_id'] = $id;
			$this->data['page_title'] = 'update password';
			$this->admin_view('backend/auth/change_password', $this->data);
		}
	}
	



//CLASS ENDS
}