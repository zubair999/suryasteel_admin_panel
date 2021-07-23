<?php

class Category extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getcategory(){
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