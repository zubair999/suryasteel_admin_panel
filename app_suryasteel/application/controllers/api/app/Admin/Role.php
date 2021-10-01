<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Role extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getRole_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('role_id,roles_name');
            $this->db->from('roles');
            $this->db->order_by('roles_name','asc');
            $roleArr = $this->db->get()->result_array();
            foreach ($roleArr as $key => $value) {
                $roleArr[$key]['value'] = $value['role_id'];
                $roleArr[$key]['label'] = ucwords($value['roles_name']);
                unset($roleArr[$key]['role_id']);
                unset($roleArr[$key]['roles_name']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Roles fetched successfully.', 'data'=>$roleArr];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


    public function getUserPermission_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->form_validation->set_rules('user_id','User Id', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $res = ['status'=>200,'message'=>'error','description'=>'Invalid details'];
            }
            else{
                $user = $this->auth_m->getUserById($this->input->post('user_id'));
                $userPermission = $this->roles_m->getUserPermission($user->role_id);
                $userPermission = unserialize($userPermission);
                foreach($userPermission as $key => $p){
                    $userPermission[$p] = $p;
                    unset($userPermission[$key]);
                };

                $res = ['status'=>200,'message'=>'success','description'=>'User permission fetched successfully.', 'data'=>$userPermission];
            }
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
