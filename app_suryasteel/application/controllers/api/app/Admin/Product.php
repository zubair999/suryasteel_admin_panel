<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function getProduct_get() {
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $products = $this->productSectionList();
            $res = ['status'=>200,'message'=>'success','description'=>'Products fetched successfully.', 'data'=>$products];
            $this->response($res, REST_Controller::HTTP_OK);
            exit();
        }
    }

    private function productSectionList(){
        $this->db->distinct();
        $this->db->select('p.category_id, c.category_name');
        $this->db->from('products as p');
        $this->db->join('category as c', 'p.category_id = c.category_id');
        $category = $this->db->get()->result_array();

        foreach ($category as $key => $c){
            $p = $this->getProductByCategory($c['category_id']);
            $category[$key]['title'] = $c['category_name'];
            $category[$key]['data'] = $p;
            unset($category[$key]['category_id']);
            unset($category[$key]['category_name']);
        }
        return $category;
    }

    private function getProductByCategory($id){
        $this->db->select(
            'p.product_id,
            p.category_id,
            p.product_name,
            p.is_active,
            p.weight_per_piece,
            p.size,
            p.zinc_or_without_zinc,
            p.having_kunda,
            p.having_nut,
            c.category_name,
            i.thumbnail,
            i.actual
            '
        );

        $this->db->from('products as p');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        $this->db->join('category as c', 'c.category_id = p.category_id');
        $this->db->where('p.category_id', $id);
        $products = $this->db->get()->result_array();

        foreach($products as $key => $p){
            $products[$key]['actual'] = BASEURL.'upload/'.$p['actual'];
            $products[$key]['thumbnail'] = BASEURL.'upload/'.$p['thumbnail'];
            $products[$key]['isAddedToCart'] = false;
        }

        return $products;
    }

    public function deleteProduct_post(){
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
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
	}






//CLASS ENDS
}