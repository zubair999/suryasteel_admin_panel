<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Challan extends REST_Controller
{

	public function __construct() {
		parent::__construct();
	}

    public function createDeliveryChallan_post(){
        echo $this->load->view('backend/challan/delivery_challan', '', true);
    }


//CLASS ENDS
}
