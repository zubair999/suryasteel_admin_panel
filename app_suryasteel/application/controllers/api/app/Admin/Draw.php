<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Draw extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function addDrawHistory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->draw_m->drawHistoryRules);
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

    public function getDrawBatch_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->draw_m->get_draw_batch();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Draw Batch fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

    public function deleteDrawBatch_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $drawBatch = $this->draw_m->getDrawProcessById($this->input->post('drawBatchId'));
            if($drawBatch->round_or_length_completed > 0){
                $response = ['status' => 200, 'message' =>'success', 'description' =>'This batch is in the process, it cannot be deleted.'];
            }
            else{
                $isItemDeleted = $this->draw_m->deleteDrawBatch($this->input->post('drawBatchId'));
                if($isItemDeleted){
                    

                    // DEDUCT COMPLETED ROUND IN THE ACID TREATMENT BATCH AND CHANGE STATUS
                    $acidTreatmentHistory = $this->acidtreatment_m->getAcidTreatmentHistory($drawBatch->acid_treament_process_history_id);

                    $isHistoryItemDeleted = $this->acidtreatment_m->deleteAcidTreatmentHistory($drawBatch->acid_treament_process_history_id);
                    if($isHistoryItemDeleted){

                        $this->acidtreatment_m->deleteRoundLengthCompletedInAcidTreatment($acidTreatmentHistory);
                        $response = ['status' => 200, 'message' =>'success', 'description' =>'Draw batch deleted successfully.'];
                    }
                    else{
                        $response = ['status' => 200, 'message' =>'success', 'description' =>'Something went wrong.'];
                    }
                }
                else{
                    $response = ['status' => 200, 'message' =>'success', 'description' =>'Something went wrong.'];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

	//CLASS ENDS
}
