<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Cutting extends REST_Controller
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
                $isAdded = $this->cutting_m->addCuttingHistory($this->input->post('completedBy'));
                if($isAdded['status'] == 'success'){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Cutting process history added for the current batch successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => $isAdded['message']];
                }
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function getCuttingBatch_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->cutting_m->get_cutting_batch();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Cutting Batch fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

    public function updateCuttingTask_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->cutting_m->cuttingTaskRule);
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => 'Task cannot be empty.'];
            }
            else{
                $isTaskUpdated = $this->cutting_m->updateCuttingTask($this->input->post('taskAssignedBy'));
                if($isTaskUpdated){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Task assigned.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Something went wrong.'];
                }
            }

            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

	//CLASS ENDS
}
