<?php

class Setting extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function getSetting(){
		$this->db->select('name, value');
		$this->db->from('settings');
		$setting = $this->db->get()->result_array();
        echo json_encode($setting);
        exit();
	}


	



//CLASS ENDS
}