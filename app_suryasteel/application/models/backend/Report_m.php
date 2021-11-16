<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Report_m extends MY_Model {

    public function __construct()
	{
		parent::__construct();   
	}

    public function get_report(){

        $sales_manufacturing_report = $this->get_sales_manufacturing_report();
        $get_stock_report = $this->get_stock_report();

        return array(
            'sales_and_manufacturing' => $sales_manufacturing_report,
            'stock' => $get_stock_report
        );
    }

    private function get_sales_manufacturing_report(){
        return array(
            'data' => array(
                'total_manufacturing' => $this->get_total_manufacturing(),
                'total_sales' => 200, 
                'total_stock' => 1600
            ),
            'chart_data' => [1800, 200, 1600]
        );
    }

    private function get_stock_report(){
        return array(
            'data' => array(
                'pin' => 800,
                'rakab' => 300, 
                'jack rod' => 500
            ),
            'chart_data' => [800, 300, 500]
        );
    }
    
    private function get_total_manufacturing(){
        $this->db->select_sum('stock');
        $this->db->select('stock');
        $this->db->from('products');
        return $this->db->get()->row()->stock;
    }

    // private function get_total_manufacturing(){
    //     $this->db->select_sum('stock');
    //     $this->db->select('stock');
    //     $this->db->from('products');
    //     return $this->db->get()->row()->stock;
    // }

//end class

}
