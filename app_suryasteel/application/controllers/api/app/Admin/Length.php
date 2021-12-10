<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Length extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function addLength_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("lengthValue", 'Length value', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$data = array(
					'length_value' => $this->input->post('lengthValue'),
					'created_by' => $this->input->post('uid'),
					'created_on' => $this->today
				);

				$isAdded = $this->db->insert('length', $data);
				$this->log_m->Log($this->input->post('uid'), 'Length','A length is added successfully.');
				if($isAdded){
					$response = ['status' => 200, 'message' => 'success', 'description' => 'New length added successfully.'];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function editLength_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("lengthValue", 'Length value', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$data = array(
					'length_value' => $this->input->post('lengthValue'),
					'updated_on' => $this->today
				);


				$this->db->where('length_id', $this->input->post('length_id'));
				$isUpdated = $this->db->update('length',$data);
				$this->log_m->Log($this->input->post('uid'), 'Length','A length is updated successfully.');
				if($isUpdated){
					$response = ['status' => 200, 'message' => 'success', 'description' => 'Length updated successfully.'];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function getLength_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('length_id,length_value');
            $this->db->from('length');
            $this->db->order_by('length_value','asc');
            $data = $this->db->get()->result_array();
            foreach ($data as $key => $value) {
                $data[$key]['value'] = $value['length_id'];
                $data[$key]['label'] = ucwords($value['length_value']);
                unset($data[$key]['length_id']);
                unset($data[$key]['length_value']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Length fetched successfully.', 'data'=>$data];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }

    public function deleteLength_post(){
		$method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("lengthId", 'Length ', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$this->db->where('length_id', $this->input->post('lengthId'));
				$isDeleted = $this->db->delete('length');
				$this->log_m->Log($this->input->post('uid'), 'Length','A length is deleted successfully.');
				if($isDeleted){
					$response = ['status' => 200, 'message' => 'success', 'description' => 'Length deleted successfully.'];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}


	

	






	//CLASS ENDS
}
