<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Galvanisation extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function addCuttingHistory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->cutting_m->cuttingHistoryRules);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $isAdded = $this->draw_m->addDrawHistory($this->input->post('completedBy'));
                if($isAdded['status'] == 'success'){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Draw process history added for the current batch successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => $isAdded['message']];
                }
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function getGalvanisationBatch_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->galvanisation_m->get_galvanisation_batch();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Galvanisation Batch fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

	//CLASS ENDS
}
