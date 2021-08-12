<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }


    public function edit(){
        if(!in_array('systemSetting', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        if($this->input->post()){
            $this->form_validation->set_rules('app_name', 'app name', 'trim|required');
            $this->form_validation->set_rules('app_description', 'app description', 'trim|required');
            $this->form_validation->set_rules('address', 'address', 'trim|required');
            $this->form_validation->set_rules('author_name', 'author', 'trim|required');
            $this->form_validation->set_rules('whatsappno', 'whatsapp no', 'trim|required|exact_length[10]');
			if($this->form_validation->run() == FALSE){
				$this->data['page_title'] = 'system settings';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/setting/edit', $this->data);
			}
			else{
				$this->setting_m->update_system_settings();
                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'System Settings',
                    'description' => 'System settings updated successfully.',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);
				$this->session->set_flashdata('success', "System settings updated successfully.");
				redirect('system-setting', 'refresh');
			}
		}
		else{
			$this->data['page_title'] = 'system settings';
			$this->admin_view('backend/setting/edit', $this->data);
		}
	}
    

    

// CLASS ENDS
}


 
