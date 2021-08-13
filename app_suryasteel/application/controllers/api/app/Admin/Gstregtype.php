<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Gstregtype extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function getGstRegType_get(){
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 200, 'message'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $this->db->select('gst_reg_type_id,gst_reg_type_value');
            $this->db->from('gst_reg_type');
            $this->db->order_by('gst_reg_type_value','asc');
            $gstregtypeArr = $this->db->get()->result_array();
            foreach ($gstregtypeArr as $key => $value) {
                $gstregtypeArr[$key]['value'] = $value['gst_reg_type_id'];
                $gstregtypeArr[$key]['label'] = ucwords($value['gst_reg_type_value']);
                unset($gstregtypeArr[$key]['gst_reg_type_id']);
                unset($gstregtypeArr[$key]['gst_reg_type_value']);
            }
            $res = ['status'=>200,'message'=>'success','description'=>'Gst Reg. Type fetched successfully.', 'data'=>$gstregtypeArr];
            $this->response($res, REST_Controller::HTTP_OK);
        }
    }


	

	






	//CLASS ENDS
}
