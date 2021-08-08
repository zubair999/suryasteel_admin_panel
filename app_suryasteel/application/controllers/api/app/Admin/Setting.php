<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Setting extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function getSetting_get(){
		$method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'message' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
		else{
			$this->db->select('name, value');
			$this->db->from('settings');
			$response = $this->db->get()->result_array();
			$this->response($response, REST_Controller::HTTP_OK);
			exit();
		}
	}


	



//CLASS ENDS
}