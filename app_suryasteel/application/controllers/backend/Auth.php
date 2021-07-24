<?php

class Auth extends Backend_Controller{

	public function __construct(){
		parent::__construct();
	}


	public function login(){		
		if($this->input->post()){	
			$this->form_validation->set_rules('user_name', 'username', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'password', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->data['page_title'] = 'login';
				$this->load->view('backend/auth/login', $this->data);
			}
			else{
				$userCount = $this->auth_m->userCountByEmail($this->input->post('user_name'));
				if($userCount > 0){
					$isActive = $this->auth_m->getActiveUserByEmail($this->input->post('user_name'));
					if($isActive > 0){
						$user = $this->auth_m->getUserByEmail($this->input->post('user_name'));						
						if(password_verify($this->input->post('password'), $user->password)){
							$userData = array(
								'uid' => $user->user_id,
								'role_id' => $user->role_id,
								'role_name' => $user->roles_name,
								'mobile_no' => $user->mobile_no,
								'firstname' => $user->firstname,
								'lastname' => $user->lastname,
								'username ' => $user->email,
								'is_logged_in' => true,
							);
							$this->session->set_userdata($userData);
							$this->session->set_flashdata('success', "You are successfully logged in.");
							redirect('dashboard');
						}
						else{
							$this->session->set_flashdata('error', "Wrong password.");
							redirect('login');
						}
					}
					else{
						$this->session->set_flashdata('error', "Your account is disabled.");
						redirect('login');
					}
				}
				else{
					$this->session->set_flashdata('error', "You are not registered.");
					redirect('login');
				}
			}
		}
		else{
			$this->data['page_title'] = 'login';;
			$this->load->view('backend/auth/login', $this->data);
		}
	}

	public function userLogout(){
		$this->session->sess_destroy();
		redirect('login');
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
	

	public function update_profile(){
        if(!in_array('manage-profile', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        if($this->input->post()){
            $this->form_validation->set_rules('app_name', 'app name', 'trim|required');
            $this->form_validation->set_rules('app_description', 'app description', 'trim|required');
            $this->form_validation->set_rules('address', 'address', 'trim|required');
            $this->form_validation->set_rules('author_name', 'author', 'trim|required');
			if($this->form_validation->run() == FALSE){
				$this->data['page_title'] = 'update profile';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/setting/edit', $this->data);
			}
			else{
				$this->setting_m->update_system_settings();
                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'Update Profile',
                    'description' => 'User updated profile successfully.',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);
				$this->session->set_flashdata('success', "Your profile updated successfully.");
				redirect('system-setting', 'refresh');
			}
		}
		else{
			$this->data['page_title'] = 'update profile';
			$this->admin_view('backend/setting/edit', $this->data);
		}
	}
	


//CLASS ENDS
}