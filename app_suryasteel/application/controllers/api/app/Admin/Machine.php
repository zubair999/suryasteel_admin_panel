<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Machine extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getMachine_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('machine_id,machine_name');
            $this->db->from('machine');
            $this->db->order_by('machine_name','asc');
            $data = $this->db->get()->result_array();
            foreach ($data as $key => $value) {
                $data[$key]['value'] = $value['machine_id'];
                $data[$key]['label'] = ucwords($value['machine_name']);
                unset($data[$key]['machine_id']);
                unset($data[$key]['machine_name']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Machine fetched successfully.', 'data'=>$data];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
