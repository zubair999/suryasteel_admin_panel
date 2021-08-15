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
					$response = ['status' => 200, 'message' => 'success', 'description' => 'New category added successfully.'];
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
        $this->db->select('c.category_id, c.category_name, c.thumbnail, i.actual, i.thumbnail');
		$this->db->from('category as c');
		$this->db->where('c.product_count >', 0);
		$this->db->join('images as i', 'c.thumbnail = i.image_id');
		$category = $this->db->get()->result_array();

		foreach($category as $key => $c){
            $category[$key]['actual'] = BASEURL.'upload/'.$c['actual'];
			$category[$key]['thumbnail'] = BASEURL.'upload/'.$c['thumbnail'];			
		}


		$res = ['status'=>200,'message'=>'success','description'=>'Category fetched successfully.', 'data'=>$category];

        echo json_encode($res);
        exit();
	}




	



//CLASS ENDS
}