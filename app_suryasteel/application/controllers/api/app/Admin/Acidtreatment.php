<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Acidtreatment extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function addAcidTreatment_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->acidtreatment_m->acidTreatmentAddRules);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $purchaseItem = $this->purchase_m->getPurchaseItem($this->input->post('purchaseItemId'));
                $isRoundLengthAvailable = is_greater_than($purchaseItem->round_or_length_availble, $purchaseItem->round_or_length_added_to_process + (int)$this->input->post('roundOrLengthToBeCompleted'));
                if($isRoundLengthAvailable){
                    $isAdded = $this->acidtreatment_m->addAcidTreatment($this->input->post('addedBy'));
                    if($isAdded){
                        set_purchase_status_catalog($this->input->post('purchaseItemId'), 2);
                        $response = ['status' => 200, 'message' => 'success', 'description' => 'Batch added to the acid treatment.'];
                    }
                    else{
                        $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
                    }                
                }
                else{                    
                    $currentLengthAvailable = (int)$purchaseItem->round_or_length_availble - (int)$purchaseItem->round_or_length_added_to_process;
                    $response = ['status' => 200, 'message' => 'error', 'description' => $currentLengthAvailable.' round is availble right now. You can not add more then that.'];
                }
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function addAcidTreatmentHistory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->acidtreatment_m->acidTreatmentAddHistoryRules);
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $isAdded = $this->acidtreatment_m->addAcidTreatmentHistory($this->input->post('completedBy'));
                if($isAdded['status'] == 'success'){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Acid treatment completed for the batch successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => $isAdded['message']];
                }
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
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
