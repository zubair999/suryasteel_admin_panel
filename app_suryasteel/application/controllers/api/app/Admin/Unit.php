<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Unit extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function getUnit_get(){
		$category = $this->unit_m->getUnit();
		$res = ['status'=>200,'message'=>'success','description'=>'Unit fetched successfully.', 'data'=>$category];
        echo json_encode($res);
        exit();
	}
	



//CLASS ENDS
}