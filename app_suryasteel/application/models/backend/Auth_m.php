<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Auth_m extends MY_Model {

	protected $tbl_name = 'users';
    protected $primary_col = 'user_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getUserById($id) {
        return $this->db->get_where('users', array('user_id'=> $id))->row();
    }

    public function getUserByEmail($email) {
        $this->db->select('*');
        $this->db->from('users as u');
        $this->db->join('roles as r', 'u.role_id = r.role_id');
        $this->db->where('email', $email);
        return $this->db->get()->row();
    }

    public function userCountByEmail($email) {
        return $this->db->get_where('users', array('email'=> $email))->num_rows();
    }

    public function getActiveUserByEmail($email) {
        return $this->db->get_where('users', array('email'=> $email,'is_active'=>'active'))->num_rows();
    }






//end class

}
