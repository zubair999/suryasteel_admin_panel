<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Report extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function getReport_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $data = $this->report_m->get_report();
            $response = ['status' => 200, 'message' => 'success', 'description' => 'Reports fetched successfully.', 'data'=>$data];
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
            
        }        
	}

    public function getStockReport_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("since", "Since", "trim|required");
            $this->form_validation->set_rules("until", "Until", "trim|required");
            if($this->form_validation->run() == FALSE){
                $response = ['status' => 200, 'message' => 'error', 'description' => 'Invalid dates.'];
            } else {
                $data = $this->report_m->get_stock1_report($this->input->post('since'), $this->input->post('until'));
                $response = ['status' => 200, 'message' => 'success', 'description' => 'Reports fetched successfully.', 'data'=>$data];
                $this->response($response, REST_Controller::HTTP_OK);
                exit();
            }


        }        
	}

	//CLASS ENDS
}
