<?php

class Customer extends MY_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function add(){
		$this->staff_api_m->add_staff($this->input->post());
	}

	public function getCustomer(){
		$this->db->select(
            'user_id,
             role_id,
             is_active,
             mobile_no,
             firstname,
             lastname,
             email
            '
        );

        $this->db->from('users');
        $this->db->where('role_id = ', null);

        $this->db->limit(25);
        $this->db->order_by('firstname ASC');

        $staff = $this->db->get()->result_array();

		$res = ['status'=> 200, 'message'=> 'success', 'description'=>'Customer fetched successfully.', 'data'=>$staff];

        echo json_encode($res);
        exit();
	}


	

	






	//CLASS ENDS
}
