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

    // public function getUserByEmailWithouRole($email) {
    //     $this->db->select('*');
    //     $this->db->from('users as u');
    //     $this->db->where('email', $email);
    //     return $this->db->get()->row();
    // }

    public function userCountByEmail($email) {
        return $this->db->get_where('users', array('email'=> $email))->num_rows();
    }

    public function userCountByCompanyEmail($email) {
        return $this->db->get_where('users', array('company_email'=> $email))->num_rows();
    }

    public function getUserCountByMobile($mobileno) {
        return $this->db->get_where('users', array('mobile_no'=> $mobileno))->num_rows();
    }

    public function getActiveUserByEmail($email) {
        return $this->db->get_where('users', array('email'=> $email,'is_active'=>'active'))->num_rows();
    }

    public function checkUserRole($email){
        return $this->db->get_where('users', array('email'=> $email,'is_active'=>'active'))->row()->role_id;
    }

    public function update_user_profile(){
        $data = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'mobile_no' => $this->input->post('mobileno')
        );
        $this->db->where('user_id', $this->uid);
        $this->db->update('users', $data);
    }

    public function update_user_password(){
        if(empty($this->input->post('newpassword'))){
            return;
        }
        else{
            $data = array(
                'password' => password_hash($this->input->post('newpassword'), PASSWORD_DEFAULT)
            );
            $this->db->where('user_id', $this->uid);
            $this->db->update('users', $data);
        }
    }

    public function deleteUser($id){
        $this->db->where('user_id', $id);
        $this->db->delete('users');
    }

    public function is_user_active(){
        $user = $this->getUserById($this->input->post('user_id'));
        if(!empty($user)){
            if($user->is_active == 'active'){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return "Either no user with this details or provided details is incorrect.";
        }
    }

    public function update_profile($id){
        if(empty($this->input->post('password'))){
            $userData = array(
                'mobile_no' => $this->input->post('mobileno'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname')
            );
        }
        else{
            $userData = array(
                'mobile_no' => $this->input->post('mobileno'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
            );
        }
        $this->db->where('user_id', $id);
        return $this->db->update('users', $userData);
    }

    public function updatePlayerId(){
        $updateData = array(
            'onesignal_player_id' => $this->input->post('onesignalPlayerId')
        );
        $this->db->where('email', $this->input->post('username'));
        $this->db->update('users', $updateData);
    }

//end class

}
