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

    public function get_sales_manufacturing_report(){
        return array(
            'data' => array(
                'total_manufacturing_in_kg' => $this->get_total_manufacturing_in_kg(),
                'total_manufacturing_in_pcs' => $this->get_total_manufacturing_in_pcs(),
                'total_sales_in_kg' => $this->get_total_sales_in_kg(),
                'total_sales_in_pcs' => $this->get_total_sales_in_pcs()
            ),
            'chart_data' => [
                (float)$this->get_total_manufacturing_in_kg(), 
                (float)$this->get_total_manufacturing_in_pcs(), 
                (float)$this->get_total_sales_in_kg(),
                (float)$this->get_total_sales_in_pcs()
            ]
        );
    }

    public function get_stock_report(){
        return array(
            'data' => $this->category_wise_stock_report()['category_sise'],
            'chart_data' => $this->category_wise_stock_report()['chart_data']
        );
    }
    
    public function get_total_manufacturing_in_kg(){
        $this->db->select_sum('stock_in_kg');
        $this->db->from('stock_manufactured');
        return $this->db->get()->row()->stock_in_kg;
    }

    public function get_total_manufacturing_in_pcs(){
        $this->db->select_sum('stock_in_pcs');
        $this->db->from('stock_manufactured');
        return $this->db->get()->row()->stock_in_pcs;
    }

    public function get_total_sales_in_kg(){
        $this->db->select_sum('dispatch_quantity');
        $this->db->from('order_item_dispatch');
        $this->db->where('delivery_status', 'Delivered');
        return $this->db->get()->row()->dispatch_quantity;
    }

    public function get_total_sales_in_pcs(){
        $this->db->select_sum('dispatch_quantity_in_pcs');
        $this->db->from('order_item_dispatch');
        $this->db->where('delivery_status', 'Delivered');
        return $this->db->get()->row()->dispatch_quantity_in_pcs;
    }

    public function category_wise_stock_report(){
        $this->db->distinct();
        $this->db->select('category_id');
        $category_arr = $this->db->get('products')->result_array();


        foreach($category_arr as $key => $c){
            $this->db->select_sum('stock');
            $this->db->where('category_id', $c['category_id']);
            $stock_in_kg = $this->db->get('products')->row()->stock;

            $this->db->select_sum('stock_in_pcs');
            $this->db->where('category_id', $c['category_id']);
            $stock_in_pcs = $this->db->get('products')->row()->stock_in_pcs;

            $this->db->select('category_name');
            $this->db->where('category_id', $c['category_id']);
            $category_name = $this->db->get('category')->row()->category_name;
            
            $category_arr[$key][$category_name] = $category_name;

            $category_arr[$key]['stock_in_kg'] = $category_name.'(in Kg): '. $stock_in_kg;
            $category_arr[$key]['stock_in_pcs'] = $category_name.'(in Pcs): '. $stock_in_pcs;
        }

        // // print_r($category_arr);
        // // die;

        // $category_wise = array(
        //     'pin' => 800,
        //     'rakab' => 300, 
        //     'jack rod' => 500
        // );

        $chart_data = [800, 300, 700];

        return ['category_sise'=> $category_arr, 'chart_data'=> $chart_data];
    }

//end class

}
