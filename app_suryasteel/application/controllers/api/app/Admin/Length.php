<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Length extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getLength_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('length_id,length_value');
            $this->db->from('length');
            $this->db->order_by('length_value','asc');
            $data = $this->db->get()->result_array();
            foreach ($data as $key => $value) {
                $data[$key]['value'] = $value['length_id'];
                $data[$key]['label'] = ucwords($value['length_value']);
                unset($data[$key]['length_id']);
                unset($data[$key]['length_value']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Length fetched successfully.', 'data'=>$data];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
