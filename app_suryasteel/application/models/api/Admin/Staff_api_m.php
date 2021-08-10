<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Staff_api_m extends MY_Model {

	protected $tbl_name = 'users';
    protected $primary_col = 'user_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}
  

	// public function staff_count(){
	// 	$this->db->select('user_id');
    //     $this->db->from('users');
    //     $this->db->where('role_id != ', null);
    // 	return $this->db->get()->num_rows();
	// }

	// public function getUserCount($username){
    //     $this->db->select('user_id');
    //     $this->db->from('users');
    //     $this->db->where('email', $username);
    //     return $this->db->get()->num_rows();
    // }

    // public function get_staff_list(){
    //     $this->db->select(
    //         'u.user_id,
    //         u.role_id,
    //         u.is_active,
    //         u.mobile_no,
    //         u.firstname,
    //         u.lastname,
    //         u.email,
    //         r.roles_name
    //         '
    //     );

    //     $this->db->from('users as u');
    //     $this->db->join('roles as r', 'u.role_id=r.role_id');
    //     $this->db->where('r.role_id != ', null);
    //     $this->db->limit(25);
    //     $this->db->order_by('u.firstname ASC');
    //     return $this->db->get()->result_array();
    // }


//end class

}
