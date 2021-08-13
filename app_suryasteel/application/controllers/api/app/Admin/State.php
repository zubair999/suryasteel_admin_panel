<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class State extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getState_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('state_id,state_name');
            $this->db->from('state');
            $this->db->order_by('state_name','asc');
            $stateArr = $this->db->get()->result_array();
            foreach ($stateArr as $key => $value) {
                $stateArr[$key]['value'] = $value['state_id'];
                $stateArr[$key]['label'] = ucwords($value['state_name']);
                unset($stateArr[$key]['state_id']);
                unset($stateArr[$key]['state_name']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'State fetched successfully.', 'data'=>$stateArr];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
