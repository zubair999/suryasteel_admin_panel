<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Log extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getLog_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $data = $this->log_m->getAllLog();
            $res = ['status'=>200,'message'=>'success','description'=>'Logs fetched successfully.', 'data'=>$data];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }

    


	

	




	//CLASS ENDS
}
