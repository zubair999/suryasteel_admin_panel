<?php

class Type extends Backend_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function add(){
		$this->data['page_title'] = 'Add Brand';
		$this->admin_view('backend/brand/add', $this->data);
    }
	
	public function index(){
		$type = $this->db->get('type')->result_array();

		$this->data['type'] = $type;
		$this->data['page_title'] = 'Type';
		$this->admin_view('backend/type/index', $this->data);
    }


	



//CLASS ENDS
}