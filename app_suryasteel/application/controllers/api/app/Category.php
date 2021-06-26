<?php

class Category extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getcategory(){
        $this->db->select('c.category_id, c.category_name, c.thumbnail, c.is_top_category, c.product_count, i.actual, i.thumbnail');
		$this->db->from('category as c');
		$this->db->where('c.product_count >', 0);
		$this->db->join('images as i', 'c.thumbnail = i.image_id');
		$category = $this->db->get()->result_array();

		foreach($category as $key => $c){
            $category[$key]['actual'] = BASEURL.'upload/'.$c['actual'];
			$category[$key]['thumbnail'] = BASEURL.'upload/'.$c['thumbnail'];
			$this->db->select('sc.sub_category_id, sc.sub_category_name, sc.thumbnail, sc.product_count, i.actual, i.thumbnail');
			$this->db->from('sub_category as sc');
			$this->db->where('sc.category_id', $c['category_id']);
			$this->db->where('sc.product_count >', 0);
			$this->db->join('images as i', 'sc.thumbnail = i.image_id');
			$subCategory = $this->db->get()->result_array();

			foreach($subCategory as $key1 => $sc){
				$subCategory[$key1]['actual'] = BASEURL.'upload/'.$sc['actual'];
				$subCategory[$key1]['thumbnail'] = BASEURL.'upload/'.$sc['thumbnail'];
			}

			$category[$key]['sub_category'] = $subCategory;

			
		}

        echo json_encode($category);
        exit();
	}


	



//CLASS ENDS
}