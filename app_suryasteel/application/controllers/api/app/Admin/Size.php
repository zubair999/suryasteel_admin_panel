<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Size extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getSize_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('size_id,size_value');
            $this->db->from('size');
            $this->db->order_by('size_value','asc');
            $size = $this->db->get()->result_array();
            foreach ($size as $key => $value) {
                $size[$key]['value'] = $value['size_id'];
                $size[$key]['label'] = ucwords($value['size_value']);
                unset($size[$key]['size_id']);
                unset($size[$key]['size_value']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Size fetched successfully.', 'data'=>$size];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
