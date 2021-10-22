<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Deliverymode extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getDeliveryMode_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('delivery_mode_id,delivery_mode_name');
            $this->db->from('delivery_mode');
            $this->db->order_by('delivery_mode_name','asc');
            $roleArr = $this->db->get()->result_array();
            foreach ($roleArr as $key => $value) {
                $roleArr[$key]['value'] = $value['delivery_mode_id'];
                $roleArr[$key]['label'] = ucwords($value['delivery_mode_name']);
                unset($roleArr[$key]['delivery_mode_id']);
                unset($roleArr[$key]['delivery_mode_name']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Delivery mode fetched successfully.', 'data'=>$roleArr];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }



	//CLASS ENDS
}
