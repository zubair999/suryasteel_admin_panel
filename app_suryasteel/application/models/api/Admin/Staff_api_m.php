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
  
	public function staff_count(){
		$this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('role_id != ', null);
    	return $this->db->get()->num_rows();
	}

	public function getUserCount($username){
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('email', $username);
        return $this->db->get()->num_rows();
    }


//end class

}
