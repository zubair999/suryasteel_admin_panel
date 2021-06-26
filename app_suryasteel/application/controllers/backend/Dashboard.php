<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->data['page_title'] = 'Dashboard';
        $this->admin_view('backend/dashboard/index', $this->data);
    }

    private function adminreport(){
      $all_order  = $this->db->get_obj('orders')->num_rows();
      $today_order  = $this->db->get_obj('orders','oid',array('order_date_time'=>$this->today))->num_rows();
      return array('all order' => $all_order);
    }
    private function dispatch() {
        switch (1) {
            case 1:
                return $this->adminreport();
            break;
        }
    }



// CLASS ENDS
}
