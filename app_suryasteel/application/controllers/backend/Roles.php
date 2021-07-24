<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        if(!in_array('view-roles', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['drawTable'] 	= $this->rolesTableHead();
		$this->data['tableId']	    =	'roleslist';
		$this->data['pl']			=	'add-roles';
        $this->data['page_title'] = 'roles list';
        $this->admin_view('backend/roles/index', $this->data);
    }
    public function rolesTableHead(){
        $tableHead = array(
                  0 => 'sr. no.',
                  1 => 'role',
                  2 => 'action'
        );
        return $tableHead;
    }
    public function getRoles(){
        $data = $this->roles_m->getRoles();
        echo json_encode($data);
    }

    public function add(){
        if(!in_array('add-roles', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if($this->input->post()){

            $permission = serialize($this->input->post('permission'));
            $data = array(
        		'roles_name' => $this->input->post('role_name'),
        		'permission' => $permission
        	);
            $this->db->insert('roles', $data);
            $this->session->set_flashdata('notification', "Roles added successfully.");
            $this->data['page_title'] = 'add roles';
			$this->admin_view('backend/roles/add', $this->data);
		}
		else{
            $this->data['status'] = $this->status();
            $this->data['role'] = $this->roles_m->getAllRoles();
			$this->data['page_title'] = 'add roles';;
			$this->admin_view('backend/roles/add', $this->data);
		}
	}

    public function edit($id){
        if(!in_array('edit-roles', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['role'] = $this->roles_m->getRoleByRoleId($id);
        if($this->input->post()){
            $permission = serialize($this->input->post('permission'));
            $data = array(
        		'roles_name' => $this->input->post('role_name'),
        		'permission' => $permission
        	);

            $this->db->where('role_id',$id);
            $this->db->update('roles', $data);
            $this->session->set_flashdata('success', "Roles updated successfully.");
            redirect('edit-role-'.$id);
		}
		else{
            $this->data['user_permission'] = unserialize($this->data['role']->permission);
			$this->data['page_title'] = 'edit roles';
			$this->admin_view('backend/roles/edit', $this->data);
		}
	}

    



// CLASS ENDS
}


 
