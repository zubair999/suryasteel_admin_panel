<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Grinding extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function addGrindingHistory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->grinding_m->grindingHistoryRules);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $isAdded = $this->grinding_m->addGrindingHistory($this->input->post('completedBy'));
                if($isAdded['status'] == 'success'){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Grinding process history added for the current batch successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => $isAdded['message']];
                }
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function getGrindingBatch_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->grinding_m->get_grinding_batch();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Grinding Batch fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

	//CLASS ENDS
}
