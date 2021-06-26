<?php

class Brand extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getbrand(){
        $this->db->select('brand.brand_id, brand.brand_name, brand.product_count, images.actual, images.thumbnail');
		$this->db->from('brand');
		$this->db->join('images', 'brand.thumbnail = images.image_id');
		$brand = $this->db->get()->result_array();

		foreach($brand as $key => $b){
            $brand[$key]['actual'] = BASEURL.'upload/'.$b['actual'];
			$brand[$key]['thumbnail'] = BASEURL.'upload/'.$b['thumbnail'];
        }


        echo json_encode($brand);
        exit();
	}


	



//CLASS ENDS
}