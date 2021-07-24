<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        if(!in_array('view-staff', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['drawTable'] 	= $this->staffTableHead();
		$this->data['tableId']	    =	'stafflist';
		$this->data['pl']			=	'add-staff';
        $this->data['page_title'] = 'staff list';
        $this->admin_view('backend/staff/index', $this->data);
    }
    public function staffTableHead(){
        $tableHead = array(
                  0 => 'sr. no.',
                  1 => 'name',
                  2 => 'username',
                  3 => 'contact no',
                  4 => 'roles',
                  5 => 'status',
                  6 => 'action'
        );
        return $tableHead;
    }
    public function getStaff(){
        $data = $this->staff_m->getStaff();
        echo json_encode($data);
    }

    public function add(){
        if(!in_array('add-staff', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if($this->input->post()){
			$this->form_validation->set_rules($this->staff_m->staffAddRules);
			if($this->form_validation->run() == FALSE){
                $this->data['status'] = $this->status();
                $this->data['role'] = $this->roles_m->getAllRoles();
				$this->data['page_title'] = 'add staff';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/staff/add', $this->data);
			}
			else{
				$staffData = array(
                    'role_id' => $this->input->post('role'),
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'email' => $this->input->post('username'),
                    'password' => $this->input->post('password'),
                    'mobile_no' => $this->input->post('mobileno'),
                    'is_active' => 'active',
                    'created_by' => $this->uid
                );
                $this->db->insert('users', $staffData);

                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'Staff',
                    'description' => 'A staff is added succesfully',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);
                $this->data['status'] = $this->status();
                $this->data['role'] = $this->roles_m->getAllRoles();
				$this->session->set_flashdata('success', "Staff added successfully.");
				redirect('add-staff','refresh');
			}
		}
		else{
            $this->data['status'] = $this->status();
            $this->data['role'] = $this->roles_m->getAllRoles();
			$this->data['page_title'] = 'add staff';;
			$this->admin_view('backend/staff/add', $this->data);
		}
	}

    public function edit($id){
        if(!in_array('edit-staff', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['user'] = $this->auth_m->getUserById($id);
        if($this->input->post()){
            if($this->input->post('mobileno') != $this->data['user']->mobile_no) {
                $is_unique =  '|is_unique[users.mobile_no]';
            } else {
                $is_unique =  '';
            }
            $this->form_validation->set_rules('role', 'Role', 'trim|required');
            $this->form_validation->set_rules('mobileno', 'Mobile no', 'trim|required|exact_length[10]|is_natural'.$is_unique);
			if($this->form_validation->run() == FALSE){
                $this->data['status'] = $this->status();
                $this->data['role'] = $this->roles_m->getAllRoles();
                $this->data['user'] = $this->auth_m->getUserById($id);
				$this->data['page_title'] = 'Edit staff';
                $this->session->set_flashdata('error', "Please fill the form carefully!");
				$this->admin_view('backend/staff/edit', $this->data);
			}
			else{
				$staffData = array(
                    'role_id' => $this->input->post('role'),
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'mobile_no' => $this->input->post('mobileno'),
                    'is_active' => $this->input->post('status')
                );

                $this->db->where('user_id', $id);
                $this->db->update('users', $staffData);

                $logData = array(
                    'user_id' => $this->uid,
                    'title' => 'Staff',
                    'description' => 'A staff is updated succesfully',
                    'created_on' => $this->today
                );
                $this->db->insert('logs', $logData);
                $this->data['status'] = $this->status();
                $this->data['role'] = $this->roles_m->getAllRoles();
				$this->session->set_flashdata('success', "Updated successfully.");
				redirect('edit-staff-'.$id,'refresh');
			}
		}
		else{
            $this->data['status'] = $this->status();
            $this->data['role'] = $this->roles_m->getAllRoles();
            $this->data['user'] = $this->auth_m->getUserById($id);
			$this->data['page_title'] = 'edit staff';;
			$this->admin_view('backend/staff/edit', $this->data);
		}
	}

    public function delete($id) {
        $this->db->where('user_id', $id);
        $this->db->delete('users');
        $this->session->set_flashdata('success', "Delete successfully.");
		redirect('view-staff','refresh');
    }
    

    

// CLASS ENDS
}


 
