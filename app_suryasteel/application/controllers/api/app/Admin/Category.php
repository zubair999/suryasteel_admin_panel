<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Category extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function addCategory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("imageId", 'image ', 'trim|required');
            $this->form_validation->set_rules("category", 'category ', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$data = array(
					'thumbnail' => $this->input->post('imageId'),
					'category_name' => $this->input->post('category'),
					'status' => 'active',
					'created_by' => $this->input->post('uid'),
					'created_on' => $this->today
				);

				$isAdded = $this->db->insert('category', $data);
				$this->log_m->Log($this->input->post('uid'), 'Category','A category is added successfully.');
				if($isAdded){
					$category = $this->category_m->get_all_category_with_image();
					$response = ['status' => 200, 'message' => 'success', 'description' => 'New category added successfully.', 'data'=>$category];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function editCategory_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("imageId", 'image ', 'trim|required');
            $this->form_validation->set_rules("category", 'category ', 'trim|required');
            $this->form_validation->set_rules("category_id", 'category ', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$data = array(
					'thumbnail' => $this->input->post('imageId'),
					'category_name' => $this->input->post('category')
				);
				$this->db->where('category_id', $this->input->post('category_id'));
				$isUpdated = $this->db->update('category',$data);
				$this->log_m->Log($this->input->post('uid'), 'Category','A category is updated successfully.');
				if($isUpdated){
					$category = $this->category_m->get_all_category_with_image();
					$response = ['status' => 200, 'message' => 'success', 'description' => 'Category updated successfully.', 'data'=>$category];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

	public function getcategory_get(){
		$category = $this->category_m->get_all_category_with_image();
		$response = ['status'=>200,'message'=>'success','description'=>'Category fetched successful.', 'data'=>$category];
        $this->response($response, REST_Controller::HTTP_OK);
        exit();
	}

	public function deleteCategory_post(){
		$method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules("category_id", 'category ', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$is_category_having_product = $this->category_m->categoryCountInProduct($this->input->post('category_id'));
				
				if($is_category_having_product > 0){
					$response = ['status' => 200, 'message' => 'success', 'description' => 'Category can not be deleted, as it\'s having products.'];
				}
				else{
					$this->db->where('category_id', $this->input->post('category_id'));
					$isDeleted = $this->db->delete('category');
					$this->log_m->Log($this->input->post('uid'), 'Category','A category is deleted successfully.');
					if($isDeleted){
						$category = $this->category_m->get_all_category_with_image();
						$response = ['status' => 200, 'message' => 'success', 'description' => 'Category deleted successfully.', 'data'=>$category];
					}
					else{
						$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
					}
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}

//CLASS ENDS
}