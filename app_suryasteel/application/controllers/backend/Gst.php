<?php

class Gst extends Backend_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function add(){
		$this->data['page_title'] = 'Add Brand';
		$this->admin_view('backend/brand/add', $this->data);
    }
	
	public function index(){
		$gst = $this->db->get('gst')->result_array();

		$this->data['gst'] = $gst;
		$this->data['page_title'] = 'GST';
		$this->admin_view('backend/gst/index', $this->data);
    }


	



//CLASS ENDS
}