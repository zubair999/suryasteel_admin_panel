<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Media extends REST_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getAllImage_get() {
		$this->db->select('image_id, thumbnail');
		$this->db->from('images');
		$images = $this->db->get()->result_array();

		foreach($images as $key => $i){
			$images[$key]['thumbnail'] = BASEURL.'upload/'.$i['thumbnail'];
        }

		$res = ['status'=>200, 'message'=>'success',  'description'=>'Media fetched successfully.', 'data'=> $images];
		echo json_encode($res);
	}


//CLASS ENDS
}