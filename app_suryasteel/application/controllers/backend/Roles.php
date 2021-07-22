<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
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

    



// CLASS ENDS
}


 
