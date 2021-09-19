<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Sink extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getSink_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('sink_id,sink_name');
            $this->db->from('sink');
            $this->db->order_by('sink_name','asc');
            $sink = $this->db->get()->result_array();
            foreach ($sink as $key => $value) {
                $sink[$key]['value'] = $value['sink_id'];
                $sink[$key]['label'] = ucwords($value['sink_name']);
                unset($sink[$key]['sink_id']);
                unset($sink[$key]['sink_name']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Sink fetched successfully.', 'data'=>$sink];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
