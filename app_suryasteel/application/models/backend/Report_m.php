<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Report_m extends MY_Model {

    public function __construct()
	{
		parent::__construct();   
	}

    public function get_report(){

        $stock_report = array(
            'sales_and_manufacturing' => array(
                'data' => array(
                    'total_manufacturing' => 1800,
                    'total_sales' => 200, 
                    'total_stock' => 1600
                ),
                'chart_data' => [1800, 200, 1600]
            )
        );

        return $stock_report;
    }


    

//end class

}
