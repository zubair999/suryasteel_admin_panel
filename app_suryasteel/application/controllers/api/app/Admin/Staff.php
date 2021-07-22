<?php

class Staff extends MY_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function add(){
		$this->staff_api_m->add_staff($this->input->post());
	}


	

	






	//CLASS ENDS
}
