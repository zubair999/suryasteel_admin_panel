<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Paymentmode extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getPaymentMode_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('payment_mode_id, payment_mode');
            $this->db->from('payment_mode');
            $this->db->order_by('payment_mode','asc');
            $paymentModeArr = $this->db->get()->result_array();
            foreach ($paymentModeArr as $key => $value) {
                $paymentModeArr[$key]['value'] = $value['payment_mode_id'];
                $paymentModeArr[$key]['label'] = ucwords($value['payment_mode']);
                unset($paymentModeArr[$key]['payment_mode_id']);
                unset($paymentModeArr[$key]['payment_mode']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Payment modes fetched successfully.', 'data'=>$paymentModeArr];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
