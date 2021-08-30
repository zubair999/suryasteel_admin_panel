<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Unit extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function getUnit_get(){
		$unit = $this->unit_m->getUnit();
		$response = ['status'=>200,'message'=>'success','description'=>'Unit fetched successfully.', 'data'=>$unit];
        echo json_encode($response);
        exit();
	}
	



//CLASS ENDS
}