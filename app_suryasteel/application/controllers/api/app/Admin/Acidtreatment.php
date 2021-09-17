<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Acidtreatment extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getAcidTreatmentItem_get(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->acidtreatment_m->get_acid_treatment();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Acid treatment item fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

	//CLASS ENDS
}
