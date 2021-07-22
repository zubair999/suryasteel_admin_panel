<?php

class Purchase extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function add(){
        $response = ['status'=> 200,'message'=>'okk'];
        echo json_encode($response);
    }







//CLASS ENDS
}