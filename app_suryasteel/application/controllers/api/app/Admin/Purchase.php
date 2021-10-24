<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Purchase extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function addPurchase_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules('createdBy', 'Created By', 'trim|required');
            $this->form_validation->set_rules('invoiceWeight', 'Invoice Weight', 'trim|required');
            $this->form_validation->set_rules('actualWeight', 'Actual Weight', 'trim|required');
            $this->form_validation->set_rules('rate', 'Rate', 'trim|required');
            $this->form_validation->set_rules('freightCharge', 'Freight Charge', 'trim|required');
            $this->form_validation->set_rules('unloadingCharge', 'Unloading Charge', 'trim|required');
            $this->form_validation->set_rules('vendor', 'Vendor', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' =>'error', 'description' =>validation_errors()];
            }
            else{
                $isPurchaseAdded = $this->purchase_m->addPurchase($this->input->post('createdBy'));
                if($isPurchaseAdded['response']){
                    $this->purchase_m->addPurchaseItem($isPurchaseAdded['last_purchase_id']);
                    $response = ['status' => 200, 'message' =>'success', 'description' =>"Purchase created successfully."];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>"Something went wrong."];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}


    public function editPurchase_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $purchaseRowCount = $this->acidtreatment_m->getAcidTreatmentCountByPurchaseId($this->input->post('purchaseId'));
            if($purchaseRowCount > 0){
                $response = ['status' => 200, 'message' =>'error', 'description' =>"Acid treatment is started on the purchase items. You can not updated it. Add new purchase."];
            }
            else{
                $isPurchaseUpdated = $this->purchase_m->editPurchase($this->input->post('purchaseId'));
                if($isPurchaseUpdated){
                    $this->purchase_m->editPurchaseItem();
                    $response = ['status' => 200, 'message' =>'success', 'description' =>"Purchase updated successfully."];
                }
                else{
                    $response = ['status' => 200, 'message' =>'error', 'description' =>"Something went wrong."];
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

    public function getPurchase_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->purchase_m->get_purchase();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Order fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

    public function deletePurchase_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $purchaseCount = $this->purchase_m->getPurchaseCount($this->input->post('purchaseId'));
            if($purchaseCount > 0){
                $purchaseCount = $this->acidtreatment_m->getAcidTreatmentCountByPurchaseId($this->input->post('purchaseId'));
                if($purchaseCount > 0){
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Acid treatment started on one or more of the purchase item. First delete acid treatment batch then try again.'];
                }
                else{
                    $isDeleted = $this->purchase_m->delete_purchase($this->input->post('purchaseId'));
                    if($isDeleted){
                        $response = ['status' => 200, 'message' => 'success', 'description' => 'Purchase deleted successfully.'];
                    }
                    else{
                        $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
                    }
                }
            }
            else{
                $response = ['status' => 200, 'message' => 'error', 'description' => 'There is no purchase found for this id.'];
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }  
    }

    public function deletePurchaseItem_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $purchaseCount = $this->acidtreatment_m->getAcidTreatmentCountByPurchaseItemId($this->input->post('purchaseItemId'));
            if($purchaseCount > 0){
                $response = ['status' => 200, 'message' => 'error', 'description' => 'Acid treatment started on this purchase item. First delete acid treatment batch then try again.'];
            }
            else{
                $isDeleted = $this->purchase_m->delete_purchase_item($this->input->post('purchaseItemId'));
                if($isDeleted){
                    $response = ['status' => 200, 'message' => 'success', 'description' => 'Purchase item deleted successfully.'];
                }
                else{
                    $response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];

                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }  
    }

    public function getPurchaseItem_get(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->purchase_m->get_purchase_item();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Raw material fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }        
	}

	//CLASS ENDS
}
