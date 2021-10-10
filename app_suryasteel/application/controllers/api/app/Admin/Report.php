<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Report extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function getWeldingBatch_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->welding_m->get_welding_batch();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Welding Batch fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

	//CLASS ENDS
}
