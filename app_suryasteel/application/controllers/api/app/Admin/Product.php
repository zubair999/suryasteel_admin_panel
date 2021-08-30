<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function addProduct_post(){
        $method = $this->_detect_method();
        if (!$method == 'POST') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{
            $this->form_validation->set_rules($this->product_m->productAddRulesApp);            
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
                $isAdded = $this->product_m->addProduct($this->input->post('createdBy'));
				$this->log_m->Log($this->input->post('createdBy'), 'Product','A product is added successfully.');
				if($isAdded){
                    $products = $this->productSectionList();
					$response = ['status' => 200, 'message' => 'success', 'description' => 'New product added successfully.', 'data'=>$products];
				}
				else{
					$response = ['status' => 200, 'message' => 'error', 'description' => 'Something went wrong.'];
				}
			}
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
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
            p.stock,
            p.is_active,
            p.weight_per_piece,
            p.size,
            p.length,
            p.zinc_or_without_zinc,
            p.having_kunda,
            p.having_nut,
            u.unit_value,
            c.category_name,
            i.thumbnail,
            i.actual
            '
        );

        $this->db->from('products as p');
        $this->db->join('images as i', 'p.thumbnail = i.image_id');
        $this->db->join('category as c', 'c.category_id = p.category_id');
        $this->db->join('units as u', 'p.unit = u.unit_id');
        $this->db->where('p.category_id', $id);
        $this->db->order_by('p.product_name','asc');
        $products = $this->db->get()->result_array();

        foreach($products as $key => $p){
            $product_wise_order_count = $this->order_m->productWiseOrderCount($p['product_id']);
            $products[$key]['actual'] = BASEURL.'upload/'.$p['actual'];
            $products[$key]['thumbnail'] = BASEURL.'upload/'.$p['thumbnail'];
            $products[$key]['isAddedToCart'] = false;
            $products[$key]['productWiseOrderCount'] = $product_wise_order_count;
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
            $this->form_validation->set_rules("productId", 'product ', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
				$response = ['status' => 200, 'message' => 'error', 'description' => validation_errors()];
			} else {
				$this->db->where('product_id', $this->input->post('productId'));
				$isDeleted = $this->db->delete('products');
				$this->log_m->Log($this->input->post('deletedBy'), 'Product','A product is deleted successfully.');
				if($isDeleted){
					$products = $this->productSectionList();
					$response = ['status' => 200, 'message' => 'success', 'description' => 'Product deleted successfully.', 'data'=>$products];
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