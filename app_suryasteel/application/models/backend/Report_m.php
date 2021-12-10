<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Report_m extends MY_Model {

    public function __construct()
	{
		parent::__construct();   
	}

    public function get_report(){
        $customer_id = $this->input->post('customerId');
        $since = $this->input->post('since');
        $until = $this->input->post('until');

        $sales_manufacturing_report = $this->get_sales_manufacturing_report($since, $until);
        $get_stock_report = $this->get_stock_report($since, $until);
        $customer_wise_report = $this->get_customer_wise($customer_id, $since, $until);

        return array(
            'sales_and_manufacturing' => $sales_manufacturing_report,
            'stock' => $get_stock_report,
            'customer_wise_report' => $customer_wise_report,
        );
    }

    public function get_sales_manufacturing_report($since, $until){
        if(!empty($since) && !empty($until)){
            return array(
                'data' => array(
                    'total_manufacturing_in_kg' => $this->get_total_manufacturing_in_kg($since, $until),
                    'total_manufacturing_in_pcs' => $this->get_total_manufacturing_in_pcs($since, $until),
                    'total_sales_in_kg' => $this->get_total_sales_in_kg($since, $until),
                    'total_sales_in_pcs' => $this->get_total_sales_in_pcs($since, $until)
                )
            );
        }
        else{
            return array(
                'data' => array(
                    'total_manufacturing_in_kg' => 0,
                    'total_manufacturing_in_pcs' => 0,
                    'total_sales_in_kg' => 0,
                    'total_sales_in_pcs' => 0
                )
            );
        }
    }

    public function get_stock_report($since, $until){
        if(!empty($since) && !empty($until)){
            return array(
                'data' => $this->category_wise_stock_report()
            );
        }
        else{
            return array(
                'data' => []
            );
        }
    }
    
    public function get_total_manufacturing_in_kg($since, $until){
        $this->db->select_sum('stock_in_kg');
        $this->db->from('stock_manufactured');
        $this->db->where('created_on >= ', $since);
        $this->db->where('created_on <=', $until);
        return $this->db->get()->row()->stock_in_kg;
    }

    public function get_total_manufacturing_in_pcs($since, $until){
        $this->db->select_sum('stock_in_pcs');
        $this->db->from('stock_manufactured');
        $this->db->where('created_on >= ', $since);
        $this->db->where('created_on <=', $until);
        return $this->db->get()->row()->stock_in_pcs;
    }

    public function get_total_sales_in_kg($since, $until){
        $this->db->select_sum('dispatch_quantity');
        $this->db->from('order_item_dispatch');
        $this->db->where('delivery_status', 'Delivered');
        $this->db->where('created_on >= ', $since);
        $this->db->where('created_on <=', $until);
        return $this->db->get()->row()->dispatch_quantity;
    }

    public function get_total_sales_in_pcs($since, $until){
        $this->db->select_sum('dispatch_quantity_in_pcs');
        $this->db->from('order_item_dispatch');
        $this->db->where('delivery_status', 'Delivered');
        $this->db->where('created_on >= ', $since);
        $this->db->where('created_on <=', $until);
        return $this->db->get()->row()->dispatch_quantity_in_pcs;
    }

    public function category_wise_stock_report(){
        $this->db->distinct();
        $this->db->select('category_id');
        $category = $this->db->get('products')->result_array();


        foreach($category as $key => $c){
            $this->db->select_sum('stock');
            $this->db->where('category_id', $c['category_id']);
            $stock_in_kg = $this->db->get('products')->row()->stock;

            $this->db->select_sum('stock_in_pcs');
            $this->db->where('category_id', $c['category_id']);
            $stock_in_pcs = $this->db->get('products')->row()->stock_in_pcs;

            $this->db->select('category_name');
            $this->db->where('category_id', $c['category_id']);
            $category_name = $this->db->get('category')->row()->category_name;
            
            $category[$key][$category_name] = $category_name;

            $category[$key]['stock_in_kg'] = $category_name.'(in Kg): '. $stock_in_kg;
            $category[$key]['stock_in_pcs'] = $category_name.'(in Pcs): '. $stock_in_pcs;
        }

        return $category;
    }

    public function get_total_order_in_kg_by_customer($customer_id, $since, $until){
        if(!empty($customer_id) && !empty($since) && !empty($until)){
            $this->db->select_sum('weight_to_be_dispatched');
            $this->db->from('order_item');
            $this->db->where('user_id', $customer_id);
            return $this->db->get()->row()->weight_to_be_dispatched;
        }
        else{
            return null;
        }
    }

    public function get_total_dispatch_for_a_customer($customer_id, $since, $until){
        $this->db->select_sum('dispatched_qty');
        $this->db->from('order_item');
        $this->db->where('user_id', $customer_id); 
        return $this->db->get()->row()->dispatched_qty;
    }

    public function product_bought_by_customer($customer_id, $since, $until){
        $this->db->distinct();
        $this->db->select('product_id');
        $this->db->where('created_on >= ', $since);
        $this->db->where('created_on <=', $until);
        $this->db->where('user_id', $customer_id);
        $product = $this->db->get('order_item')->result_array();

        foreach($product as $key => $p){
            $this->db->select_sum('weight_to_be_dispatched');
            $this->db->where('product_id', $p['product_id']);
            $total_bought = $this->db->get('order_item')->row()->weight_to_be_dispatched;

            $this->db->select('product_name');
            $this->db->where('product_id', $p['product_id']);
            $product_name = $this->db->get('products')->row()->product_name;
            
            $product[$key]['product_name'] = $product_name;
            $product[$key]['total_bought'] = $total_bought.'Kg';
        }

        return $product;
    }

    public function get_customer_wise($customer_id, $since, $until){
        if(!empty($customer_id) && !empty($since) && !empty($until)){
            return array(
                'data' => array(
                    'total_order' => $this->get_total_order_in_kg_by_customer($customer_id, $since, $until),
                    'total_dispatch' => $this->get_total_dispatch_for_a_customer($customer_id, $since, $until),
                    'product_bought' => $this->product_bought_by_customer($customer_id, $since, $until)
                )
            );
        }
        else{
            return array(
                'data' => array(
                    'total_order' => 0,
                    'total_dispatch' => 0,
                    'product_bought' => []
                )
            );
        }
    }

    





//end class
}
